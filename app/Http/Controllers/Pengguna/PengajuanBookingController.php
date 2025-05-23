<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengguna\PengajuanBooking\PengajuanBookingStoreRequest;
use App\Http\Requests\Pengguna\PengajuanBooking\PengajuanBookingUpdateRequest;
use App\Models\HariOperasional;
use App\Models\JamOperasional;
use App\Models\LaboratoriumUnpam;
use App\Models\Lokasi;
use App\Models\PengajuanBooking;
use App\Services\PengajuanBookingStoreService;
use App\Services\PengajuanBookingUpdateService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PengajuanBookingController extends Controller
{
    public function index() {
        return view("pengguna.booking-page.pengajuan-booking", [
            'page_meta' => [
                'page' => 'Pengajuan',
                'description' => 'Halaman untuk Membuat Pengajuan Booking.'
            ]
        ]);
    }

    public function create() {

        $lokasi = Lokasi::select([
            'id',
            'nama_lokasi'
        ])->whereNot('nama_lokasi', 'fleksible')->get();

        return view("pengguna.booking-page.pengajuan.form-pengajuan-booking-store", [
            'lokasi' => $lokasi,
            'page_meta' => [
                'page' => 'Buat Pengajuan Booking',
                'description' => 'Halaman untuk input Data Pengajuan Booking'
            ] 
        ]);
    }

    public function store(PengajuanBookingStoreRequest $request, PengajuanBookingStoreService $pengajuan)
    {
        $data = $request->validated();
        // dd($data);

        // Cek apakah masih ada pengajuan booking yang menunggu (user login)
        $konflikPengajuanMenunggu = $pengajuan->cekPengajuanBookingMenungguLogin($data);
        if (!empty($konflikPengajuanMenunggu)) {
            return redirect()->route('pengajuan.create')
                ->with('pesan', 'Anda Masih Memiliki Pengajuan yang belum diproses :')
                ->withErrors(['konflik' => $konflikPengajuanMenunggu]) 
                ->withInput();
        }

        try {
            $pengajuan->buatPengajuan($data);
            return redirect()->route('pengajuan')->with('success', 'Pengajuan Booking berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('pengajuan.create')->withInput()->with('error', 'Pengajuan Booking tidak berhasil dibuat.' . $e->getMessage());
        }
    }

    public function getLaboratoriumByLokasi($lokasiId)
    {
        $laboratorium = LaboratoriumUnpam::where('lokasi_id', $lokasiId)
                        ->where('status_laboratorium', 'tersedia')
                        ->select('id', 'nama_laboratorium')
                        ->get();

        return response()->json($laboratorium);
    }

    public function getHariOperasionalByLokasi($lokasi_id)
    {
        $hariOperasional =   HariOperasional::select([
                                'id',
                                'hari_operasional',
                                'lokasi_id',
                                'is_disabled'
                            ])
                            ->where('lokasi_id', $lokasi_id)
                            ->where('is_disabled', false)
                            ->get();
        
        return response()->json($hariOperasional);
    }

    public function getJamOperasional($hariOperasionalId)
    {
        $jamOperasional = JamOperasional::where('hari_operasional_id', $hariOperasionalId)
                                        ->where('is_disabled', false)
                                        ->get(['jam_mulai', 'jam_selesai'])
                                        ->map(function ($item) {
                                            return [
                                                'jam_mulai' => date('H:i', strtotime($item->jam_mulai)),
                                                'jam_selesai' => date('H:i', strtotime($item->jam_selesai)),
                                            ];
                                        });

        return response()->json($jamOperasional);
    }
    
    public function getApiPengajuanBooking(Request $request)
    {
        // Query dasar dengan filter user login
        $query = PengajuanBooking::select([
            'id',
            'kode_booking',
            'status_pengajuan_booking',
            'keperluan_pengajuan_booking',
            'balasan_pengajuan_booking',
            'user_id',
        ])->where('user_id', Auth::id()); 

        $totalData = $query->count();

        // Cloning query untuk proses pencarian
        $filteredQuery = clone $query;

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $filteredQuery->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                    ->orWhere('status_pengajuan_booking', 'like', "%{$search}%");
            });
        }

        $totalFiltered = $filteredQuery->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'desc';

        $columns = [null, 'kode_booking', 'id', 'keperluan_pengajuan_booking', 'status_pengajuan_booking', 'balasan_pengajuan_booking', 'user_id'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumnName, $columns)) {
            $filteredQuery->orderBy($orderColumnName, $orderDirection);
        } else {
            $filteredQuery->orderBy('id', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $filteredQuery->skip($start)->take($length)->get();

        // Format response
        $result = [];
        foreach ($data as $pengajuanBooking) {
            $result[] = [
                'id_pengajuan_booking' => Crypt::encryptString($pengajuanBooking->id),
                'kode_booking' => $pengajuanBooking->kode_booking,
                'status_pengajuan_booking' => $pengajuanBooking->status_pengajuan_booking,
                'balasan_pengajuan_booking' => $pengajuanBooking->balasan_pengajuan_booking,
                'user_id' => Crypt::encryptString($pengajuanBooking->user_id),
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result,
        ]);
    }

    public function edit($id)
    {
        $pengajuan = PengajuanBooking::findOrFail(Crypt::decryptString($id));
        $jadwal = $pengajuan->jadwalBookings()->get();

        $lokasi = Lokasi::select([
            'id',
            'nama_lokasi'
        ])->whereNot('nama_lokasi', 'fleksible')->get();

        $tanggal_multi = $jadwal
            ->sortBy('tanggal_jadwal')
            ->pluck('tanggal_jadwal')
            ->map(fn($tanggal) => Carbon::parse($tanggal)->format('Y-m-d'))
            ->unique()
            ->implode(', '); // spasi habis koma (karena flatpickr)

        // dd($tanggal_multi);

        $sorted_dates = $jadwal->pluck('tanggal_jadwal')
            ->map(fn($tanggal) => Carbon::parse($tanggal)->format('Y-m-d'))
            ->sort()
            ->values();

        $tanggal_range = $sorted_dates->isNotEmpty()
            ? $sorted_dates->first() . ' - ' . $sorted_dates->last()
            : '';

        // dd($tanggal_range);

        // Ambil hari dari tanggal yang ada di jadwal
        $hari_operasional = $jadwal->pluck('tanggal_jadwal')->map(function($tanggal) {
            return Carbon::parse($tanggal)->dayOfWeek; // 0 (Minggu) sampai 6 (Sabtu)
        })->unique()->values();
        
        // dd($hari_operasional);

        $jam_per_tanggal = $jadwal->groupBy(function ($item) {
            return Carbon::parse($item->tanggal_jadwal)->format('Y-m-d');
        })->map(function ($items) {
            return $items->map(function ($item) {
                return Carbon::parse($item->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($item->jam_selesai)->format('H:i');
            })->values();
        });        

        // dd($jam_per_tanggal);

        // Kirim ke view
        return view('pengguna.booking-page.pengajuan.form-pengajuan-booking-update', [
            'pengajuan' => $pengajuan,
            'jadwal' => $jadwal,
            'lokasi' => $lokasi,
            'tanggal_multi' => $tanggal_multi,
            'tanggal_range' => $tanggal_range,
            'jam_per_tanggal' => $jam_per_tanggal,
            'hari_operasional' => $hari_operasional,
            'page_meta' => [
                'page' => 'Edit Pengajuan Booking',
                'description' => 'Halaman untuk edit Data Pengajuan Booking',
            ]
        ]);
    }
    
    public function update(PengajuanBookingUpdateRequest $request, PengajuanBooking $pengajuanBooking, PengajuanBookingUpdateService $pengajuanService)
    {
        $data = $request->validated();

        // Cek konflik pengajuan 'menunggu', tapi abaikan pengajuan yang sedang diedit
        $konflikPengajuanMenunggu = $pengajuanService->cekPengajuanBookingMenungguLogin($pengajuanBooking, $data);

        if (!empty($konflikPengajuanMenunggu)) {
            return redirect()->route('pengajuan.edit', $pengajuanBooking->id)
                ->with('pesan', 'Anda masih memiliki pengajuan yang belum diproses:')
                ->withErrors(['konflik' => $konflikPengajuanMenunggu])
                ->withInput();
        }

        try {
            $pengajuanService->ubahPengajuan($pengajuanBooking, $data);
            return redirect()->route('pengajuan')->with('success', 'Pengajuan Booking berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('pengajuan.edit', $pengajuanBooking->id)
                ->withInput()
                ->with('error', 'Pengajuan Booking gagal diperbarui. ' . $e->getMessage());
        }
    }

    public function batalkanPengajuanBooking($id,Request $request)
    {
        DB::beginTransaction();

        $request->validate([
            'balasan_pengajuan_booking' => 'required'
        ]);

        try {

            $pengajuanBookingId = Crypt::decryptString($id);
            $pengajuanBooking = PengajuanBooking::with('jadwalBookings')->findOrFail($pengajuanBookingId);

            $pengajuanBooking->update([
                'status_pengajuan_booking' => 'dibatalkan',
                'balasan_pengajuan_booking' => $request->balasan_pengajuan_booking
            ]);

            $pengajuanBooking->jadwalBookings()->update([
                'status' => 'dibatalkan'
            ]);

            DB::commit();
            return redirect()->route('pengajuan')->with('success', 'Pengajuan Berhasil Dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('pengajuan')->with('error', 'Pengajuan Tidak Berhasil Dibatalkan.' . $e->getMessage());
        }
    }
}

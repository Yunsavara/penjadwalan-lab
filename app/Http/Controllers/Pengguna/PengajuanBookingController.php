<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengguna\PengajuanBooking\PengajuanBookingStoreRequest;
use App\Models\HariOperasional;
use App\Models\JadwalBooking;
use App\Models\JamOperasional;
use App\Models\LaboratoriumUnpam;
use App\Models\Lokasi;
use App\Models\PengajuanBooking;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function store(PengajuanBookingStoreRequest $request)
    {
        $data = $request->validated();

        // dd($data);

        DB::beginTransaction();

        try {
            // Membuat kode booking unik
            $kodeBooking = 'PBL-' . strtoupper(Str::random(8));

            // Menyimpan data pengajuan booking
            $pengajuanBooking = PengajuanBooking::create([
                'kode_booking' => $kodeBooking,
                'keperluan_pengajuan_booking' => $data['keperluan_pengajuan_booking'],
                'status_pengajuan_booking' => 'menunggu',
                'mode_tanggal_pengajuan' => $data['mode_tanggal'],
                'lokasi_id' => $data['lokasi_pengajuan_booking'],
                'user_id' => auth()->id(),
            ]);

            // Jika mode tanggal adalah multi
            if ($data['mode_tanggal'] === 'multi' && $data['tanggal_multi']) {
                foreach ($data['tanggal_multi'] as $tanggal) {
                    foreach ($data['jam'][$tanggal] as $jam) {
                        list($jamMulai, $jamSelesai) = explode(' - ', $jam);
                        foreach ($data['laboratorium_pengajuan_booking'] as $labId) {
                            JadwalBooking::create([
                                'tanggal_jadwal' => $tanggal,
                                'jam_mulai' => $jamMulai,
                                'jam_selesai' => $jamSelesai,
                                'pengajuan_booking_id' => $pengajuanBooking->id,
                                'laboratorium_unpam_id' => $labId,
                            ]);
                        }                        
                    }
                }
            }

            // Jika mode tanggal adalah rentang
            if ($data['mode_tanggal'] === 'range' && $data['tanggal_range']) {
                $startDate = $data['tanggal_range']['start'];
                $endDate = $data['tanggal_range']['end'];

                // Mengiterasi setiap hari dalam rentang tanggal
                $start = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);

                while ($start->lte($end)) {
                    if (in_array($start->dayOfWeek, $data['hari_operasional'])) {
                        // Pastikan tanggal ada di array 'jam' sebelum diproses
                        $tanggalString = $start->toDateString();
                        if (isset($data['jam'][$tanggalString])) {
                            foreach ($data['jam'][$tanggalString] as $jam) {
                                list($jamMulai, $jamSelesai) = explode(' - ', $jam);
                                foreach ($data['laboratorium_pengajuan_booking'] as $labId) {
                                    JadwalBooking::create([
                                        'tanggal_jadwal' => $tanggalString,
                                        'jam_mulai' => $jamMulai,
                                        'jam_selesai' => $jamSelesai,
                                        'pengajuan_booking_id' => $pengajuanBooking->id,
                                        'laboratorium_unpam_id' => $labId,
                                    ]);
                                }                                
                            }
                        }
                    }

                    // Pindah ke tanggal berikutnya
                    $start->addDay();
                }
            }

            DB::commit();
            return redirect()->route('pengajuan')->with('success', 'Pengajuan booking berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.' . $e->getMessage()])->withInput();
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
        $query =    PengajuanBooking::select([
                        'id',
                        'kode_booking',
                        'status_pengajuan_booking',
                        'keperluan_pengajuan_booking',
                        'balasan_pengajuan_booking',
                        'user_id',
                    ]);

        // Pencarian
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                    ->orWhere('status_pengajuan_booking', 'like', "%{$search}%");
            });
        }

        $totalData = PengajuanBooking::count();
        $totalFiltered = $query->count();

        // Sorting
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection = $request->input('order.0.dir') ?? 'desc';

        $columns = [null, 'kode_booking','id', 'keperluan_pengajuan_booking', 'status_pengajuan_booking', 'balasan_pengajuan_booking', 'user_id'];
        $orderColumnName = $columns[$orderColumnIndex] ?? 'id';

        if (in_array($orderColumnName, ['id','kode_booking', 'keperluan_pengajuan_booking','status_pengajuan_booking', 'balasan_pengajuan_booking', 'user_id'])) {
            $query->orderBy($orderColumnName, $orderDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination (limit data yang tampil per page)
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;

        $data = $query->skip($start)->take($length)->get();

        $result = [];
        foreach ($data as $index => $pengajuanBooking) {
            $result[] = [
                'id_pengajuan_booking' => Crypt::encryptString($pengajuanBooking->id),
                'kode_booking' => $pengajuanBooking->kode_booking,
                'status_pengajuan_booking' => $pengajuanBooking->status_pengajuan_booking,
                'balasan_pengajuan_booking' => $pengajuanBooking->balasan_pengajuan_booking,
                'status_pengajuan_booking' => $pengajuanBooking->status_pengajuan_booking,
                'user_id' => Crypt::encryptString($pengajuanBooking->user_id)
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $result
        ]);
    }
   
    public function edit($id)
    {
        $pengajuan = PengajuanBooking::findOrFail(Crypt::decryptString($id));
        $jadwal = $pengajuan->jadwalBookings()->get();

        $lokasi = Lokasi::all();

        return view('pengguna.booking-page.pengajuan.form-pengajuan-booking-update', [
            'pengajuan' => $pengajuan,
            'jadwal' => $jadwal,
            'lokasi' => $lokasi,
            'page_meta' => [
                'page' => 'Edit Pengajuan Booking',
                'description' => 'Halaman untuk edit Data Pengajuan Booking',
            ]
        ]);
    }
    
    // public function update(PengajuanBookingStoreRequest $request, PengajuanBooking $pengajuan)
    // {
    //     DB::beginTransaction();

    //     try {
    //         // Update data utama pengajuan
    //         $pengajuan->update([
    //             'keperluan_pengajuan_booking' => $request->keperluan_pengajuan_booking,
    //             // Jika status atau balasan ingin bisa diubah juga, tambahkan di sini
    //         ]);

    //         // Hapus jadwal lama
    //         $pengajuan->jadwalBookings()->delete();

    //         // Simpan jadwal baru
    //         $pivotData = $request->toPivotData();

    //         foreach ($pivotData as $data) {
    //             JadwalBooking::create([
    //                 'pengajuan_booking_id' => $pengajuan->id,
    //                 'laboratorium_unpam_id' => $data['laboratorium_id'],
    //                 'tanggal_jadwal' => $data['tanggal'],
    //                 'jam_mulai' => $data['jam_mulai'],
    //                 'jam_selesai' => $data['jam_selesai'],
    //                 'status' => 'menunggu', // default
    //             ]);
    //         }

    //         DB::commit();

    //         return redirect()->route('pengajuan-booking.index')->with('success', 'Pengajuan berhasil diperbarui.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
    //     }
    // }
}

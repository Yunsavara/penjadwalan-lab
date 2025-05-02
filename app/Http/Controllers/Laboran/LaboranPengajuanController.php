<?php

namespace App\Http\Controllers\Laboran;

use App\Models\Booking;
use App\Models\BookingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LaboranPengajuanController extends Controller
{
    // Page Jadwal
    public function index()
    {
        return view('laboran.jadwal-page.index', [
            'page_meta' => [
                'page' => 'Jadwal Penggunaan'
            ]
        ]);
    }

    // Page Pengajuan
    public function viewPengajuan()
    {
        return view('laboran.jadwal-page.pengajuan.index', [
            'page_meta' => [
                'page' => 'Pengajuan Jadwal'
            ]
        ]);
    }

    // Datatables Pengajuan
    public function getDataBooking(Request $request)
    {
        // Ambil data dari tabel `bookings` dengan relasi ke `booking_details`
        $query = Booking::with(['bookingDetail.laboratorium']);

        // Pencarian (jika ada)
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];

            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                    ->orWhereHas('bookingDetail', function ($q) use ($search) {
                        $q->where('status', 'like', "%{$search}%");
                    })
                    ->orWhereHas('bookingDetail.laboratorium', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Total data sebelum paginasi
        $totalData = $query->count();

        // Paginate manual untuk server-side
        $start = $request->start;
        $length = $request->length ?? 10;
        $data = $query->skip($start)->take($length)->get();

        // Format data untuk DataTables
        $result = [];
        foreach ($data as $index => $booking) {
            // Gabungkan semua lab dalam satu string
            $labs = $booking->bookingDetail->pluck('laboratorium.name')->unique()->implode(', ');

            $status = ucfirst($booking->status); // Ambil status dari tabel `bookings`

            $result[] = [
                'index' => $start + $index + 1,
                'kode_pengajuan' => $booking->kode_pengajuan,
                'lab' => $labs,
                'status' => ucfirst($status),
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData, // Bisa diubah jika pakai filter
            'data' => $result
        ]);
    }


    public function terimaPengajuan(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_pengajuan' => 'required|string|exists:bookings,kode_pengajuan',
        ]);

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Ambil data booking
            $booking = Booking::where('kode_pengajuan', $request->kode_pengajuan)->first();

            // Pastikan booking ditemukan
            if (!$booking) {
                return redirect()->back()->with('error', 'Pengajuan tidak ditemukan.');
            }

            // Ubah status booking menjadi "diterima"
            $booking->status = 'diterima';
            $booking->save();

            // Ubah status booking details yang terkait
            $bookingDetails = $booking->bookingDetail;
            foreach ($bookingDetails as $detail) {
                $detail->status = 'diterima';
                $detail->save();

                // Log status perubahan booking detail
                BookingLog::create([
                    'booking_detail_id' => $detail->id,
                    'user_id' => auth()->id(),  // atau sesuaikan dengan user yang melakukan perubahan
                    'status' => 'diterima',
                    'catatan' => 'Pengajuan diterima.',
                ]);
            }

            // Log status perubahan booking
            BookingLog::create([
                'booking_detail_id' => $detail->id,
                'user_id' => auth()->id(),  // atau sesuaikan dengan user yang melakukan perubahan
                'status' => 'diterima',
                'catatan' => 'Pengajuan diterima.',
            ]);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return redirect()->back()->with('success', 'Pengajuan diterima.');
        } catch (\Exception $e) {
            // Jika terjadi error, rollback transaksi
            DB::rollBack();

            // Log error jika diperlukan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menerima pengajuan: ' . $e->getMessage());
        }
    }

    public function tolakPengajuan(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_pengajuan' => 'required|string|exists:bookings,kode_pengajuan',
        ]);

        // Mulai transaksi
        DB::beginTransaction();

        try {
            // Ambil data booking
            $booking = Booking::where('kode_pengajuan', $request->kode_pengajuan)->first();

            // Pastikan booking ditemukan
            if (!$booking) {
                return redirect()->back()->with('error', 'Pengajuan tidak ditemukan.');
            }

            // Ubah status booking menjadi "diterima"
            $booking->status = 'ditolak';
            $booking->save();

            // Ubah status booking details yang terkait
            $bookingDetails = $booking->bookingDetail;
            foreach ($bookingDetails as $detail) {
                $detail->status = 'ditolak';
                $detail->save();

                // Log status perubahan booking detail
                BookingLog::create([
                    'booking_detail_id' => $detail->id,
                    'user_id' => auth()->id(),  // atau sesuaikan dengan user yang melakukan perubahan
                    'status' => 'ditolak',
                    'catatan' => 'Pengajuan ditolak.',
                ]);
            }

            // Log status perubahan booking
            BookingLog::create([
                'booking_detail_id' => $detail->id,
                'user_id' => auth()->id(),  // atau sesuaikan dengan user yang melakukan perubahan
                'status' => 'ditolak',
                'catatan' => 'Pengajuan ditolak.',
            ]);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return redirect()->back()->with('success', 'Pengajuan ditolak.');
        } catch (\Exception $e) {
            // Jika terjadi error, rollback transaksi
            DB::rollBack();

            // Log error jika diperlukan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak pengajuan: ' . $e->getMessage());
        }
    }
}

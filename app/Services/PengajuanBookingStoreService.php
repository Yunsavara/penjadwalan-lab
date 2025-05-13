<?php

namespace App\Services;

use App\Models\JadwalBooking;
use App\Models\PengajuanBooking;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengajuanBookingStoreService
{
    /**
     * Membuat pengajuan booking dan jadwalnya.
     *
     * @param array $data
     * @return PengajuanBooking
     * @throws Exception
     */
    public function buatPengajuan(array $data): PengajuanBooking
    {

        DB::beginTransaction();

        try {
            // Generate kode unik
            $kodeBooking = 'PBL-' . strtoupper(Str::random(8));

            // Simpan data utama pengajuan booking
            $pengajuan = PengajuanBooking::create([
                'kode_booking' => $kodeBooking,
                'keperluan_pengajuan_booking' => $data['keperluan_pengajuan_booking'],
                'status_pengajuan_booking' => 'menunggu',
                'mode_tanggal_pengajuan' => $data['mode_tanggal'],
                'lokasi_id' => $data['lokasi_pengajuan_booking'],
                'user_id' => Auth::id(),
            ]);

            // Proses mode multi tanggal
            if ($data['mode_tanggal'] === 'multi' && !empty($data['tanggal_multi'])) {
                foreach ($data['tanggal_multi'] as $tanggal) {
                    foreach ($data['jam'][$tanggal] as $jam) {
                        [$jamMulai, $jamSelesai] = explode(' - ', $jam);
                        foreach ($data['laboratorium_pengajuan_booking'] as $labId) {
                            JadwalBooking::create([
                                'tanggal_jadwal' => $tanggal,
                                'jam_mulai' => $jamMulai,
                                'jam_selesai' => $jamSelesai,
                                'pengajuan_booking_id' => $pengajuan->id,
                                'laboratorium_unpam_id' => $labId,
                            ]);
                        }
                    }
                }
            }

            // Proses mode rentang tanggal
            if ($data['mode_tanggal'] === 'range' && !empty($data['tanggal_range'])) {
                $start = Carbon::parse($data['tanggal_range']['start']);
                $end = Carbon::parse($data['tanggal_range']['end']);

                while ($start->lte($end)) {
                    if (in_array($start->dayOfWeek, $data['hari_operasional'])) {
                        $tanggalString = $start->toDateString();
                        if (isset($data['jam'][$tanggalString])) {
                            foreach ($data['jam'][$tanggalString] as $jam) {
                                [$jamMulai, $jamSelesai] = explode(' - ', $jam);
                                foreach ($data['laboratorium_pengajuan_booking'] as $labId) {
                                    JadwalBooking::create([
                                        'tanggal_jadwal' => $tanggalString,
                                        'jam_mulai' => $jamMulai,
                                        'jam_selesai' => $jamSelesai,
                                        'pengajuan_booking_id' => $pengajuan->id,
                                        'laboratorium_unpam_id' => $labId,
                                    ]);
                                }
                            }
                        }
                    }
                    $start->addDay();
                }
            }

            DB::commit();
            return $pengajuan;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cek apakah user masih memiliki pengajuan booking dengan status 'menunggu'
     *
     * @param array $data
     */
    public function cekPengajuanBookingMenunggu(array $data): array
    {
        $konflik = [];

        $query = PengajuanBooking::where('user_id', Auth::id())
            ->where('status_pengajuan_booking', 'menunggu')
            ->where('lokasi_id', $data['lokasi_pengajuan_booking'])
            ->with('jadwalBookings.laboratoriumUnpam') // eager load relasi lab
            ->get();

        foreach ($query as $pengajuan) {
            foreach ($pengajuan->jadwalBookings as $jadwal) {
                if (
                    in_array($jadwal->laboratorium_unpam_id, (array) $data['laboratorium_pengajuan_booking']) &&
                    (
                        ($data['mode_tanggal'] === 'multi' && in_array($jadwal->tanggal_jadwal, $data['tanggal_multi'])) ||
                        ($data['mode_tanggal'] === 'range' && $jadwal->tanggal_jadwal >= $data['tanggal_range']['start'] && $jadwal->tanggal_jadwal <= $data['tanggal_range']['end'])
                    )
                ) {
                    foreach ($data['jam'][$jadwal->tanggal_jadwal] ?? [] as $jam) {
                        [$mulaiBaru, $selesaiBaru] = explode(' - ', $jam);

                        // Deteksi tabrakan
                        if ($jadwal->jam_mulai < $selesaiBaru && $jadwal->jam_selesai > $mulaiBaru) {
                            $tanggalFormatted = Carbon::parse($jadwal->tanggal_jadwal)->locale('id')->translatedFormat('d F Y'); // Contoh: 23 Juni 2025
                            $jamFormatted = Carbon::parse($jadwal->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jadwal->jam_selesai)->format('H:i');
                            $namaLab = $jadwal->laboratoriumUnpam->nama_laboratorium ?? 'Lab Tidak Diketahui';

                            $konflik[] = "Tanggal {$tanggalFormatted}, Jam {$jamFormatted}, Lab {$namaLab}";
                        }
                    }
                }
            }
        }

        return $konflik;
    }
}

<?php

namespace App\Livewire\Laboran\ProsesPengajuanBooking;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\PengajuanBooking;
use App\Models\JadwalBooking;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class TerimaProsesPengajuanBooking extends Component
{
    public bool $showModal = false;
    public $pengajuan = null;

    #[On('terimaProsesPengajuanBookingModal')]
    public function terimaProsesPengajuanBookingModal($rowId)
    {
        $this->showModal = true;
        $this->pengajuan = PengajuanBooking::with(['user.role', 'jadwalBookings'])->find($rowId);
    }

    public function terimaPengajuan()
    {
        if (!$this->pengajuan) {
            session()->flash('error', 'Pengajuan tidak ditemukan.');
            return;
        }

        $userPrioritas = $this->pengajuan->user->role->prioritas_peran;

        foreach ($this->pengajuan->jadwalBookings as $jadwalBaru) {
            // Cari semua jadwal booking lain yang bentrok (bukan milik pengajuan ini)
            $jadwalBentrokLain = JadwalBooking::where('laboratorium_unpam_id', $jadwalBaru->laboratorium_unpam_id)
                ->where('tanggal_jadwal', $jadwalBaru->tanggal_jadwal)
                ->where(function($q) use ($jadwalBaru) {
                    $q->where('jam_mulai', '<', $jadwalBaru->jam_selesai)
                      ->where('jam_selesai', '>', $jadwalBaru->jam_mulai);
                })
                ->where('status', 'diterima')
                ->where('pengajuan_booking_id', '!=', $this->pengajuan->id)
                ->get();

            foreach ($jadwalBentrokLain as $jadwalLama) {
                $pengajuanLama = PengajuanBooking::with('user.role')->find($jadwalLama->pengajuan_booking_id);
                $prioritasLama = $pengajuanLama->user->role->prioritas_peran ?? 999;

                // Jika prioritas pengajuan baru >= prioritas lama (angka lebih besar = prioritas lebih rendah)
                if ($userPrioritas >= $prioritasLama) {
                    session()->flash('error', 'Tidak dapat menerima pengajuan karena sudah ada booking dengan prioritas sama/lebih tinggi.');
                    $this->showModal = false;
                    return;
                }
            }
        }

        // Jika lolos, terima pengajuan baru
        $this->pengajuan->status_pengajuan_booking = 'diterima';
        $this->pengajuan->save();

        foreach ($this->pengajuan->jadwalBookings as $jadwalBaru) {
            $jadwalBaru->status = 'diterima';
            $jadwalBaru->save();

            // Batalkan semua jadwal booking lain yang bentrok (prioritas lebih rendah)
            $jadwalBentrokLain = JadwalBooking::where('laboratorium_unpam_id', $jadwalBaru->laboratorium_unpam_id)
                ->where('tanggal_jadwal', $jadwalBaru->tanggal_jadwal)
                ->where(function($q) use ($jadwalBaru) {
                    $q->where('jam_mulai', '<', $jadwalBaru->jam_selesai)
                      ->where('jam_selesai', '>', $jadwalBaru->jam_mulai);
                })
                ->where('status', 'diterima')
                ->where('pengajuan_booking_id', '!=', $this->pengajuan->id)
                ->get();

            foreach ($jadwalBentrokLain as $jadwalLama) {
                $pengajuanLama = PengajuanBooking::with('user.role', 'jadwalBookings')->find($jadwalLama->pengajuan_booking_id);
                $prioritasLama = $pengajuanLama->user->role->prioritas_peran ?? 999;

                // Jika prioritas lama lebih rendah (angka lebih besar)
                if ($userPrioritas < $prioritasLama) {
                    $jadwalLama->status = 'dibatalkan';
                    $jadwalLama->save();

                    // Jika semua jadwal booking pada pengajuan lama sudah dibatalkan, update status pengajuan juga
                    $semuaDibatalkan = $pengajuanLama->jadwalBookings->every(function($j) {
                        return $j->status === 'dibatalkan';
                    });
                    if ($semuaDibatalkan) {
                        $pengajuanLama->status_pengajuan_booking = 'dibatalkan';
                        $pengajuanLama->save();
                    }
                }
            }
        }

        session()->flash('success', 'Pengajuan booking berhasil diterima dan jadwal bentrok prioritas lebih rendah dibatalkan.');
        $this->showModal = false;
        $this->dispatch('pengajuanDiterima');
    }

    public function render()
    {
        return view('livewire.laboran.proses-pengajuan-booking.terima-proses-pengajuan-booking');
    }
}

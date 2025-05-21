<?php

namespace App\Livewire\Laboran\ProsesPengajuanBooking;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\PengajuanBooking;
use App\Models\JadwalBooking;

class TerimaProsesPengajuanBooking extends Component
{
    public bool $showModal = false;
    public $pengajuan = null;

    #[On('terimaProsesPengajuanBookingModal')]
    public function terimaProsesPengajuanBookingModal($rowId): void
    {
        $this->showModal = true;
        $this->pengajuan = PengajuanBooking::with(['user.role', 'jadwalBookings'])->find($rowId);
    }

    public function terimaPengajuan(): void
    {
        if (!$this->pengajuan) {
            session()->flash('error', 'Pengajuan tidak ditemukan.');
            return;
        }

        $userPrioritas = $this->pengajuan->user->role->prioritas_peran;

        if ($this->adaBentrokPrioritasSamaAtauLebihTinggi($userPrioritas)) {
            session()->flash('error', 'Tidak dapat menerima pengajuan karena sudah ada booking dengan prioritas sama/lebih tinggi.');
            $this->showModal = false;
            return;
        }

        $this->batalkanJadwalBentrokPrioritasLebihRendah($userPrioritas);
        $this->setujuiPengajuan();
        $this->tolakPengajuanMenungguBentrok();

        session()->flash('success', 'Pengajuan booking berhasil diterima dan jadwal bentrok prioritas lebih rendah dibatalkan.');
        $this->showModal = false;
        $this->dispatch('pengajuanDiterima');
    }

    /**
     * Mengecek apakah ada jadwal bentrok dengan prioritas sama atau lebih tinggi.
     */
    private function adaBentrokPrioritasSamaAtauLebihTinggi(int $userPrioritas): bool
    {
        foreach ($this->pengajuan->jadwalBookings as $jadwalBaru) {
            $jadwalBentrok = JadwalBooking::where('laboratorium_unpam_id', $jadwalBaru->laboratorium_unpam_id)
                ->where('tanggal_jadwal', $jadwalBaru->tanggal_jadwal)
                ->where(function($q) use ($jadwalBaru) {
                    $q->where('jam_mulai', '<', $jadwalBaru->jam_selesai)
                      ->where('jam_selesai', '>', $jadwalBaru->jam_mulai);
                })
                ->where('status', 'diterima')
                ->where('pengajuan_booking_id', '!=', $this->pengajuan->id)
                ->get();

            foreach ($jadwalBentrok as $jadwalLama) {
                $pengajuanLama = PengajuanBooking::with('user.role')->find($jadwalLama->pengajuan_booking_id);
                $prioritasLama = $pengajuanLama->user->role->prioritas_peran ?? 999;

                if ($userPrioritas >= $prioritasLama) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Membatalkan jadwal bentrok yang sudah diterima dengan prioritas lebih rendah.
     */
    private function batalkanJadwalBentrokPrioritasLebihRendah(int $userPrioritas): void
    {
        $jadwalBaruList = $this->pengajuan->jadwalBookings;

        // Gabungan unik lab & tanggal
        $kombinasi = $jadwalBaruList->map(fn($jadwal) => [
            'laboratorium_unpam_id' => $jadwal->laboratorium_unpam_id,
            'tanggal_jadwal' => $jadwal->tanggal_jadwal,
        ])->unique();

        foreach ($kombinasi as $item) {
            $jadwalBentrokDiterima = JadwalBooking::where('laboratorium_unpam_id', $item['laboratorium_unpam_id'])
                ->where('tanggal_jadwal', $item['tanggal_jadwal'])
                ->where('status', 'diterima')
                ->where('pengajuan_booking_id', '!=', $this->pengajuan->id)
                ->get();

            foreach ($jadwalBentrokDiterima as $jadwalLama) {
                foreach ($jadwalBaruList as $jadwalBaru) {
                    if (
                        $jadwalBaru->laboratorium_unpam_id == $item['laboratorium_unpam_id'] &&
                        $jadwalBaru->tanggal_jadwal == $item['tanggal_jadwal'] &&
                        $jadwalBaru->jam_mulai < $jadwalLama->jam_selesai &&
                        $jadwalBaru->jam_selesai > $jadwalLama->jam_mulai
                    ) {
                        $pengajuanLama = PengajuanBooking::with('user.role')->find($jadwalLama->pengajuan_booking_id);
                        $prioritasLama = $pengajuanLama->user->role->prioritas_peran ?? 999;

                        if ($userPrioritas < $prioritasLama) {
                            $jadwalLama->status = 'dibatalkan';
                            $jadwalLama->save();

                            // Jika semua jadwal pengajuan lama sudah dibatalkan, update status pengajuan
                            $jadwalLain = JadwalBooking::where('pengajuan_booking_id', $pengajuanLama->id)->get();
                            if ($jadwalLain->every(fn($j) => $j->status === 'dibatalkan')) {
                                $pengajuanLama->status_pengajuan_booking = 'dibatalkan';
                                $pengajuanLama->save();
                            }
                        }
                        break; // cukup sekali per jadwalLama
                    }
                }
            }
        }
    }

    /**
     * Menyetujui pengajuan dan seluruh jadwalnya.
     */
    private function setujuiPengajuan(): void
    {
        $this->pengajuan->status_pengajuan_booking = 'diterima';
        $this->pengajuan->save();

        foreach ($this->pengajuan->jadwalBookings as $jadwalBaru) {
            $jadwalBaru->status = 'diterima';
            $jadwalBaru->save();
        }
    }

    /**
     * Menolak semua pengajuan menunggu yang bentrok dengan pengajuan ini.
     */
    private function tolakPengajuanMenungguBentrok(): void
    {
        $jadwalBaruList = $this->pengajuan->jadwalBookings;
        $pengajuanIdsUntukCek = collect();

        foreach ($jadwalBaruList as $jadwalBaru) {
            $jadwalBentrokMenunggu = JadwalBooking::where('laboratorium_unpam_id', $jadwalBaru->laboratorium_unpam_id)
                ->where('tanggal_jadwal', $jadwalBaru->tanggal_jadwal)
                ->where(function($q) use ($jadwalBaru) {
                    $q->where('jam_mulai', '<', $jadwalBaru->jam_selesai)
                      ->where('jam_selesai', '>', $jadwalBaru->jam_mulai);
                })
                ->where('status', 'menunggu')
                ->where('pengajuan_booking_id', '!=', $this->pengajuan->id)
                ->get();

            foreach ($jadwalBentrokMenunggu as $jadwalBentrok) {
                $pengajuanIdsUntukCek->push($jadwalBentrok->pengajuan_booking_id);
            }
        }

        $pengajuanIdsUntukCek = $pengajuanIdsUntukCek->unique();

        foreach ($pengajuanIdsUntukCek as $pengajuanId) {
            $jadwalLain = JadwalBooking::where('pengajuan_booking_id', $pengajuanId)->get();

            // Jika semua status jadwal "menunggu", tolak semua jadwal & pengajuan
            if ($jadwalLain->count() > 0 && $jadwalLain->every(fn($j) => $j->status === 'menunggu')) {
                JadwalBooking::where('pengajuan_booking_id', $pengajuanId)
                    ->update(['status' => 'ditolak']);

                $pengajuan = PengajuanBooking::find($pengajuanId);
                if ($pengajuan && $pengajuan->status_pengajuan_booking !== 'ditolak') {
                    $pengajuan->status_pengajuan_booking = 'ditolak';
                    $pengajuan->save();
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.laboran.proses-pengajuan-booking.terima-proses-pengajuan-booking');
    }
}

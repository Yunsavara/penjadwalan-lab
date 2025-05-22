<?php

namespace App\Livewire\Laboran\ProsesPengajuanBooking;

use App\Models\PengajuanBooking;
use Livewire\Attributes\On;
use Livewire\Component;

class TolakProsesPengajuanBooking extends Component
{
    public bool $showModal = false;
    public $pengajuan = null;
    public $balasanTolak = '';

    #[On('bukaModalTolakPengajuan')]
    public function bukaModalTolakPengajuan($rowId)
    {
        $this->showModal = true;
        $this->pengajuan = PengajuanBooking::with(['user', 'jadwalBookings'])->find($rowId);
        $this->balasanTolak = '';
    }

    public function validateProsesPengajuan()
    {
        return $this->validate([
            'balasanTolak' => 'required|string|max:255',
        ], [
            'balasanTolak.required' => 'Balasan penolakan wajib diisi.',
        ]);
    }

    public function refreshTable()
    {
        $this->dispatch('pg:eventRefresh-pengajuan_bookings');
    }

    public function prosesTolakPengajuan()
    {
        $validated = $this->validateProsesPengajuan();

        if ($this->pengajuan) {
            // Update status pengajuan
            $this->pengajuan->status_pengajuan_booking = 'ditolak';
            $this->pengajuan->balasan_pengajuan_booking = $validated['balasanTolak'];
            $this->pengajuan->save();

            // Update semua jadwal_booking terkait
            $this->pengajuan->jadwalBookings()->update([
                'status' => 'ditolak'
            ]);
        }

        $this->showModal = false;
        $this->dispatch($this->refreshTable());
        session()->flash('success', 'Pengajuan booking berhasil ditolak.');
    }

    public function render()
    {
        return view('livewire.laboran.proses-pengajuan-booking.tolak-proses-pengajuan-booking');
    }
}

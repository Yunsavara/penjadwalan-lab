<?php

namespace App\Livewire\Pengguna\Booking;

use App\Models\PengajuanBooking;
use Livewire\Attributes\On;
use Livewire\Component;

class BatalkanPengajuanBooking extends Component
{
    public bool $showModal = false;
    public $pengajuan = null;
    public $alasanBatal = '';

    #[On('openModalBatalkan')]
    public function openModalBatalkan($rowId)
    {
        $this->showModal = true;
        $this->pengajuan = PengajuanBooking::with('jadwalBookings')->find($rowId);
        $this->alasanBatal = '';
    }

    public function validateBatalkan()
    {
        return $this->validate([
            'alasanBatal' => 'required|string|max:255',
        ], [
            'alasanBatal.required' => 'Alasan pembatalan wajib diisi.',
        ]);
    }

    public function prosesBatalkan()
    {
        $validated = $this->validateBatalkan();

        if ($this->pengajuan) {
            $this->pengajuan->status_pengajuan_booking = 'dibatalkan';
            $this->pengajuan->balasan_pengajuan_booking = $validated['alasanBatal'];
            $this->pengajuan->save();

            // Update semua jadwal_booking terkait
            $this->pengajuan->jadwalBookings()->update([
                'status' => 'dibatalkan'
            ]);
        }

        $this->showModal = false;
        $this->dispatch('pg:eventRefresh-pengajuan_bookings');
        session()->flash('success', 'Pengajuan booking berhasil dibatalkan.');
    }

    public function render()
    {
        return view('livewire.pengguna.booking.batalkan-pengajuan-booking');
    }
}

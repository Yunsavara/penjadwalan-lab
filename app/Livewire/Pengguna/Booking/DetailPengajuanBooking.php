<?php

namespace App\Livewire\Pengguna\Booking;

use App\Models\PengajuanBooking;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailPengajuanBooking extends Component
{

    public bool $showModal = false;
    public $pengajuan = null;

    #[On('detailPengajuanBookingModal')]
    public function detailPengajuanBookingModal($rowId)
    {
        $this->showModal = true;

        $this->pengajuan = PengajuanBooking::with(['lokasi', 'user', 'jadwalBookings'])->find($rowId);
        
    }

    public function render()
    {
        return view('livewire.pengguna.booking.detail-pengajuan-booking');
    }
}

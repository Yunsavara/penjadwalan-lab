<?php

namespace App\Livewire\Laboran\ProsesPengajuanBooking;

use App\Models\PengajuanBooking;
use Livewire\Attributes\On;
use Livewire\Component;

class DetailProsesPengajuanBooking extends Component
{
    public bool $showModal = false;
    public $pengajuan = null;

    #[On('detailProsesPengajuanBookingModal')]
    public function detailProsesPengajuanBookingModal($rowId)
    {
        $this->showModal = true;

        $this->pengajuan = PengajuanBooking::with(['lokasi', 'user', 'jadwalBookings'])->find($rowId);
        
    }

    public function render()
    {
        return view('livewire.laboran.proses-pengajuan-booking.detail-proses-pengajuan-booking');
    }
}

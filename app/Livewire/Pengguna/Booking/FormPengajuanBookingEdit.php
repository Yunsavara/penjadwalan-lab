<?php

namespace App\Livewire\Pengguna\Booking;

use App\Models\HariOperasional;
use App\Models\LaboratoriumUnpam;
use App\Models\Lokasi;
use App\Models\PengajuanBooking;
use Livewire\Attributes\On;
use Livewire\Component;

class FormPengajuanBookingEdit extends Component
{
    public $lokasiId;
    public $laboratoriumIds = [];
    public $laboratoriumList = [];
    public $modeTanggal = "multi";
    public $tanggalMulti = [];
    public $jamOperasionalPerTanggal = [];
    public $jamTerpilih = [];

    public bool $showModal = false;

    #[On('openModalEdit')]
    public function openModalEdit($rowId): void
    {
        $this->showModal = true;
        
        $pengajuan = PengajuanBooking::findOrFail($rowId);
        
        $this->lokasiId = $pengajuan->lokasi_id;
        $this->laboratoriumList = LaboratoriumUnpam::where('lokasi_id', $this->lokasiId)->get();
        $this->laboratoriumIds = $pengajuan->laboratorium->pluck('id')->toArray();
        $this->modeTanggal = $pengajuan->mode_tanggal_pengajuan;
        
        if ($this->modeTanggal === "multi") {
            $tanggalMulti = $pengajuan->jadwalBookings
                ->pluck('tanggal_jadwal')
                ->unique()
                ->values()
                ->toArray();

            $this->tanggalMulti = $tanggalMulti;

            $jamPerTanggal = [];
            $jamTerpilih = [];

            foreach ($tanggalMulti as $tanggal) {
                // Ambil hanya jadwal bookings yang sesuai tanggal
                $jamBookings = $pengajuan->jadwalBookings->filter(function ($item) use ($tanggal) {
                    return $item->tanggal_jadwal === $tanggal;
                });

                $jamOptions = [];

                foreach ($jamBookings as $jb) {
                    $jamRange = $jb->jam_mulai . ' - ' . $jb->jam_selesai;

                    // Hindari duplikat jam
                    if (!in_array($jamRange, $jamOptions)) {
                        $jamOptions[] = $jamRange;
                    }
                }

                $jamPerTanggal[$tanggal] = $jamOptions;
                $jamTerpilih[$tanggal] = $jamOptions; 
            }

            $this->jamOperasionalPerTanggal = $jamPerTanggal;
            $this->jamTerpilih = $jamTerpilih;
        }
    }

    #[On('closeModalEdit')]
    public function closeModalEdit()
    {
        $this->showModal = false;
    }

    public function getHariAktifProperty()
    {
        return HariOperasional::where('lokasi_id', $this->lokasiId)
            ->where('is_disabled', false)
            ->pluck('hari_operasional')
            ->toArray(); 
    }
    
    public function render()
    {
        $lokasis = Lokasi::select(['id','nama_lokasi'])->whereNot('nama_lokasi','fleksible')->get();

        return view('livewire.pengguna.booking.form-pengajuan-booking-edit', [
            'lokasis' => $lokasis
        ]);
    }
}

 // public function updatedLokasiId($value)
    // {
    //     if($value)
    //     {
    //         $this->laboratoriumList = LaboratoriumUnpam::where('lokasi_id',$value)->get();
    //         // $this->hariOperasionalList = HariOperasional::where('lokasi_id', $value)
    //         //     ->where('is_disabled', false)
    //         //     ->orderBy('hari_operasional')
    //         //     ->get();
    //     } else {
    //         $this->laboratoriumList = [];
    //         // $this->hariOperasionalList = [];
    //     }

    //     $this->laboratoriumIds = [];
    //     // $this->tanggalMulti = [];
    //     // $this->jamOperasionalPerTanggal = [];
    //     // $this->jamTerpilih = [];
    //     // $this->dispatch('resetLaboratoriumSelect');
    //     // $this->dispatch('resetTanggalMultiFlatpickr');
    //     // $this->dispatch('initFlatpickrWithHariAktif', ['hariAktif' => $this->hariAktif]);
    //     // $this->dispatch('resetTanggalRangeFlatpickr');
    // }
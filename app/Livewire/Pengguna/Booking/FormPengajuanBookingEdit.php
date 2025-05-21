<?php

namespace App\Livewire\Pengguna\Booking;

use App\Models\HariOperasional;
use App\Models\LaboratoriumUnpam;
use App\Models\Lokasi;
use App\Models\PengajuanBooking;
use Carbon\Carbon;
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

            $this->setJamOperasionalFromTanggalMulti($tanggalMulti);

            $jamTerpilih = [];
            foreach ($tanggalMulti as $tanggal) {
                $jamTerpilih[$tanggal] = $pengajuan->jadwalBookings
                    ->where('tanggal_jadwal', $tanggal)
                    ->map(function ($jb) {
                        return Carbon::parse($jb->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jb->jam_selesai)->format('H:i');
                    })
                    ->unique()
                    ->values()
                    ->toArray();
            }

            $this->jamTerpilih = $jamTerpilih;

            // dump($this->jamTerpilih);
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

    private function setJamOperasionalFromTanggalMulti(array $tanggalList): void
    {
        $this->jamOperasionalPerTanggal = [];

        foreach ($tanggalList as $tgl) {
            try {
                $carbon = Carbon::parse($tgl);
                $hariKe = $carbon->dayOfWeek;
                $tanggalStr = $carbon->format('Y-m-d');

                $data = HariOperasional::with('jamOperasionals')
                    ->where('lokasi_id', $this->lokasiId)
                    ->where('hari_operasional', $hariKe)
                    ->where('is_disabled', false)
                    ->first();

                if ($data) {
                    $this->jamOperasionalPerTanggal[$tanggalStr] = $data->jamOperasionals
                        ->map(function ($jam) {
                            return Carbon::parse($jam->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jam->jam_selesai)->format('H:i');
                        })->toArray();
                }
            } catch (\Exception $e) {
                // Bisa log error kalau perlu
            }
        }
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
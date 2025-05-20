<?php

namespace App\Livewire\Pengguna\Booking;

use App\Models\HariOperasional;
use App\Models\LaboratoriumUnpam;
use App\Models\Lokasi;
use Carbon\Carbon;
use Livewire\Component;

class FormBookingStore extends Component
{
    public $lokasiId;
    public $laboratoriumIds = [];
    public $laboratoriumList = [];
    public $modeTanggal = 'multi';

    public $tanggalMulti = [];
    public $jamOperasionalPerTanggal = [];
    public $jamTerpilih = [];
    public $hariOperasionalList = [];

    public $tanggalRange = ''; 
    public $hariTerpilih = []; 
    public $tanggalFiltered = []; 

    public $keperluanBooking;

    public function updatedLokasiId($value)
    {
        if ($value) {
            $this->laboratoriumList = LaboratoriumUnpam::where('lokasi_id', $value)->get();

            $this->hariOperasionalList = HariOperasional::where('lokasi_id', $value)
                ->where('is_disabled', false)
                ->orderBy('hari_operasional')
                ->get();
        } else {
            $this->laboratoriumList = [];
            $this->hariOperasionalList = [];
        }

        $this->laboratoriumIds = [];
        $this->modeTanggal = 'multi';
        $this->tanggalMulti = [];
        $this->jamOperasionalPerTanggal = [];
        $this->jamTerpilih = [];

        $this->dispatch('resetLaboratoriumSelect');
        $this->dispatch('resetTanggalMultiFlatpickr');
        $this->dispatch('resetTanggalRangeFlatpickr');
    }

    public function updatedModeTanggal()
    {
        $this->tanggalMulti = [];
        $this->jamOperasionalPerTanggal = [];
        $this->jamTerpilih = [];
        $this->hariTerpilih = [];
    }

    public function updatedTanggalMulti($value)
    {
        $this->jamOperasionalPerTanggal = [];

        if (is_string($value)) {
            $value = explode(',', $value);
        }

        // Bersihkan jamTerpilih dari tanggal yang tidak ada di tanggalMulti
        $this->jamTerpilih = array_filter(
            $this->jamTerpilih,
            fn ($tanggal) => in_array($tanggal, $this->tanggalMulti),
            ARRAY_FILTER_USE_KEY
        );

        foreach ($value as $tgl) {
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
            }
        }
    }

    public function updatedTanggalRange($value)
    {
        $this->filterTanggalByHari();
    }

    public function updatedHariTerpilih($value)
    {
        $this->filterTanggalByHari();
    }

    protected function filterTanggalByHari()
    {
        $this->tanggalFiltered = [];
        $this->jamOperasionalPerTanggal = [];

        if (!$this->tanggalRange || empty($this->hariTerpilih)) {
            $this->jamTerpilih = []; // reset juga kalau kondisi tidak valid
            return;
        }

        $parts = explode(' - ', $this->tanggalRange);

        if (count($parts) !== 2) {
            $this->jamTerpilih = [];
            return;
        }

        try {
            $start = Carbon::parse(trim($parts[0]));
            $end = Carbon::parse(trim($parts[1]));
        } catch (\Exception $e) {
            $this->jamTerpilih = [];
            return;
        }

        $current = $start->copy();

        while ($current->lte($end)) {
            $hari = $current->dayOfWeek;
            if (in_array($hari, array_map('intval', $this->hariTerpilih))) {
                $tanggalStr = $current->format('Y-m-d');
                $this->tanggalFiltered[] = $tanggalStr;

                $data = HariOperasional::with('jamOperasionals')
                    ->where('lokasi_id', $this->lokasiId)
                    ->where('hari_operasional', $hari)
                    ->where('is_disabled', false)
                    ->first();

                if ($data) {
                    $this->jamOperasionalPerTanggal[$tanggalStr] = $data->jamOperasionals
                        ->map(fn($jam) => Carbon::parse($jam->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jam->jam_selesai)->format('H:i'))
                        ->toArray();
                }
            }

            $current->addDay();
        }

        // Sinkronkan jamTerpilih dengan tanggalFiltered
        $this->jamTerpilih = array_filter(
            $this->jamTerpilih,
            fn ($tanggal) => in_array($tanggal, $this->tanggalFiltered),
            ARRAY_FILTER_USE_KEY
        );
    }

    public function render()
    {
        $lokasis = Lokasi::select(['id', 'nama_lokasi'])
            ->whereNot('nama_lokasi', 'fleksible')
            ->get();

        return view('livewire.pengguna.booking.form-booking-store', [
            'lokasis' => $lokasis,
        ]);
    }

    
    public function storeBooking()
    {
        $data = $this->validateBooking();
        dd($data);
    }

    public function validateBooking()
    {
        $rules = [
            'lokasiId' => 'required|exists:lokasis,id',
            'laboratoriumIds' => 'required|array|min:1',
            'laboratoriumIds.*' => 'exists:laboratorium_unpams,id',
            'modeTanggal' => 'required|in:multi,range',
        ];

        if ($this->modeTanggal === 'multi') {
            $rules = array_merge($rules, [
                'tanggalMulti' => 'required|array|min:1',
                'tanggalMulti.*' => 'date',
                'jamTerpilih' => 'required|array|min:1',
                'jamTerpilih.*' => 'array|min:1',
                'jamTerpilih.*.*' => 'string',
            ]);
        } elseif ($this->modeTanggal === 'range') {
            $rules = array_merge($rules, [
                'tanggalRange' => 'required|string',
                'hariTerpilih' => 'required|array|min:1',
                'hariTerpilih.*' => 'in:0,1,2,3,4,5,6',
                'jamTerpilih' => 'required|array|min:1',
                'jamTerpilih.*' => 'array|min:1',
                'jamTerpilih.*.*' => 'string',
            ]);
        }

        return $this->validate($rules);
    }

}

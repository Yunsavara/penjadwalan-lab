<?php

namespace App\Livewire\Pengguna\Booking;

use App\Models\HariOperasional;
use App\Models\JadwalBooking;
use App\Models\LaboratoriumUnpam;
use App\Models\Lokasi;
use App\Models\PengajuanBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class FormPengajuanBookingCreate extends Component
{
    public $lokasiId;
    public $laboratoriumIds = [];
    public $laboratoriumList = [];
    public $modeTanggal = "multi";
    public $tanggalMulti = [];
    public $jamOperasionalPerTanggal = [];
    public $jamTerpilih = [];
    public $hariOperasionalList = [];
    public $tanggalRange = ''; 
    public $hariTerpilih = []; 
    public $tanggalFiltered = []; 
    public $keperluanBooking;
    public bool $showModal = false;

    protected function resetForm()
    {
        $this->reset([
            'lokasiId','laboratoriumIds','laboratoriumList','tanggalMulti',
            'jamOperasionalPerTanggal','jamTerpilih','tanggalRange','hariTerpilih',
            'tanggalFiltered','keperluanBooking'
        ]);
        $this->modeTanggal = 'multi';
    }

    protected function getJamOperasionalForTanggal($tanggal)
    {
        try {
            $carbon = Carbon::parse($tanggal);
            $hariKe = $carbon->dayOfWeek;
            $tanggalStr = $carbon->format('Y-m-d');

            $data = HariOperasional::with('jamOperasionals')
                ->where('lokasi_id', $this->lokasiId)
                ->where('hari_operasional', $hariKe)
                ->where('is_disabled', false)
                ->first();

            if ($data) {
                return $data->jamOperasionals
                    ->map(fn($jam) => Carbon::parse($jam->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jam->jam_selesai)->format('H:i'))
                    ->toArray();
            }
        } catch (\Exception $e) {}
        return [];
    }

    protected function setJamOperasionalFromTanggalMulti(array $tanggalList): void
    {
        $this->jamOperasionalPerTanggal = [];
        foreach ($tanggalList as $tgl) {
            $this->jamOperasionalPerTanggal[$tgl] = $this->getJamOperasionalForTanggal($tgl);
        }
    }

    protected function loadHariOperasionalByLokasi($lokasiId)
    {
        if ($lokasiId) {
            return HariOperasional::where('lokasi_id', $lokasiId)
                ->where('is_disabled', false)
                ->orderBy('hari_operasional')
                ->get();
        }
        return collect();
    }

    #[On('openModalCreate')]
    public function openModalCreate()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = true;
    }

    #[On('closeModalCreate')]
    public function closeModalCreate()
    {
        $this->showModal = false;
    }

    public function updatedLokasiId($value)
    {
        $this->onLokasiChanged($value);
    }

    public function updatedModeTanggal()
    {
        $this->onModeTanggalChanged();
    }

    public function updatedTanggalMulti($value)
    {
        $this->onTanggalMultiChanged($value);
    }

    public function updatedTanggalRange($value)
    {
        $this->onTanggalRangeChanged();
    }

    public function updatedHariTerpilih($value)
    {
        $this->onHariTerpilihChanged();
    }

    protected function onLokasiChanged($value)
    {
        if ($value) {
            $this->laboratoriumList = LaboratoriumUnpam::where('lokasi_id', $value)->get();
            $this->hariOperasionalList = $this->loadHariOperasionalByLokasi($value);
        } else {
            $this->laboratoriumList = [];
            $this->hariOperasionalList = collect();
        }

        $this->laboratoriumIds = [];
        $this->tanggalMulti = [];
        $this->jamOperasionalPerTanggal = [];
        $this->jamTerpilih = [];
        $this->dispatch('resetLaboratoriumSelect');
        $this->dispatch('resetTanggalMultiFlatpickr');
        $this->dispatch('initFlatpickrWithHariAktif', ['hariAktif' => $this->hariAktif]);
        $this->dispatch('resetTanggalRangeFlatpickr');
    }

    protected function onModeTanggalChanged()
    {
        $this->tanggalMulti = [];
        $this->jamOperasionalPerTanggal = [];
        $this->jamTerpilih = [];
        $this->hariTerpilih = [];
    }

    protected function onTanggalMultiChanged($value)
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
        $this->setJamOperasionalFromTanggalMulti($value);
    }

    protected function onTanggalRangeChanged()
    {
        $this->filterTanggalByHari();
    }

    protected function onHariTerpilihChanged()
    {
        $this->filterTanggalByHari();
    }

    protected function filterTanggalByHari()
    {
        $this->tanggalFiltered = [];
        $this->jamOperasionalPerTanggal = [];

        if (!$this->tanggalRange || empty($this->hariTerpilih)) {
            $this->jamTerpilih = [];
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
                $this->jamOperasionalPerTanggal[$tanggalStr] = $this->getJamOperasionalForTanggal($tanggalStr);
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

    public function validatePengajuanBooking()
    {
        $rules = [
            'lokasiId' => 'required|exists:lokasis,id',
            'laboratoriumIds' => 'required|array|min:1',
            'laboratoriumIds.*' => 'exists:laboratorium_unpams,id',
            'modeTanggal' => 'required|in:multi,range',
            'keperluanBooking' => 'required|string|max:255'
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

    public function simpanPengajuanBooking()
    {
        $data = $this->validatePengajuanBooking();
        // dd($data);

        DB::beginTransaction();

        try
        {

            $kodeBooking = 'Book-' . strtoupper(Str::random(8));

            $pengajuan = PengajuanBooking::create([
                'kode_booking' => $kodeBooking,
                'status_pengajuan_booking' => 'menunggu',
                'keperluan_pengajuan_booking' => $this->keperluanBooking,
                'mode_tanggal_pengajuan' => $this->modeTanggal,
                'lokasi_id' => $this->lokasiId,
                'user_id' => auth()->id(),
            ]);

            foreach ($this->laboratoriumIds as $labId) {
                if ($this->modeTanggal === 'multi') {
                    foreach ($this->tanggalMulti as $tanggal) {
                        if (!isset($this->jamTerpilih[$tanggal])) continue;

                        foreach ($this->jamTerpilih[$tanggal] as $jam) {
                            [$mulai, $selesai] = array_map('trim', explode('-', $jam));

                            JadwalBooking::create([
                                'pengajuan_booking_id' => $pengajuan->id,
                                'laboratorium_unpam_id' => $labId,
                                'tanggal_jadwal' => $tanggal,
                                'jam_mulai' => $mulai,
                                'jam_selesai' => $selesai,
                                'status' => 'menunggu',
                            ]);
                        }
                    }
                } elseif ($this->modeTanggal === 'range') {
                    foreach ($this->tanggalFiltered as $tanggal) {
                        if (!isset($this->jamTerpilih[$tanggal])) continue;

                        foreach ($this->jamTerpilih[$tanggal] as $jam) {
                            [$mulai, $selesai] = array_map('trim', explode('-', $jam));

                            JadwalBooking::create([
                                'pengajuan_booking_id' => $pengajuan->id,
                                'laboratorium_unpam_id' => $labId,
                                'tanggal_jadwal' => $tanggal,
                                'jam_mulai' => $mulai,
                                'jam_selesai' => $selesai,
                                'status' => 'menunggu',
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            // Reset form jika perlu
            $this->reset(['laboratoriumIds', 'laboratoriumList','tanggalMulti', 'tanggalRange', 'hariTerpilih', 'jamOperasionalPerTanggal', 'jamTerpilih', 'keperluanBooking']);
            $this->modeTanggal = 'multi';
            $this->dispatch('resetLokasiSelect');
            $this->dispatch('resetLaboratoriumSelect');
            $this->dispatch('resetTanggalMultiFlatpickr');
            $this->dispatch('resetTanggalRangeFlatpickr');

            session()->flash('success', 'Pengajuan booking berhasil disimpan!');
            $this->showModal = false;
        } catch(\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan saat menyimpan pengajuan: ' . $e->getMessage());
        }
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

        return view('livewire.pengguna.booking.form-pengajuan-booking-create', [
            'lokasis' => $lokasis
        ]);
    }
}

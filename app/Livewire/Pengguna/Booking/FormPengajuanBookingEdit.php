<?php

namespace App\Livewire\Pengguna\Booking;

use App\Models\HariOperasional;
use App\Models\JadwalBooking;
use App\Models\LaboratoriumUnpam;
use App\Models\Lokasi;
use App\Models\PengajuanBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class FormPengajuanBookingEdit extends Component
{
    public $pengajuanId;
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
            'pengajuanId','lokasiId','laboratoriumIds','laboratoriumList','tanggalMulti',
            'jamOperasionalPerTanggal','jamTerpilih','hariOperasionalList','tanggalRange',
            'hariTerpilih','tanggalFiltered','keperluanBooking'
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

    #[On('openModalEdit')]
    public function openModalEdit($rowId): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = true;

        $pengajuan = PengajuanBooking::findOrFail($rowId);
        $this->pengajuanId = $pengajuan->id;
        $this->lokasiId = $pengajuan->lokasi_id;
        $this->laboratoriumList = LaboratoriumUnpam::where('lokasi_id', $this->lokasiId)->get();
        $this->laboratoriumIds = array_values(array_unique($pengajuan->laboratorium->pluck('id')->toArray()));
        $this->keperluanBooking = $pengajuan->keperluan_pengajuan_booking;
        $this->hariOperasionalList = $this->loadHariOperasionalByLokasi($this->lokasiId);

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
                    ->map(fn ($jb) => Carbon::parse($jb->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jb->jam_selesai)->format('H:i'))
                    ->unique()
                    ->values()
                    ->toArray();
            }
            $this->jamTerpilih = $jamTerpilih;
        } elseif ($this->modeTanggal === "range") {
            // Ambil range dan hari terpilih dari jadwalBookings
            $tanggalArr = $pengajuan->jadwalBookings->pluck('tanggal_jadwal')->unique()->sort()->values()->toArray();
            if (count($tanggalArr) >= 2) {
                $this->tanggalRange = $tanggalArr[0] . ' - ' . $tanggalArr[count($tanggalArr)-1];
            }
            $hariArr = [];
            foreach ($tanggalArr as $tgl) {
                $hariArr[] = Carbon::parse($tgl)->dayOfWeek;
            }
            $this->hariTerpilih = array_values(array_unique($hariArr));
            $this->filterTanggalByHari();

            $jamTerpilih = [];
            foreach ($this->tanggalFiltered as $tanggal) {
                $jamTerpilih[$tanggal] = $pengajuan->jadwalBookings
                    ->where('tanggal_jadwal', $tanggal)
                    ->map(fn ($jb) => Carbon::parse($jb->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jb->jam_selesai)->format('H:i'))
                    ->unique()
                    ->values()
                    ->toArray();
            }
            $this->jamTerpilih = $jamTerpilih;
        }
    }

    #[On('closeModalEdit')]
    public function closeModalEdit()
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

    public function ubahPengajuanBooking()
    {
        $data = $this->validatePengajuanBooking();
        // dd($data);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanBooking::findOrFail($this->pengajuanId);

            // Update data utama
            $pengajuan->update([
                'lokasi_id' => $this->lokasiId,
                'keperluan_pengajuan_booking' => $this->keperluanBooking,
                'mode_tanggal_pengajuan' => $this->modeTanggal,
            ]);

            // Hapus jadwal lama
            $pengajuan->jadwalBookings()->delete();

            // Simpan jadwal baru
            if ($this->modeTanggal === 'multi') {
                foreach ($this->laboratoriumIds as $labId) {
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
                }
            } elseif ($this->modeTanggal === 'range') {
                foreach ($this->laboratoriumIds as $labId) {
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
            $this->showModal = false;
            session()->flash('success', 'Pengajuan booking berhasil diubah!');
            $this->refreshTable();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal mengubah pengajuan: ' . $e->getMessage());
        }
    }

    public function getHariAktifProperty()
    {
        return HariOperasional::where('lokasi_id', $this->lokasiId)
            ->where('is_disabled', false)
            ->pluck('hari_operasional')
            ->toArray(); 
    }

    public function refreshTable()
    {
        $this->dispatch('pg:eventRefresh-pengajuan_bookings');
    }

    public function render()
    {
        $lokasis = Lokasi::select(['id','nama_lokasi'])->whereNot('nama_lokasi','fleksible')->get();

        return view('livewire.pengguna.booking.form-pengajuan-booking-edit', [
            'lokasis' => $lokasis
        ]);
    }
}


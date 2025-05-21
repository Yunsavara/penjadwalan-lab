@php
    use \Carbon\Carbon;
@endphp

<div>
    @if ($showModal)
    <div class="modal fade show d-block" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" style="background-color: rgba(0,0,0,0.5)">
        <div class="modal-dialog">
            <form wire:submit.prevent="ubahPengajuanBooking">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Edit Pengajuan Booking</h1>
                        <button type="button" class="btn-close" wire:click="$dispatchSelf('closeModalEdit')" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        {{-- Lokasi --}}
                        <div class="mb-3" x-data x-init="initFuncInput.initLokasiSelect2($el.querySelector('select'), $wire)" wire:key="lokasi-select" wire:ignore>
                            <label for="lokasiId" class="form-label">Lokasi</label>
                            <select id="lokasiId" class="form-select">
                                <option value="">Pilih Lokasi...</option>
                                @foreach ($lokasis as $lok)
                                    <option value="{{ $lok->id }}" @selected($lok->id == $lokasiId)>{{ $lok->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Laboratorium --}}
                        @if(!empty($laboratoriumList))
                        <div class="mb-3" x-data x-init="initFuncInput.initLaboratoriumSelect2($el.querySelector('select'), $wire)" wire:key="laboratorium-list-{{ md5(json_encode($laboratoriumList)) }}" wire:ignore>
                            <label for="laboratoriumId" class="form-label">Laboratorium</label>
                            <select id="laboratoriumId" class="form-select" multiple>
                                @foreach ($laboratoriumList as $lab)
                                    <option value="{{ $lab->id }}" @if(in_array($lab->id, $laboratoriumIds)) selected @endif>{{ $lab->nama_laboratorium }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Mode Tanggal --}}
                        @if(!empty($laboratoriumList))
                            <div class="mb-3">
                                <label class="form-label">Mode Tanggal</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="modeMulti" value="multi" wire:model.live="modeTanggal">
                                        <label class="form-check-label" for="modeMulti">Manual</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="modeRange" value="range" wire:model.live="modeTanggal">
                                        <label class="form-check-label" for="modeRange">Rentang</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Mode Multi --}}
                            @if ($modeTanggal === "multi")
                                <div class="mb-3" wire:key="tanggal-multi-{{ $lokasiId }}" x-data x-init="initFuncInput.initTanggalMultiFlatpickr($refs.tanggalMulti, $wire, @js($this->hariAktif))" wire:ignore>
                                    <label for="tanggalMulti" class="form-label">Tanggal (Manual)</label>
                                    <input type="text" x-ref="tanggalMulti" class="form-control" id="tanggalMulti">
                                </div>

                                @if (!empty($jamOperasionalPerTanggal))
                                    @foreach ($jamOperasionalPerTanggal as $tanggal => $jams)
                                        <div class="mb-3" wire:key="jam-{{ $tanggal }}" x-data x-init="initFuncInput.initJamOperasionalSelect2($el.querySelector('select'), $wire, '{{ $tanggal }}', @js($jamTerpilih[$tanggal] ?? []))" wire:ignore>
                                            <label class="form-label">
                                                {{ Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                                            </label>
                                            <select id="jamSelect{{ $tanggal }}" class="form-select" multiple>
                                                @foreach ($jams as $jam)
                                                    <option value="{{ $jam }}" @if (in_array($jam, $jamTerpilih[$tanggal] ?? [])) selected @endif>
                                                        {{ $jam }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                @endif

                            {{-- Mode Range --}}
                            @elseif ($modeTanggal === "range")
                                <div class="mb-3" wire:key="tanggal-range-{{ $lokasiId }}" x-data x-init="initFuncInput.initTanggalRangeFlatpickr($refs.tanggalRange, $wire)" wire:ignore>
                                    <label for="tanggalRange" class="form-label">Tanggal (Rentang)</label>
                                    <input type="text" x-ref="tanggalRange" class="form-control" id="tanggalRange">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Hari Aktif</label>
                                    <div>
                                        @foreach ([0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'] as $key => $label)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="hariTerpilih{{ $key }}" value="{{ $key }}" wire:model="hariTerpilih">
                                                <label class="form-check-label" for="hariTerpilih{{ $key }}">{{ $label }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @if (!empty($jamOperasionalPerTanggal))
                                    @foreach ($jamOperasionalPerTanggal as $tanggal => $jams)
                                        <div class="mb-3" wire:key="jam-{{ $tanggal }}" x-data x-init="initFuncInput.initJamOperasionalSelect2($el.querySelector('select'), $wire, '{{ $tanggal }}', @js($jamTerpilih[$tanggal] ?? []))" wire:ignore>
                                            <label class="form-label">
                                                {{ Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                                            </label>
                                            <select id="jamSelect{{ $tanggal }}" class="form-select" multiple>
                                                @foreach ($jams as $jam)
                                                    <option value="{{ $jam }}" @if (in_array($jam, $jamTerpilih[$tanggal] ?? [])) selected @endif>
                                                        {{ $jam }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                @endif
                            @endif

                            <div class="mb-3">
                                <label for="keperluanBooking">Keperluan</label>
                                <textarea id="keperluanBooking" class="form-control" wire:model.defer="keperluanBooking" style="resize:none; max-height:100px; min-height:100px;"></textarea>
                            </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$dispatchSelf('closeModalEdit')">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
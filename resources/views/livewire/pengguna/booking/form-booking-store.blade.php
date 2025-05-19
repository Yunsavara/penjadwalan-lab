@php
    use \Carbon\Carbon;
@endphp

<div>
    <div class="modal fade" id="modalPengajuanBooking" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Buat Pengajuan Booking</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Name Model --}}

                <div class="modal-body">
                    {{-- Pilihan Lokasi --}}
                    <div class="mb-3" x-data x-init="initFuncInput.initLokasiSelect2($el.querySelector('select'), $wire)" wire:ignore>
                        <label for="lokasiId" class="form-label">Lokasi</label>
                        <div id="select2-lokasi-parent">
                            <select id="lokasiId" class="form-select">
                                <option value="">Pilih Lokasi</option>
                                @foreach ($lokasis as $lok)
                                    <option value="{{ $lok->id }}">{{ $lok->nama_lokasi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Pilihan Laboratorium --}}
                    @if (!empty($laboratoriumList))
                        <div class="mb-3" x-data x-init="initFuncInput.initLaboratoriumSelect2($el.querySelector('select'), $wire)" wire:ignore>
                            <label for="laboratoriumId">Laboratorium</label>
                            <div id="select2-laboratorium-parent">
                                <select id="laboratoriumId" class="form-select">
                                    <option value="">Pilih Laboratorium</option>
                                    @foreach ($laboratoriumList as $lab)
                                        <option value="{{ $lab->id }}">{{ $lab->nama_laboratorium }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    {{-- Mode Tanggal --}}
                    @if ($laboratoriumList)
                        <div class="mb-3">
                            <label for="form-label">Mode Tanggal</label>
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

                        {{-- Tanggal Multi --}}
                        @if ($modeTanggal === 'multi')
                            <div class="mb-3" x-data x-init="initFuncInput.initTanggalMultiFlatpickr($refs.tanggalMulti, $wire)" wire:ignore>
                                <label class="form-label" for="tanggalMulti">Tanggal (Manual)</label>
                                <input type="text" id="tanggalMulti" x-ref="tanggalMulti" class="form-control">
                            </div>

                            @if (!empty($jamOperasionalPerTanggal))
                                @foreach ($jamOperasionalPerTanggal as $tanggal => $jams)
                                    <div class="mb-4" x-data <div class="mb-4" x-data x-init="initFuncInput.initJamOperasionalSelect2($el.querySelector('select'), $wire, {{ $tanggal }})" wire:ignore>
                                        <label class="form-label">
                                            {{ Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                                        </label>
                                        <select id="jamSelect{{ $tanggal }}" multiple class="form-select">
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

                        {{-- Tanggal Range --}}
                        @if ($modeTanggal === 'range')
                            {{-- Input rentang tanggal flatpickr --}}
                            <div class="mb-3" x-init="initFuncInput.initTanggalRangeFlatpickr($refs.tanggalRange, $wire)" wire:ignore>
                                <label for="tanggalRange" class="form-label">Tanggal (Rentang)</label>
                                <input type="text" x-ref="tanggalRange" class="form-control" />
                            </div>

                            {{-- Checkbox Hari Operasional --}}
                            @if (!empty($hariOperasionalList))
                                <div class="mb-3">
                                    <label>Hari Operasional:</label>
                                    <div class="row">
                                        @foreach ($hariOperasionalList as $hari)
                                            <div class="col-6 col-md-4">
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        wire:model.live="hariTerpilih" 
                                                        value="{{ $hari->hari_operasional }}" 
                                                        id="hariOperasional{{ $hari->id }}" />
                                                    <label class="form-check-label" for="hariOperasional{{ $hari->id }}">
                                                        {{ Carbon::create()->startOfWeek(Carbon::SUNDAY)->addDays($hari->hari_operasional)->locale('id')->dayName }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!empty($jamOperasionalPerTanggal))
                                @foreach ($jamOperasionalPerTanggal as $tanggal => $jamList)
                                    <div class="mb-4" wire:key="jam-{{ $tanggal }}" x-data x-init="initFuncInput.initJamOperasionalSelect2($el.querySelector('select'), $wire, '{{ $tanggal }}')" wire:ignore>
                                        <label class="form-label">
                                            {{ Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                                        </label>
                                        <select id="jamSelect{{ $tanggal }}" multiple class="form-select">
                                            @foreach ($jamList as $jam)
                                                <option value="{{ $jam }}" @if (in_array($jam, $jamTerpilih[$tanggal] ?? [])) selected @endif>
                                                    {{ $jam }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                            @endif

                        @endif

                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Kirim</button>
                </div>
            </div>
        </div>
    </div>
</div>

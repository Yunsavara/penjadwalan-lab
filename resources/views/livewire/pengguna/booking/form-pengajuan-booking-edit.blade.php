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
                            <h1 class="modal-title fs-5">Buat Pengajuan Booking</h1>
                            <button type="button" class="btn-close" wire:click="$dispatchSelf('closeModalEdit')" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                            <div class="mb-3" x-data x-init="initFuncInput.initLokasiSelect2($el.querySelector('select'), $wire)" wire:key="lokasi-select" wire:ignore>
                                <label for="lokasiId" class="form-label">Lokasi</label>
                                <select id="lokasiId" class="form-select">
                                    <option value="">Pilih Lokasi...</option>
                                    @foreach ($lokasis as $lok)
                                        <option value="{{ $lok->id }}" @selected($lok->id == $lokasiId)>{{ $lok->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            @if(!empty($laboratoriumList))
                                <div class="mb-3" x-data x-init="initFuncInput.initLaboratoriumSelect2($el.querySelector('select'), $wire)" wire:key="laboratorium-list-{{ md5(json_encode($laboratoriumList)) }}" wire:ignore>
                                    <label for="laboratoriumId" class="form-label">Laboratorium</label>
                                    <select id="laboratoriumid" class="form-select" multiple>
                                        @foreach ($laboratoriumList as $lab)
                                            <option value="{{ $lab->id }}">{{ $lab->nama_laboratorium }}</option>
                                        @endforeach
                                    </select>
                                </div>

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

                                @if ($modeTanggal === "multi")
                                    <div class="mb-3" wire:key="tanggal-multi-{{ $lokasiId }}" x-data x-init="initFuncInput.initTanggalMultiFlatpickr($refs.tanggalMulti, $wire, @js($this->hariAktif))" wire:ignore>
                                        <label for="tanggalMulti" class="form-label">Tanggal (Manual)</label>
                                        <input type="text" x-ref="tanggalMulti" class="form-control">
                                    </div>

                                     @if (!empty($jamOperasionalPerTanggal))
                                        @foreach ($jamOperasionalPerTanggal as $tanggal => $jams)
                                            <div class="mb-3" wire:key="jam-{{ $tanggal }}" x-data x-init="initFuncInput.initJamOperasionalSelect2($el.querySelector('select'), $wire, '{{ $tanggal }}')" wire:ignore>
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

                            @endif

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$dispatchSelf('closeModalEdit')">Tutup</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </div>
                    </div>
                </form>
            </div>
    </div>
    @endif
</div>

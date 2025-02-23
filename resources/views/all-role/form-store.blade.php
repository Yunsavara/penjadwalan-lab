<div class="container-fluid px-3">
    <form action="{{ route('pengajuan.store') }}" method="POST">
        @csrf

        <div class="col-12 mb-3">
            <label for="pilihRuangan">Ruangan</label>
            <select name="lab_id" id="pilihRuangan" class="form-select">
                <option value=""></option>
                @foreach ($Ruangan as $item)
                    @php
                        $label = array_filter([
                            $item->Jenislab?->name && $item->name ? "{$item->Jenislab->name} {$item->name}" : $item->name,
                            $item->kapasitas ? "Kapasitas: {$item->kapasitas}" : null,
                            $item->lokasi ? "Lokasi: {$item->lokasi}" : null
                        ]);
                    @endphp
                    @if ($label)
                        <option value="{{ $item->id }}" {{ old('lab_id') == $item->id ? 'selected' : '' }}>
                            {{ implode(' ', $label) }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="col-12 mb-3">
            <label for="tanggalPengajuan">Tanggal Pengajuan</label>
            <input type="text" id="tanggalPengajuan" class="form-control" name="tanggal_pengajuan[]" placeholder="Pilih Tanggal" value="{{ old('tanggal_pengajuan') ? implode(',', old('tanggal_pengajuan')) : '' }}">
        </div>

        @php
            $old_jam_mulai = old('jam_mulai', []);
            $old_jam_selesai = old('jam_selesai', []);
        @endphp

        <div id="jamContainer" class="col-12 mb-3 d-flex flex-wrap justify-content-md-between align-items-center">
            @foreach (old('tanggal_pengajuan', []) as $tanggal)
                <div class="col-12 border p-3 rounded">
                    <h6 class="fw-bold">Tanggal : {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="jam_mulai_{{ $tanggal }}">Jam Mulai</label>
                            <input type="text" id="jam_mulai_{{ $tanggal }}" class="form-control timepicker"
                                name="jam_mulai[{{ $tanggal }}]" value="{{ $old_jam_mulai[$tanggal] ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label for="jam_selesai_{{ $tanggal }}">Jam Selesai</label>
                            <input type="text" id="jam_selesai_{{ $tanggal }}" class="form-control timepicker"
                                name="jam_selesai[{{ $tanggal }}]" value="{{ $old_jam_selesai[$tanggal] ?? '' }}">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination untuk jam -->
        <div id="paginationControls" class="d-flex justify-content-between align-items-center mt-3 mb-3" style="display: none;">
        </div>


        <div id="hiddenJamInputs">
            @foreach (old('jam_mulai', []) as $tanggal => $jam)
                <input type="hidden" name="jam_mulai[{{ $tanggal }}]" value="{{ $jam }}">
            @endforeach
            @foreach (old('jam_selesai', []) as $tanggal => $jam)
                <input type="hidden" name="jam_selesai[{{ $tanggal }}]" value="{{ $jam }}">
            @endforeach
        </div>


        {{-- Untuk user_id --}}
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div class="col-12 mb-3">
            <label for="keperluanPengajuan">Keperluan</label>
            <textarea name="keperluan" id="keperluanPengajuan" class="form-control" autocomplete="off" style="min-height:80px; max-height:80px;">{{ old('keperluan') }}</textarea>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="reset" class="btn btn-danger me-2">Reset</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
</div>

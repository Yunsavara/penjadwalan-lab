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

        <div id="tanggalContainer">
            <div id="tanggalInputs" class="tanggal-page">
                @php
                    $oldTanggal = old('tanggal_pengajuan', []);
                    $oldJamMulai = old('jam_mulai', []);
                    $oldJamSelesai = old('jam_selesai', []);
                @endphp

                @if (!empty($oldTanggal))
                    @foreach ($oldTanggal as $index => $tanggal)
                        <div class="tanggal-input">
                            <input type="date" name="tanggal_pengajuan[]" class="form-control mb-2"
                                   value="{{ $tanggal }}" required>
                            <input type="time" name="jam_mulai[]" class="form-control mb-2"
                                   value="{{ $oldJamMulai[$index] ?? '' }}" required>
                            <input type="time" name="jam_selesai[]" class="form-control mb-2"
                                   value="{{ $oldJamSelesai[$index] ?? '' }}" required>
                        </div>
                    @endforeach
                @else
                    <div class="tanggal-input">
                        <input type="date" name="tanggal_pengajuan[]" class="form-control mb-2" required>
                        <input type="time" name="jam_mulai[]" class="form-control mb-2" required>
                        <input type="time" name="jam_selesai[]" class="form-control mb-2" required>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-between mt-2">
                <button type="button" id="prevTanggal" class="btn btn-secondary" disabled>Sebelumnya</button>
                <button type="button" id="removeTanggal" class="btn btn-danger" disabled>Hapus Tanggal</button>
                <button type="button" id="addTanggal" class="btn btn-secondary">Tambah Tanggal</button>
                <button type="button" id="nextTanggal" class="btn btn-secondary" disabled>Berikutnya</button>
            </div>
        </div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div class="col-12 mb-3">
            <label for="keperluanPengajuan">Keperluan</label>
            <textarea name="keperluan" id="keperluanPengajuan" class="form-control" autocomplete="off"
                      style="min-height:80px; max-height:80px;">{{ old('keperluan') }}</textarea>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="reset" class="btn btn-danger me-2">Reset</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
</div>

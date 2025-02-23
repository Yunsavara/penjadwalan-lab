<div id="formPengajuanUpdateContainer" style="display: none;">
    <form action="{{ route('pengajuan.update', 'kode_pengajuan') }}" method="POST" id="formUpdatePengajuan">
        @csrf
        @method('PUT')

        <div class="col-12 mb-3">
            <label for="lab_id">Ruangan</label>
            <select name="lab_id" id="lab_id" class="form-select">
                <option value=""></option>
                @foreach ($Ruangan as $item)
                    @php
                        $label = array_filter([
                            $item->Jenislab?->name && $item->name ? "{$item->Jenislab->name} {$item->name}" : $item->name,
                            $item->kapasitas ? "Kapasitas: {$item->kapasitas}" : null,
                            $item->lokasi ? "Lokasi: {$item->lokasi}" : null
                        ]);
                    @endphp
                    <option value="{{ $item->id }}" {{ old('lab_id') == $item->id ? 'selected' : '' }}>
                        {{ implode(' ', $label) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 mb-3">
            <label for="tanggalPengajuan">Tanggal Pengajuan</label>
            <input type="text" id="tanggalPengajuan" class="form-control" name="tanggal_pengajuan[]" placeholder="Pilih Tanggal">
        </div>

        <div id="jamContainer" class="col-12 mb-3 d-flex flex-wrap justify-content-md-between align-items-center"></div>

        <div id="hiddenJamInputs"></div>

        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        <div class="col-12 mb-3">
            <label for="keperluanPengajuan">Keperluan</label>
            <textarea name="keperluan" id="keperluanPengajuan" class="form-control" style="min-height:80px; max-height:80px;"></textarea>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="reset" class="btn btn-danger me-2">Reset</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>

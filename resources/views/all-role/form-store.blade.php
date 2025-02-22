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
                        <option value="{{ $item->id }}">
                            {{ implode(' ', $label) }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="col-12 mb-3">
            <label for="tanggalPengajuan">Tanggal Pengajuan</label>
            <input type="date" id="tanggalPengajuan" class="form-control" placeholder="Pilih Tanggal">
        </div>

        <div id="jamContainer" class="col-12 mb-3 d-flex flex-wrap justify-content-md-between align-items-center">
            <!-- Disini jam mulai dan jam selesai akan muncul -->
        </div>

        <!-- Pagination untuk jam -->
        <div id="paginationControls" class="d-flex justify-content-between align-items-center mt-3 mb-3" style="display: none;">
        </div>


        {{-- Tanggal --}}
        <div id="hiddenTanggalInputs"></div>

        {{-- Jam --}}
        <div id="hiddenJamInputs"></div>

        {{-- Untuk user_id --}}
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div class="col-12 mb-3">
            <label for="keperluanPengajuan">Keperluan</label>
            <textarea name="keperluan" id="keperluanPengajuan" class="form-control" autocomplete="off" style="min-height:80px; max-height:80px;"></textarea>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="reset" class="btn btn-danger me-2">Reset</button>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
    </form>
</div>

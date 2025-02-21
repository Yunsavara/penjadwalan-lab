<form id="formPengajuan" action="" method="POST">
    @csrf

    <div class="col-12 mb-3">
        <label for="pilihRuangan">Ruangan</label>
        <select name="lab_id" id="pilihRuangan" class="form-select">
            <option value="blabla">BlaBla</option>
            <option value="blabla2">BlaBla2</option>
        </select>
    </div>

    <div class="col-12 mb-3">
        <label for="tanggalPengajuan">Tanggal Pengajuan</label>
        <input type="date" name="tanggal_pengajuan[]" id="tanggalPengajuan" class="form-control">
    </div>

    <div id="jamContainer" class="col-12 mb-3 d-flex flex-wrap justify-content-md-between align-items-center">
        <!-- Disini jam mulai dan jam selesai akan muncul -->
    </div>

    <!-- Pagination untuk jam -->
    <div id="paginationControls" class="d-flex justify-content-between align-items-center mt-3 mb-3" style="display: none;">
    </div>


    <div class="col-12 mb-3">
        <label for="keperluanPengajuan">Keperluan</label>
        <textarea name="keperluan" id="keperluanPengajuan" class="form-control" autocomplete="off" style="min-height:80px; max-height:80px;"></textarea>
        @error('keperluan')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    {{-- <div class="col-12 mb-3">
        <label for="keperluanBooking">Keperluan</label>
        <textarea name="keperluan" id="keperluanBooking" class="form-control @error('keperluan') is-invalid @enderror" autocomplete="off" style="min-height:80px; max-height:80px;">{{ old('keperluan', $Jadwal->keperluan) }}</textarea>
        @error('keperluan')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div> --}}
</form>

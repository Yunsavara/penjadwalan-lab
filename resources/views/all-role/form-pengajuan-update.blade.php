<div id="editPengajuanContainer" class="mt-4 d-none">
    <h4>Edit Pengajuan</h4>
    <form id="updatePengajuanForm" action="{{ route('pengajuan.update', ':kode_pengajuan') }}" method="POST">
        {{-- Form nya di Inject di Javascript --}}
        @csrf
        @method('PUT')

        <!-- Hidden input untuk kode_pengajuan -->
        <input type="hidden" class="form-control" name="kode_pengajuan" id="editKodePengajuan">

        <div class="col-12 mb-3">
            <label for="editPilihRuangan">Ruangan</label>
            <select name="lab_id" id="editPilihRuangan" class="form-select">
                <option value=""></option>
                @foreach ($Ruangan as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>

        <div id="editTanggalContainer">
            <div id="editTanggalInputs"></div>

            <div class="d-flex justify-content-between mt-2">
                <button type="button" id="editPrevTanggal" class="btn btn-secondary" disabled>Sebelumnya</button>
                <button type="button" id="editRemoveTanggal" class="btn btn-danger" disabled>Hapus Tanggal</button>
                <button type="button" id="editAddTanggal" class="btn btn-secondary">Tambah Tanggal</button>
                <button type="button" id="editNextTanggal" class="btn btn-secondary" disabled>Berikutnya</button>
            </div>
        </div>

        <div class="col-12 mb-3">
            <label for="editKeperluanPengajuan">Keperluan</label>
            <textarea name="keperluan" id="editKeperluanPengajuan" class="form-control"></textarea>
        </div>

        <div class="col-12 d-flex justify-content-end">
            <button type="button" id="cancelEdit" class="btn btn-secondary me-2">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>

<div class="mb-3">
    <label for="tanggalPengajuanBooking" class="form-label">Tanggal Pengajuan Booking</label>
    <input type="text" id="tanggalPengajuanBooking" class="form-control" placeholder="Pilih Tanggal">
</div>

<div class="mb-3">
    <label for="labPengajuanBooking">Pilih Laboratorium</label>
    <select id="labPengajuanBooking" class="form-select" multiple>
        <option value="Lab Komputer">Lab Komputer</option>
        <option value="Lab Fisika">Lab Fisika</option>
        <option value="Lab Mesin">Lab Mesin</option>
    </select>
</div>

<div class="mb-3">
    <label for="keperluanPengajuanBooking">Keperluan</label>
    <textarea id="keperluanPengajuanBooking" class="form-control" placeholder="Keperluan Mengajar Mata Kuliah..." style="min-height: 100px; max-height:100px; resize:none;"></textarea>
</div>

<div class="mb-3">
    <label for="modeJam" class="form-label">Mode Pilih Jam</label>
    <select id="modeJam" class="form-select">
        <option value="manual" selected>Manual</option>
        <option value="otomatis">Otomatis</option>
    </select>
</div>

<div class="mb-3 d-none" id="autoJamContainer">
    <label for="jamOtomatis" class="form-label">Pilih Jam Otomatis</label>
    <select id="jamOtomatis" class="form-select" multiple>
        <!-- Diisi via JavaScript -->
    </select>
</div>

<div class="mb-3">
    <button type="button" class="btn btn-primary col-12" onclick="generateBookingForm()">Generate Form</button>
</div>

<!-- Tempat Form Hasil Generate -->
<div id="generatedFormContainer"></div>

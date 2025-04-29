// Fungsi untuk meng-handle pembaruan pilihan laboratorium berdasarkan lokasi
export async function updateLaboratoriumOptions(lokasiId) {
    const laboratoriumSelect = $('#laboratorium');

    // Kosongkan pilihan laboratorium sebelumnya
    laboratoriumSelect.empty().append('<option value="">-- Pilih Laboratorium --</option>');

    if (!lokasiId) return;

    try {
        const response = await fetch(`/pengajuan/api/data-laboratorium/${lokasiId}`);
        const data = await response.json();

        if (data.length > 0) {
            data.forEach(lab => {
                const option = new Option(lab.nama_laboratorium, lab.id);
                laboratoriumSelect.append(option);
            });
        } else {
            const option = new Option("Tidak ada laboratorium tersedia", "");
            laboratoriumSelect.append(option);
        }

        // Refresh Select2 setelah menambahkan opsi baru
        laboratoriumSelect.trigger('change');
    } catch (error) {
        console.error('Error fetching laboratorium:', error);
    }
}

// Fungsi untuk generate checkbox hari operasional
export async function generateHariOperasionalCheckbox(lokasiId) {
    const container = $('#hariOperasionalContainer');
    container.empty();

    // Hapus label jika sebelumnya sudah ditambahkan
    $('#hariBookingLabel').remove();

    if (!lokasiId) return;

    try {
        const response = await fetch(`/pengajuan/api/data-hari-operasional/${lokasiId}`);
        const hariOperasional = await response.json();

        if (hariOperasional.length > 0) {
            // Tambahkan label hanya jika ada data hari operasional
            const labelHTML = `
                <label id="hariBookingLabel" class="form-label">Hari Booking</label>
            `;
            container.before(labelHTML);  // Tempatkan label sebelum checkbox

            hariOperasional.forEach(item => {
                const hariValue = item.id; // <-- Gunakan id, bukan nama
                const checkboxHTML = `
                    <div class="col-6 col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="${hariValue}" id="hari-${hariValue}" data-nama-hari="${item.hari_operasional}">
                            <label class="form-check-label" for="hari-${hariValue}">${item.hari_operasional}</label>
                        </div>
                    </div>
                `;
                container.append(checkboxHTML);
            });

            $('input[type="checkbox"]').on('change', function () {
                generateJamOperasional();
            });
        }

    } catch (error) {
        console.error('Error fetching hari operasional:', error);
    }
}

// Fungsi untuk generate select jam operasional berdasarkan hari yang dicentang
export function generateJamOperasional() {
    const container = $('#jamOperasionalContainer');
    container.empty(); // Kosongkan kontainer sebelumnya

    // Ambil semua hari yang dicentang
    const selectedHari = $('input[name="hari_aktif[]"]:checked').map(function() {
        return $(this).val();
    }).get();

    // Untuk setiap hari yang dipilih, buat input select untuk jam operasional
    selectedHari.forEach(async hariId => {
    const checkbox = $(`input[name="hari_aktif[]"][value="${hariId}"]`);
    const namaHari = checkbox.data('nama-hari'); // ambil nama hari dari data attribute

    const selectHTML = `
        <div class="mb-3" id="jam-${hariId}">
            <label for="jam_${hariId}" class="form-label">Jam untuk ${namaHari}</label>
            <select id="jam_${hariId}" class="form-select" multiple required>
                <!-- Jam operasional akan dimasukkan di sini -->
            </select>
        </div>
    `;
    container.append(selectHTML);

    $(`#jam_${hariId}`).select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Jam",
        allowClear: true,
    });

    try {
        const response = await fetch(`/pengajuan/api/jam-operasional/${hariId}`);
        const jamOperasional = await response.json();

        const jamSelect = $(`#jam_${hariId}`);

        if (jamOperasional.length > 0) {
            jamOperasional.forEach(jam => {
                const optionHTML = `<option value="${jam.jam_mulai}">${jam.jam_mulai} - ${jam.jam_selesai}</option>`;
                jamSelect.append(optionHTML);
            });
        } else {
            jamSelect.append('<option value="">Tidak ada jam operasional</option>');
        }

        jamSelect.trigger('change');
    } catch (error) {
        console.error('Error fetching jam operasional:', error);
    }
});

}

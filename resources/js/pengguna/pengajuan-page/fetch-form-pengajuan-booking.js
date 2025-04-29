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
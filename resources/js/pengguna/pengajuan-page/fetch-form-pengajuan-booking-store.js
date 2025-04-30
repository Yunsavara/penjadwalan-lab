export async function updateLaboratoriumOptions(lokasiId) {
    const laboratoriumSelect = $('#laboratorium');

    laboratoriumSelect.empty().append('<option value="">-- Pilih Laboratorium --</option>');

    if (!lokasiId) return;

    try {
        const response = await fetch(`/pengajuan/api/data-laboratorium/${lokasiId}`);
        const laboratoriumList = await response.json();

        laboratoriumList.forEach(lab => {
            const option = new Option(lab.nama_laboratorium, lab.id);
            laboratoriumSelect.append(option); 
        });

        laboratoriumSelect.trigger('change');
    } catch (error) {
        console.error('Error fetching laboratorium:', error);
    }
}

export async function generateHariOperasionalCheckbox(lokasiId) {
    const container = $('#hariOperasionalContainer');
    container.empty();

    $('#hariBookingLabel').remove();

    if (!lokasiId) return;

    try {
        const response = await fetch(`/pengajuan/api/data-hari-operasional/${lokasiId}`);
        const hariList = await response.json();

        if (hariList.length > 0) {
            const labelHTML = `<label id="hariBookingLabel" class="form-label">Hari Booking</label>`;
            container.before(labelHTML);

            hariList.forEach(hari => {
                const checkboxHTML = `
                    <div class="col-6 col-md-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hari_aktif[]" value="${hari.id}" id="hari-${hari.id}" data-nama-hari="${hari.hari_operasional}">
                            <label class="form-check-label" for="hari-${hari.id}">${hari.hari_operasional}</label>
                        </div>
                    </div>
                `;
                container.append(checkboxHTML);
            }); 

            // Event generate jam operasional
            $('input[name="hari_aktif[]"]').on('change', generateJamOperasional);
        }
    } catch (error) {
        console.error('Error fetching hari operasional:', error);
    }
}

export async function generateJamOperasional() {
    const selectedHariIds = $('input[name="hari_aktif[]"]:checked').map(function () {
        return $(this).val();
    }).get();

    $('#jamOperasionalContainer > div').each(function() {
        const divId = $(this).attr('id'); 
        const hariId = divId.split('-')[1]; 
        if (!selectedHariIds.includes(hariId)) {
            $(this).remove();
        }
    });

    for (const hariId of selectedHariIds) {
        if ($(`#jam-${hariId}`).length) continue;

        const checkbox = $(`input[name="hari_aktif[]"][value="${hariId}"]`);
        const namaHari = checkbox.data('nama-hari');

        const selectHTML = `
            <div class="mb-3" id="jam-${hariId}">
                <label for="jam_${hariId}" class="form-label">Jam untuk ${namaHari}</label>
                <select id="jam_${hariId}" class="form-select" multiple required></select>
            </div>
        `;
        $('#jamOperasionalContainer').append(selectHTML);

        const jamSelect = $(`#jam_${hariId}`);
        jamSelect.select2({
            theme: "bootstrap-5",
            placeholder: "Pilih Jam",
            allowClear: true,
        });

        try {
            const response = await fetch(`/pengajuan/api/jam-operasional/${hariId}`);
            const jamList = await response.json();

            jamList.forEach(jam => {
                const optionHTML = `<option value="${jam.jam_mulai}">${jam.jam_mulai} - ${jam.jam_selesai}</option>`;
                jamSelect.append(optionHTML);
            });

            jamSelect.trigger('change');
        } catch (error) {
            console.error('Error fetching jam operasional:', error);
        }
    }
}

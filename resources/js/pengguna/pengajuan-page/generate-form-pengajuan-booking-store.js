// Inisialisasi tombol generate
export function initGenerateButtonForm() {
    document.getElementById("generateBtn").addEventListener("click", async () => {
        const tanggalMulai = document.getElementById("tanggal_mulai").value;
        const tanggalSelesai = document.getElementById("tanggal_selesai").value;
        const laboratoriumIds = $('#laboratorium').val();
        const hariAktif = $('input[name="hari_aktif[]"]:checked').map(function () {
            return {
                id: this.value,
                nama: $(this).data('nama-hari')
            };
        }).get();

        // Validasi input wajib
        if (!tanggalMulai || !tanggalSelesai || laboratoriumIds.length === 0 || hariAktif.length === 0) {
            alert("Lengkapi semua inputan terlebih dahulu.");
            return;
        }

        const tanggalAwal = new Date(tanggalMulai);
        const tanggalAkhir = new Date(tanggalSelesai);
        const accordionContainer = $('#accordionGenerated');
        accordionContainer.empty();

        // Simpan nama lab dari option terpilih
        const laboratoriumLabels = {};
        $('#laboratorium option:selected').each(function () {
            laboratoriumLabels[$(this).val()] = $(this).text();
        });

        // Loop semua tanggal dari mulai sampai selesai
        for (let d = new Date(tanggalAwal); d <= tanggalAkhir; d.setDate(d.getDate() + 1)) {
            const tanggalStr = d.toISOString().split('T')[0];
            const namaHari = d.toLocaleDateString('id-ID', { weekday: 'long' });
            const tanggalFull = d.toLocaleDateString('id-ID', {
                day: 'numeric', month: 'long', year: 'numeric'
            });

            const hariDipilih = hariAktif.find(h => h.nama.toLowerCase() === namaHari.toLowerCase());
            if (!hariDipilih) continue;

            // Ambil daftar jam dari server
            let jamList = [];
            try {
                const response = await fetch(`/pengajuan/api/jam-operasional/${hariDipilih.id}`);
                jamList = await response.json();
            } catch (error) {
                console.error(`Gagal ambil jam untuk hari ${hariDipilih.nama}`, error);
                continue;
            }

            // Ambil jam yang dicentang user
            const jamTercentang = $(`#jam_${hariDipilih.id}`).val() || [];

            // Generate HTML accordion per tanggal
            const itemId = `accordion-${tanggalStr}`;
            let accordionHTML = `
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-${tanggalStr}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${itemId}">
                            ${namaHari}, ${tanggalFull}
                        </button>
                    </h2>
                    <div id="${itemId}" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <button type="button" class="btn btn-sm btn-warning mb-3" onclick="hapusAccordionTanggal('${tanggalStr}')">Hapus Tanggal</button>
            `;

            // Loop setiap lab
            laboratoriumIds.forEach(labId => {
                const labName = laboratoriumLabels[labId] || `Laboratorium ${labId}`;
                const labContainerId = `lab-${tanggalStr}-${labId}`;

                accordionHTML += `
                    <div class="mb-3 border p-2 rounded" id="${labContainerId}">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>${labName}</strong>
                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusLab('${labContainerId}', '${tanggalStr}')">Hapus</button>
                        </div>
                        <div class="row mt-2">
                `;

                jamList.forEach(jam => {
                    const checkboxName = `slot[${tanggalStr}][${labId}][]`;
                    const checkboxId = `slot-${tanggalStr}-${labId}-${jam.jam_mulai.replace(":", "")}`;
                    const isChecked = jamTercentang.includes(jam.jam_mulai) ? 'checked' : '';
                    const value = `${jam.jam_mulai}|${jam.jam_selesai}`;  // Menggabungkan jam mulai dan selesai
                
                    accordionHTML += `
                        <div class="col-6 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="${checkboxName}" value="${value}" id="${checkboxId}" ${isChecked}>
                                <label class="form-check-label" for="${checkboxId}">${jam.jam_mulai} - ${jam.jam_selesai}</label>
                            </div>
                        </div>
                    `;
                });
                

                accordionHTML += `
                        </div>
                    </div>
                `;
            });

            accordionHTML += `
                        </div>
                    </div>
                </div>
            `;

            accordionContainer.append(accordionHTML);
        }

        // Tampilkan hasil jika ada
        if (accordionContainer.children().length > 0) {
            $('#hasilGenerate').removeClass('d-none');
            $('html, body').animate({
                scrollTop: $('#hasilGenerate').offset().top
            }, 500);
        } else {
            alert("Tidak ada slot yang dapat ditampilkan.");
        }
    });
}

// Fungsi hapus 1 lab dari dalam 1 tanggal
export function hapusLab(labContainerId, tanggalStr) {
    const labElement = document.getElementById(labContainerId);
    if (labElement) {
        labElement.remove();

        const accordionBody = document.querySelector(`#accordion-${tanggalStr} .accordion-body`);
        const sisaLab = accordionBody.querySelectorAll(`[id^="lab-${tanggalStr}-"]`);

        if (sisaLab.length === 0) {
            const accordionItem = document.querySelector(`#accordion-${tanggalStr}`).closest('.accordion-item');
            if (accordionItem) accordionItem.remove();
        }
    }
}

// Fungsi hapus semua lab dalam satu tanggal
export function hapusAccordionTanggal(tanggalStr) {
    const accordionItem = document.querySelector(`#accordion-${tanggalStr}`).closest('.accordion-item');
    if (accordionItem) {
        accordionItem.remove();
    }
}

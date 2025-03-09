import { Modal } from 'bootstrap';

document.addEventListener("DOMContentLoaded", () => {
    initPengajuanDatatable();
});

function initPengajuanDatatable() {
    $.fn.DataTable.ext.pager.numbers_length = 3;

    let table = $('#tablePengajuan').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/pengajuan-jadwal/pengajuan-jadwal-data",
            type: "GET",
            dataType: "json",
        },
        columns: [
            {
                data: null,
                name: 'index',
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Indexing Table
                }
            },
            { data: 'kode_pengajuan', name: 'kode_pengajuan' },
            { data: 'lab', name: 'lab' },
            { data: 'status', name: 'status' },
            {
                data: null,
                name: 'aksi',
                render: function (data, type, row) {
                    return `
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu py-0">
                                <li><a class="dropdown-item btn-detail" data-kode="${row.kode_pengajuan}">Detail</a></li>
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        initComplete: function () {
            moveTools();
        }
    });

    // Event listener untuk tombol "Detail"
    $('#tablePengajuan tbody').on('click', '.btn-detail', function () {
        let kodePengajuan = $(this).data('kode');
        showDetailModal(kodePengajuan);
    });
}

function showDetailModal(kodePengajuan) {
    $.ajax({
        url: `/pengajuan-jadwal/detail/${kodePengajuan}`,
        type: "GET",
        success: function (response) {
            if (response.success) {
                let data = response.data;

                // Update elemen modal langsung
                document.getElementById("modalKodePengajuan").textContent = data.kode_pengajuan;
                document.getElementById("modalStatus").textContent = data.status;

                // Isi daftar lab & jadwal
                let labList = document.getElementById("modalLab");
                labList.innerHTML = ""; // Kosongkan dulu sebelum diisi ulang
                data.details.forEach(detail => {
                    let li = document.createElement("li");
                    li.textContent = `${detail.lab} (${detail.tanggal}, ${detail.jam_mulai} - ${detail.jam_selesai})`;
                    labList.appendChild(li);
                });

                // 🔹 **Isi log status**
                let logList = document.getElementById("modalLogs");
                logList.innerHTML = ""; // Kosongkan sebelum isi ulang

                data.logs.forEach(log => {
                    let li = document.createElement("li");
                    li.innerHTML = `<strong>${log.status}</strong> oleh <em>${log.user}</em> pada ${log.waktu} <br> Catatan: ${log.catatan}`;
                    logList.appendChild(li);
                });

                // Tampilkan modal
                const modalElement = document.getElementById('modalDetail');
                const modal = new Modal(modalElement);
                modal.show();
            } else {
                alert("Gagal mengambil data detail.");
            }
        },
        error: function () {
            alert("Terjadi kesalahan saat mengambil data.");
        }
    });
}

function moveTools() {
    const search = document.querySelector(".dt-search");
    const sorting = document.querySelector(".dt-length");
    const info = document.querySelector(".dt-info");
    const paging = document.querySelector(".dt-paging");

    if (search && sorting && info && paging) {
        search.querySelector("input").placeholder = "Pencarian...";
        document.getElementById("searchPengajuan").appendChild(search);
        document.getElementById("sortingPengajuan").appendChild(sorting);
        document.getElementById("infoPengajuan").appendChild(info);
        document.getElementById("pagingPengajuan").appendChild(paging);
    } else {
        console.log("Tools Error");
    }
}

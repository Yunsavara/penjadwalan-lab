import { showDetailModal } from "./modal-detail-pengajuan.js";
import { showEditModal } from "./form-pengajuan-edit.js";
import { batalkanPengajuan } from "./batalkan-pengajuan.js";

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
                                <li><a class="dropdown-item btn-edit" data-kode="${row.kode_pengajuan}">Edit</a></li>
                                <li><a class="dropdown-item btn-detail" data-kode="${row.kode_pengajuan}">Detail</a></li>
                                <li><a class="dropdown-item btn-batal text-danger" data-kode="${row.kode_pengajuan}">Batalkan</a></li>
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

    // Event listener untuk tombol "Edit"
    $('#tablePengajuan tbody').on('click', '.btn-edit', function () {
        let kodePengajuan = $(this).data('kode');
        showEditModal(kodePengajuan);
    });

    // Event listener untuk tombol "Batalkan"
    $("#tablePengajuan tbody").on("click", ".btn-batal", function () {
        let kodePengajuan = $(this).data("kode");
        batalkanPengajuan(kodePengajuan);
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

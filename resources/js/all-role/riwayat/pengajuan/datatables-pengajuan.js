import { showDetailModal } from "./modal-detail-pengajuan.js";
import { showEditModal } from "./form-pengajuan-edit.js";
import { batalkanPengajuan } from "./batalkan-pengajuan.js";

document.addEventListener("DOMContentLoaded", () => {
    generateTableHeadPengajuan();
    initPengajuanDatatable();
});

function generateTableHeadPengajuan() {
    let table = document.getElementById("tablePengajuan");

    let thead = document.createElement("thead");
    thead.classList.add("table-dark", "sticky-top");

    let tr = document.createElement("tr");

    let headers = [
        { text: "No", style: "min-width: 3rem" },
        { text: "Kode Pengajuan", style: "min-width: 11.25rem" },
        { text: "Ruangan", style: "width: auto" },
        { text: "Status", style: "width: auto" },
        { text: "Aksi", style: "width: auto" },
    ];

    headers.forEach(header => {
        let th = document.createElement("th");
        th.textContent = header.text;
        if (header.style) {
            th.style = header.style;
        }
        tr.appendChild(th);
    });

    thead.appendChild(tr);
    table.appendChild(thead);
}

function initPengajuanDatatable() {
    $.fn.DataTable.ext.pager.numbers_length = 3;

    let table = $('#tablePengajuan').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/jadwal/pengajuan-data",
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
            { data: 'lab', name: 'lab', className: 'min-tablet' },
            { data: 'status', name: 'status', className: 'min-tablet' },
            {
                data: null,
                name: 'aksi',
                render: function (data, type, row) {
                    let aksiButtons = `
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu py-0">
                                <li><a class="dropdown-item btn-detail" data-kode="${row.kode_pengajuan}">Detail</a></li>`;

                    // Tampilkan "Edit" dan "Batalkan" hanya jika status "pending"
                    if (row.status === "Pending") {
                        aksiButtons += `
                                <li><a class="dropdown-item btn-edit" data-kode="${row.kode_pengajuan}">Edit</a></li>
                                <li><a class="dropdown-item btn-batal text-danger" data-kode="${row.kode_pengajuan}">Batalkan</a></li>`;
                    }

                    aksiButtons += `</ul></div>`;

                    return aksiButtons;
                }
                , className: 'min-desktop'
            }
        ],
        initComplete: function () {
            moveToolsPengajuan();
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

function moveToolsPengajuan() {
    const pengajuanTable = document.getElementById("tablePengajuan").closest("#tablePengajuan_wrapper");

    const search = pengajuanTable.querySelector(".dt-search");
    const sorting = pengajuanTable.querySelector(".dt-length");
    const info = pengajuanTable.querySelector(".dt-info");
    const paging = pengajuanTable.querySelector(".dt-paging");

    if (search && sorting && info && paging) {
        search.querySelector("input").placeholder = "Pencarian...";
        document.getElementById("searchPengajuan").appendChild(search);
        document.getElementById("sortingPengajuan").appendChild(sorting);
        document.getElementById("infoPengajuan").appendChild(info);
        document.getElementById("pagingPengajuan").appendChild(paging);
    } else {
        console.log("Tools Pengajuan Error");
    }
}

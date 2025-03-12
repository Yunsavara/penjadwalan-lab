import { showTerimaModal } from "./terima-pengajuan";
import { showTolakModal } from "./tolak-pengajuan";

document.addEventListener("DOMContentLoaded", () => {
    generateTableHeadLaboranPengajuan();
    initLaboranPengajuanDatatable();
});

function generateTableHeadLaboranPengajuan() {
    let table = document.getElementById("tableLaboranPengajuan");

    let thead = document.createElement("thead");
    thead.classList.add("table-dark", "sticky-top");

    let tr = document.createElement("tr");

    let headers = [
        { text: "Kode Pengajuan", style: "min-width: 9.25rem" },
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

function initLaboranPengajuanDatatable() {
    $.fn.DataTable.ext.pager.numbers_length = 3;

    let table = $('#tableLaboranPengajuan').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/laboran/jadwal/pengajuan-data",
            type: "GET",
            dataType: "json",
        },
        columns: [
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
                                <li><a class="dropdown-item btn-detail" data-kode="${row.kode_pengajuan}">Detail</a></li>`; //Belum di implementasi di sisi admin

                        if (row.status === "Pending") {
                            aksiButtons += `
                                    <li><a class="dropdown-item btn-terima" data-kode="${row.kode_pengajuan}">Terima</a></li>
                                    <li><a class="dropdown-item btn-tolak text-danger" data-kode="${row.kode_pengajuan}">Tolak</a></li>`;
                        }

                    aksiButtons += `</ul></div>`;

                    return aksiButtons;
                }
            }
        ],
        initComplete: function () {
            moveToolsLaboranPengajuan();
        }
    });

     // Event listener untuk tombol "Detail"
    // $('#tablePengajuan tbody').on('click', '.btn-detail', function () {
    //     let kodePengajuan = $(this).data('kode');
    //     showDetailModal(kodePengajuan);
    // });

    // Event listener untuk tombol "Terima"
    $('#tableLaboranPengajuan tbody').on('click', '.btn-terima', function () {
        let kodePengajuan = $(this).data('kode');
        showTerimaModal(kodePengajuan);
    });

    //Event listener untuk tombol "Tolak"
    $("#tableLaboranPengajuan tbody").on("click", ".btn-tolak", function () {
        let kodePengajuan = $(this).data("kode");
        showTolakModal(kodePengajuan);
    });
}

function moveToolsLaboranPengajuan() {
    const pengajuanTable = document.getElementById("tableLaboranPengajuan").closest("#tableLaboranPengajuan_wrapper");

    const search = pengajuanTable.querySelector(".dt-search");
    const sorting = pengajuanTable.querySelector(".dt-length");
    const info = pengajuanTable.querySelector(".dt-info");
    const paging = pengajuanTable.querySelector(".dt-paging");

    if (search && sorting && info && paging) {
        search.querySelector("input").placeholder = "Pencarian...";
        document.getElementById("searchLaboranPengajuan").appendChild(search);
        document.getElementById("sortingLaboranPengajuan").appendChild(sorting);
        document.getElementById("infoLaboranPengajuan").appendChild(info);
        document.getElementById("pagingLaboranPengajuan").appendChild(paging);
    } else {
        console.log("Tools Pengajuan Error");
    }
}

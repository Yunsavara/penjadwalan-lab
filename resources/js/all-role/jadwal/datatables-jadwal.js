import { showDetailModal } from "../jadwal/modal-detail-jadwal.js";
import { batalkanJadwal } from "./batalkan-jadwal.js";

document.addEventListener("DOMContentLoaded", () => {
    generateTableHead();
    initJadwalDatatable();
});

function generateTableHead() {
    let table = document.getElementById("tableJadwal");

    let thead = document.createElement("thead");
    thead.classList.add("table-dark", "sticky-top");

    let tr = document.createElement("tr");

    let headers = [
        { text: "No", style: "min-width: 3rem" },
        { text: "Kode Pengajuan", style: "min-width: 11.25rem" },
        { text: "Ruangan", style: "width: auto" },
        { text: "Tanggal", style: "min-width: 7.4rem" },
        { text: "Jam Mulai", style: "min-width: 6.5rem" },
        { text: "Jam Selesai", style: "min-width: 6.5rem" },
        { text: "Keperluan", style: "min-width: 12rem" },
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

function initJadwalDatatable() {
    $.fn.DataTable.ext.pager.numbers_length = 3;

    let table = $('#tableJadwal').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/jadwal/jadwal-data",
            type: "GET",
            dataType: "json",
        },
        columns: [
            { data: 'index', name: 'index' },
            { data: 'kode_pengajuan', name: 'kode_pengajuan' },
            { data: 'lab', name: 'lab', className: 'min-tablet' },
            { data: 'tanggal', name: 'tanggal', className: 'min-tablet' },
            { data: 'jam_mulai', name: 'jam_mulai', className: 'min-desktop' },
            { data: 'jam_selesai', name: 'jam_selesai', className: 'min-desktop' },
            { data: 'keperluan', name: 'keperluan', className: 'none' },
            { data: 'status', name: 'status', className: 'min-desktop' },
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

                    // Tampilkan"Batalkan" hanya jika status "diterima"
                    console.log(`${row.id}`);
                    if (row.status === "Diterima") {
                        aksiButtons += `
                               <li><a class="dropdown-item btn-batal text-danger" data-id="${row.id}">Batalkan</a></li>`;
                    }

                    aksiButtons += `</ul></div>`;

                    return aksiButtons;
                },
                className: 'min-desktop'
            }
        ],
        initComplete: function () {
            moveTools();
        }
    });
    // Event listener untuk tombol "Detail"
    $('#tableJadwal tbody').on('click', '.btn-detail', function () {
        let kodePengajuan = $(this).data('kode');
        showDetailModal(kodePengajuan);
    });

    // Event listener untuk tombol "Batalkan"
    $("#tableJadwal tbody").on("click", ".btn-batal", function () {
        let bookingDetailId = $(this).data("id");
        batalkanJadwal(bookingDetailId);
    });
}

function moveTools() {
    const search = document.querySelector(".dt-search");
    const sorting = document.querySelector(".dt-length");
    const info = document.querySelector(".dt-info");
    const paging = document.querySelector(".dt-paging");

    if (search && sorting && info && paging) {
        search.querySelector("input").placeholder = "Pencarian...";
        document.getElementById("searchJadwal").appendChild(search);
        document.getElementById("sortingJadwal").appendChild(sorting);
        document.getElementById("infoJadwal").appendChild(info);
        document.getElementById("pagingJadwal").appendChild(paging);
    } else {
        console.log("Tools Error");
    }
}

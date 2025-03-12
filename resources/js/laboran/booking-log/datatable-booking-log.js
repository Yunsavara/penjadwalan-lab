document.addEventListener("DOMContentLoaded", () => {
    generateTableHeadBookingLog();
    initBookingLogDatatable();
});

function generateTableHeadBookingLog() {
    let table = document.getElementById("tableLaboranBookingLog");

    let thead = document.createElement("thead");
    thead.classList.add("table-dark", "sticky-top");

    let tr = document.createElement("tr");

    let headers = [
        { text: "Kode Pengajuan", style: "min-width: 11.25rem" },
        { text: "Ruang Lab", style: "width: auto" },
        { text: "Oleh", style: "width: auto" },
        { text: "Catatan", style: "width: auto" },
        { text: "Waktu", style: "width: auto" },  // Menambahkan kolom untuk waktu
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

function initBookingLogDatatable() {
    $.fn.DataTable.ext.pager.numbers_length = 3;

    let table = $('#tableLaboranBookingLog').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "/laboran/jadwal/booking-log",
            type: "GET",
            dataType: "json",
        },
        columns: [
            { data: 'kode_pengajuan', name: 'kode_pengajuan' },
            { data: 'lab', name: 'lab', className: 'min-tablet' },  // Mengubah 'lab' ke 'lab_name'
            { data: 'user_id', name: 'user_id', className: 'min-tablet' }, // Mengubah 'user_id' ke 'user_name'
            { data: 'catatan', name: 'catatan', className: 'min-tablet' },
            { data: 'created_at', name: 'created_at', className: 'none' }, // Menampilkan waktu
        ],
        initComplete: function () {
            moveToolsBookingLog();
        }
    });
}

function moveToolsBookingLog() {
    const pengajuanTable = document.getElementById("tableLaboranBookingLog").closest("#tableLaboranBookingLog_wrapper");

    const search = pengajuanTable.querySelector(".dt-search");
    const sorting = pengajuanTable.querySelector(".dt-length");
    const info = pengajuanTable.querySelector(".dt-info");
    const paging = pengajuanTable.querySelector(".dt-paging");

    if (search && sorting && info && paging) {
        search.querySelector("input").placeholder = "Pencarian...";
        document.getElementById("searchLaboranBookingLog").appendChild(search);
        document.getElementById("sortingLaboranBookingLog").appendChild(sorting);
        document.getElementById("infoLaboranBookingLog").appendChild(info);
        document.getElementById("pagingLaboranBookingLog").appendChild(paging);
    } else {
        console.log("Tools Error");
    }
}

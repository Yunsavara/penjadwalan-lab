document.addEventListener("DOMContentLoaded", async () => {
    generateTableHeadLaboranJadwalGenerate();
    loadJadwalToDataTable();
});

function generateTableHeadLaboranJadwalGenerate() {
    let table = document.getElementById("tableLaboranJadwalGenerate");

    let thead = document.createElement("thead");
    thead.classList.add("table-dark", "sticky-top");

    let tr = document.createElement("tr");

    let headers = [
        { text: "No", style: "min-width: 3rem" },
        { text: "Tanggal", style: "min-width: 8rem" },
        { text: "Lokasi", style: "width: auto" },
        { text: "Ruang Lab", style: "width: auto; white-space:nowrap;" },
        { text: "Kapasitas", style: "width: auto;" },
        { text: "Jenis Lab", style: "width: auto" },
        { text: "Jam Mulai", style: "width: auto; white-space:nowrap;" },
        { text: "Jam Selesai", style: "width: auto; white-space:nowrap;" },
        { text: "Status", style: "width: auto" },
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

function loadJadwalToDataTable() {
    $.fn.DataTable.ext.pager.numbers_length = 3;


    $('#tableLaboranJadwalGenerate').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '/laboran/jadwal/generate-jadwal',
            type: 'GET',
        },
        pageLength: 5,
        lengthMenu: [5, 10, 15, 20, 30],
        columns: [
            {
                data: null,
                name: 'index',
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Indexing Table
                }
            },
            { data: 'tanggal' },
            { data: 'lokasi' },
            { data: 'ruang_lab', className: 'min-desktop' },
            { data: 'kapasitas', className: 'none' },
            { data: 'jenis_lab', className: 'min-tablet' },
            { data: 'jam_mulai', className: 'min-desktop' },
            { data: 'jam_selesai', className: 'min-desktop' },
            {
                data: 'status',
                render: function(data) {
                    return data === 'dipesan'
                        ? '<span class="badge bg-danger">Dipesan</span>'
                        : '<span class="badge bg-success">Tersedia</span>';
                }
            }
        ],
        initComplete: function () {
            moveToolsLaboranGenerateJadwal();
        }
    });
}

function moveToolsLaboranGenerateJadwal() {
    const pengajuanTable = document.getElementById("tableLaboranJadwalGenerate").closest("#tableLaboranJadwalGenerate_wrapper");

    const search = pengajuanTable.querySelector(".dt-search");
    const sorting = pengajuanTable.querySelector(".dt-length");
    const info = pengajuanTable.querySelector(".dt-info");
    const paging = pengajuanTable.querySelector(".dt-paging");

    if (search && sorting && info && paging) {
        search.querySelector("input").placeholder = "Pencarian...";
        document.getElementById("searchLaboranJadwalGenerate").appendChild(search);
        document.getElementById("sortingLaboranJadwalGenerate").appendChild(sorting);
        document.getElementById("infoLaboranJadwalGenerate").appendChild(info);
        document.getElementById("pagingLaboranJadwalGenerate").appendChild(paging);
    } else {
        console.log("Tools Pengajuan Error");
    }
}

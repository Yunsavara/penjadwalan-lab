document.addEventListener("DOMContentLoaded", () => {
    initPengajuanDatatable();
})

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
                render: function(data, type, row, meta) {
                    return meta.row + 1; // Indexing Table
                }
            },
            { data: 'kode_pengajuan', name: 'kode_pengajuan' },
            { data: 'lab', name: 'lab' },
            { data: 'status', name: 'status' },
        ],
        initComplete: function() {
            moveTools();
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

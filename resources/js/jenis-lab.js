document.addEventListener("DOMContentLoaded", function () {
    initJenisLabDataTable();
});

function initJenisLabDataTable(){
    $(document).ready(function() {
        $.fn.DataTable.ext.pager.numbers_length = 3;
        // Inisialisasi DataTable
        let table = $('#jenisLabTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 50,
            paging: true,
            fixedHeader:true,
            responsive: true,
            ajax: {
                url: '/admin/jenis-lab/data',
                type: 'GET',
                data: function(d) {
                    // Parameter Lain kalau ada
                }
            },
            columns: [
                {
                    data: null,
                    name: 'index',
                    render: function(data, type, row, meta) {
                        return meta.row + 1; // Indexing Table
                    }
                },
                { data: 'name', name: 'name', },
                { data: 'description', name: 'description'},
            ],
            initComplete: function() {
                moveTools();
            }
        });
    });
}

function moveTools(){
    const search = document.querySelector(".dt-search");
    const sorting = document.querySelector(".dt-length");
    const info = document.querySelector(".dt-info");
    const paging = document.querySelector(".dt-paging");

    // Memberi Placeholder
    search.querySelector("input").placeholder = "Pencarian..."

    if(search && sorting && info && paging){
        document.getElementById("searchJenisLab").appendChild(search);
        document.getElementById("sortingJenisLab").appendChild(sorting);
        document.getElementById("infoJenisLab").appendChild(info);
        document.getElementById("pagingJenisLab").appendChild(paging);
    }else{
        console.log("Tools Error");
    }
}

document.addEventListener("DOMContentLoaded", function () {
    initLaboratoriumDataTable();
});

function initLaboratoriumDataTable() {
    $(document).ready(function() {
        $.fn.DataTable.ext.pager.numbers_length = 3;
        // Inisialisasi DataTable
        let table = $('#laboratoriumTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            paging: true,
            fixedHeader:true,
            responsive: true,
            ajax: {
                url: '/admin/laboratorium/laboratorium-data',
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
                { data: 'jenislab_name', name: 'jenislab_name'},
                { data: 'lokasi', lokasi: 'lokasi'},
                { data: 'kapasitas', kapasitas: 'kapasitas'},
                { data: 'status', status: 'status'},
                {
                    data: null,
                    name: 'action',
                    render: function(data, type, row) {
                        return `
                            <a href="/admin/ubah-laboratorium/${row.slug}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/admin/delete-laboratorium/${row.slug}" class="btn btn-danger btn-sm"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</a>
                        `;
                    }
                },
            ],
            initComplete: function() {
                moveTools();
            }
        });
    });
}

function moveTools(){
    // Mengambil Tools Datatables buat dipindahin nanti
    const search = document.querySelector(".dt-search");
    const sorting = document.querySelector(".dt-length");
    const info = document.querySelector(".dt-info");
    const paging = document.querySelector(".dt-paging");

    // Memberi Placeholder
    search.querySelector("input").placeholder = "Pencarian..."

    if(search && sorting && info && paging){
        // Dipindah ke sini
        document.getElementById("searchLaboratorium").appendChild(search);
        document.getElementById("sortingLaboratorium").appendChild(sorting);
        document.getElementById("infoLaboratorium").appendChild(info);
        document.getElementById("pagingLaboratorium").appendChild(paging);
    }else{
        console.log("Tools Error");
    }
}

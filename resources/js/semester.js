document.addEventListener("DOMContentLoaded", function () {
    initSemesterDataTable();
});

function initSemesterDataTable(){
    $(document).ready(function() {
        $.fn.DataTable.ext.pager.numbers_length = 3;
        // Inisialisasi DataTable
        let table = $('#semesterTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            paging: true,
            fixedHeader:true,
            responsive: true,
            ajax: {
                url: '/admin/semester/semester-data',
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
                { data: 'tanggal_mulai', name: 'tanggal_mulai'},
                { data: 'tanggal_akhir', name: 'tanggal_akhir'},
                { data: 'status', name: 'status'},
                {
                    data: null,
                    name: 'action',
                    render: function(data, type, row) {
                        return `
                            <a href="/admin/ubah-semester/${row.slug}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/admin/delete-semester/${row.slug}" class="btn btn-danger btn-sm"
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
        document.getElementById("searchSemester").appendChild(search);
        document.getElementById("sortingSemester").appendChild(sorting);
        document.getElementById("infoSemester").appendChild(info);
        document.getElementById("pagingSemester").appendChild(paging);
    }else{
        console.log("Tools Error");
    }
}

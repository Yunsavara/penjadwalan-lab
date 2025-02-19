document.addEventListener("DOMContentLoaded", function () {
    initMataKuliahDataTable();
});

function initMataKuliahDataTable(){
    $(document).ready(function() {
        $.fn.DataTable.ext.pager.numbers_length = 3;

        let table = $('#mataKuliahTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            paging: true,
            fixedHeader: true,
            responsive: true,
            ajax: {
                url: '/admin/mata-kuliah/mata-kuliah-data',
                type: 'GET',
            },
            columns: [
                {
                    data: null,
                    name: 'index',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name', name: 'name' },
                { data: 'dosen', name: 'dosen' },
                {
                    data: null,
                    name: 'action',
                    render: function(data, type, row) {
                        return `
                            <a href="/admin/ubah-mata-kuliah/${row.slug}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/admin/delete-mata-kuliah/${row.slug}" class="btn btn-danger btn-sm"
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
        document.getElementById("searchMataKuliah").appendChild(search);
        document.getElementById("sortingMataKuliah").appendChild(sorting);
        document.getElementById("infoMataKuliah").appendChild(info);
        document.getElementById("pagingMataKuliah").appendChild(paging);
    }else{
        console.log("Tools Error");
    }
}

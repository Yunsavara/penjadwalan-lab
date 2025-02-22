document.addEventListener("DOMContentLoaded", function () {
    initLaboratoriumDataTable();
});

function initLaboratoriumDataTable() {
    $(document).ready(function() {
        $.fn.DataTable.ext.pager.numbers_length = 3;
        // Inisialisasi DataTable
        let table = $('#pengajuanTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            paging: true,
            fixedHeader:true,
            responsive: true,
            ajax: {
                url: '/pengajuan-jadwal/pengajuan-jadwal-data',
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
                { data: 'kode_pengajuan', name: 'kode_pengajuan', },
                { data: 'lab_id', name: 'lab_id'},
                { data: 'status', status: 'status'},
                {
                    data: null,
                    name: 'action',
                    render: function(data, type, row) {
                        return `
                            <button type="button" class="btn btn-primary btn-sm" id="pengajuanDetailBtn" data-bs-toggle="modal" data-bs-target="#detailPengajuanModal" data-kode="${row.kode_pengajuan}"
                                data-keperluan="${row.keperluan}"
                                data-status="${row.status}"
                                data-lab="${row.lab_id}">Detail</button>
                            <a href="/pengajuan-jadwal/${row.kode_pengajuan}" class="btn btn-warning btn-sm">Edit</a>
                            <a href="/pengajuan-jadwal/${row.kode_pengajuan}" class="btn btn-danger btn-sm"
                               onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Batalkan</a>
                        `;
                    }
                },
            ],
            initComplete: function() {
                moveTools();
            }
        });

        // Event listener untuk mengisi modal secara langsung
        $('#pengajuanTable tbody').on('click', '#pengajuanDetailBtn', function() {
            let kodePengajuan = $(this).data('kode');

            // Set judul modal dengan kode_pengajuan
            $('#detailPengajuanLabel').text(`Detail Pengajuan : ${kodePengajuan}`);

            $.ajax({
                url: '/pengajuan-jadwal/detail/' + kodePengajuan,
                type: 'GET',
                success: function(response) {
                    let details = response.details;
                    let detailHtml = ``;

                    details.forEach(detail => {
                        detailHtml += `
                            <p><strong>Tanggal:</strong> ${detail.tanggal} |
                            <strong>Jam Mulai:</strong> ${detail.jam_mulai} |
                            <strong>Jam Selesai:</strong> ${detail.jam_selesai}</p>`;
                    });

                    $('.modal-body').html(detailHtml);
                    $('#detailPengajuanModal').modal('show');
                }
            });
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
        document.getElementById("searchPengajuan").appendChild(search);
        document.getElementById("sortingPengajuan").appendChild(sorting);
        document.getElementById("infoPengajuan").appendChild(info);
        document.getElementById("pagingPengajuan").appendChild(paging);
    }else{
        console.log("Tools Error");
    }
}

import './form-pengajuan-update'

document.addEventListener("DOMContentLoaded", function () {
    initLaboratoriumDataTable();
});

function initLaboratoriumDataTable() {
    $(document).ready(function() {
        $.fn.DataTable.ext.pager.numbers_length = 3;

        let table = $('#pengajuanTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            paging: true,
            fixedHeader: true,
            responsive: true,
            ajax: {
                url: '/pengajuan-jadwal/pengajuan-jadwal-data',
                type: 'GET',
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
                { data: 'lab_id', name: 'lab_id' },
                { data: 'status', name: 'status' },
                {
                    data: null,
                    name: 'action',
                    render: function(data, type, row) {
                        return `
                            <button type="button" class="btn btn-primary btn-sm btn-detail" data-bs-toggle="modal" data-bs-target="#detailPengajuanModal"
                                data-kode="${row.kode_pengajuan}">
                                Detail
                            </button>
                            <button type="button" class="btn btn-warning btn-sm btn-edit" data-kode="${row.kode_pengajuan}">Edit</button>
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

        // Event delegation untuk tombol Detail (agar tetap berfungsi setelah DataTable di-refresh)
        $(document).on('click', '.btn-detail', function() {
            let kodePengajuan = $(this).data('kode');

            // Set judul modal
            $('#detailPengajuanLabel').text(`Detail Pengajuan: ${kodePengajuan}`);

            $.ajax({
                url: `/pengajuan-jadwal/detail/${kodePengajuan}`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        let pengajuan = response.data.pengajuan;
                        let histories = response.data.histories;

                        let detailHtml = `
                            <p><strong>Kode Pengajuan:</strong> ${pengajuan.kode_pengajuan}</p>
                            <p><strong>Lab:</strong> ${pengajuan.laboratorium.name}</p>
                            <p><strong>Keperluan:</strong> ${pengajuan.keperluan}</p>

                            <h6><strong>Jadwal:</strong></h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jam Mulai</th>
                                        <th>Jam Selesai</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                        // Tambahkan daftar jadwal ke dalam tabel
                        if (pengajuan.jadwal.length > 0) {
                            pengajuan.jadwal.forEach(j => {
                                detailHtml += `
                                    <tr>
                                        <td>${j.tanggal}</td>
                                        <td>${j.jam_mulai}</td>
                                        <td>${j.jam_selesai}</td>
                                    </tr>`;
                            });
                        } else {
                            detailHtml += `<tr><td colspan="3" class="text-center text-muted">Tidak ada jadwal.</td></tr>`;
                        }

                        detailHtml += `</tbody></table>`;

                        detailHtml += `<h6><strong>Riwayat Status:</strong></h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Diubah Oleh</th>
                                        <th>Waktu Perubahan</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                        // Tambahkan riwayat status ke dalam tabel
                        if (histories.length > 0) {
                            histories.forEach(h => {
                                detailHtml += `
                                    <tr>
                                        <td>${h.tanggal}</td>
                                        <td>${h.jam_mulai} - ${h.jam_selesai}</td>
                                        <td>${h.status}</td>
                                        <td>${h.changed_by_name}</td>
                                        <td>${h.created_at}</td>
                                    </tr>`;
                            });
                        } else {
                            detailHtml += `<tr><td colspan="5" class="text-center text-muted">Tidak ada riwayat status.</td></tr>`;
                        }

                        detailHtml += `</tbody></table>`;

                        // Masukkan seluruh HTML ke dalam modal
                        $('.modal-body').html(detailHtml);


                    } else {
                        $('.modal-body').html('<p class="text-danger">Data tidak ditemukan!</p>');
                        $('#detailPengajuanModal').modal('show');
                    }
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                    $('.modal-body').html('<p class="text-danger">Terjadi kesalahan saat mengambil data.</p>');
                }
            });
        });

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

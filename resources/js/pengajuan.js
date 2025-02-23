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
                    // console.log("Response dari API:", response); // Debugging

                    if (response.success) {
                        let data = response.data;
                        let jadwal = data.jadwal;
                        let statusHistori = data.status_histori;

                        let detailHtml = `
                            <p><strong>Keperluan:</strong> ${data.keperluan}</p>
                            <p><strong>Lab ID:</strong> ${data.lab_id}</p>
                            <h6><strong>Jadwal:</strong></h6>`;

                        if (jadwal.length > 0) {
                            jadwal.forEach(item => {
                                detailHtml += `<p>- <strong>${item.tanggal}</strong> (${item.jam_mulai} - ${item.jam_selesai})</p>`;
                            });
                        } else {
                            detailHtml += `<p class="text-muted">Tidak ada jadwal.</p>`;
                        }

                        detailHtml += `<h6><strong>Riwayat Status:</strong></h6>`;
                        if (statusHistori.length > 0) {
                            statusHistori.forEach(status => {
                                detailHtml += `<p>- ${status.status} (${status.created_at})</p>`;
                            });
                        } else {
                            detailHtml += `<p class="text-muted">Tidak ada riwayat status.</p>`;
                        }

                        $('.modal-body').html(detailHtml);
                    } else {
                        $('.modal-body').html('<p class="text-danger">Data tidak ditemukan!</p>');
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

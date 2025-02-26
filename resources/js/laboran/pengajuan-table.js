document.addEventListener("DOMContentLoaded", function () {
    initLaboranPengajuanDataTable();
    modalStatusDiterima();
});

function initLaboranPengajuanDataTable() {
    $(document).ready(function() {
        $.fn.DataTable.ext.pager.numbers_length = 3;

        let table = $('#pengajuanLaboranTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            paging: true,
            fixedHeader: true,
            responsive: true,
            ajax: {
                url: '/laboran/pengajuan-jadwal/pengajuan-jadwal-data',
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
                    render: function (data, type, row) {
                        let buttons = `
                            <div class="d-flex flex-wrap align-items-center gap-1">
                                <button type="button" class="btn btn-primary btn-sm btn-detail" data-bs-toggle="modal" data-bs-target="#detailPengajuanModal"
                                data-kode="${row.kode_pengajuan}">
                                Detail
                                </button>
                        `;

                        // Tampilkan dropdown status hanya jika statusnya masih "pending"
                        if (row.status === "pending") {
                            buttons += `
                                <div class="dropdown dropstart">
                                    <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Status
                                    </button>
                                    <ul class="dropdown-menu p-0">
                                        <li>
                                            <button class="dropdown-item ubah-status" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi"
                                                data-kode="${row.kode_pengajuan}" data-status="diterima">
                                                Terima
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item ubah-status" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi"
                                                data-kode="${row.kode_pengajuan}" data-status="ditolak">
                                                Tolak
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item ubah-status" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi"
                                                data-kode="${row.kode_pengajuan}" data-status="dibatalkan">
                                                Batalkan
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            `;
                        }

                        buttons += `</div>`; // Tutup wrapper div utama
                        return buttons;
                    }
                },
            ],
            initComplete: function() {
                moveTools();
            }
        });

        // Event delegation untuk tombol Detail
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

function modalStatusDiterima(){
    let modalKonfirmasi = document.getElementById("modalKonfirmasi");

    modalKonfirmasi.addEventListener("show.bs.modal", function (event) {
        let button = event.relatedTarget;
        let kodePengajuan = button.getAttribute("data-kode");
        let statusBaru = button.getAttribute("data-status");

        let modalText = document.getElementById("konfirmasiText");
        let inputKode = document.getElementById("kodePengajuanInput");
        let inputStatus = document.getElementById("statusPengajuanInput");

        modalText.innerHTML = `Apakah Anda yakin ingin mengubah status menjadi <strong>${statusBaru}</strong>?`;
        inputKode.value = kodePengajuan;
        inputStatus.value = statusBaru;
    });
}

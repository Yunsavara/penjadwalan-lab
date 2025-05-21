import DataTable from 'datatables.net';
import 'datatables.net-responsive-bs5';
import 'datatables.net-fixedheader-bs5';
import 'datatables.net-select';

export function initPengajuanBookingDatatable() {
    const table = new DataTable("#tablePengajuanBooking", {
        serverSide: true,
        processing: true,
        responsive: true,
        fixedHeader: false,
        ajax: {
            url: "/pengajuan/api/data-pengajuan-booking",
            method: "GET"
        },
        columns: [
            {
                title: "",
                render: DataTable.render.select(),
                orderable: false,
                data: null,
                className: "min-mobile text-nowrap"
            },
            {
                title: "No",
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                className: "min-mobile text-center align-middle",
                orderable: false,
                width: "1rem"
            },
            {
                title: "No",
                data: "id_pengajuan_booking",
                visible: false,
            },
            {
                title: "Kode Booking",
                data: "kode_booking",
                className: "min-mobile text-nowrap align-middle"
            },
            {
                title: "Status",
                data: "status_pengajuan_booking",
                className: "text-nowrap align-middle",
            },
            {
                title: "Aksi",
                data: null,
                orderable: false,
                searchable: false,
                className: 'min-tablet text-md-center',
                render: function (data, type, row) {
                    return `
                        <div class="dropdown">
                            <button class="btn btn-sm btn-success border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Aksi
                            </button>
                            <ul class="dropdown-menu p-0">
                                <li><a href="/pengajuan/ubah-pengajuan-booking/${row.id_pengajuan_booking}" class="dropdown-item">Ubah</a></li>
                                <li><button class="dropdown-item text-danger btn-batalkan-pengajuan-booking" data-id="${row.id_pengajuan_booking}" data-kode-booking="${row.kode_booking}" data-bs-toggle="modal" data-bs-target="#batalkanPengajuanBookingModal">Batalkan</button></li>
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        order: [[2, 'desc']], // Ini bukan dari index kolom javascript tapi dari backend
        select: {
            style: "multi+shift",
            selector: "td:first-child"
        },
        initComplete: function () {
            moveToolsPengajuanBooking();
        },
        drawCallback: function (settings) {
            const thElements = document.querySelectorAll('#tablePengajuanBooking th');
            thElements.forEach(th => {
                th.classList.add('table-white', 'text-nowrap', 'text-center');
            });
        }
    });
} 

function moveToolsPengajuanBooking() {
    const wrapper = document.getElementById("tablePengajuanBooking").closest("#tablePengajuanBooking_wrapper");

    const search = wrapper.querySelector(".dt-search");
    const length = wrapper.querySelector(".dt-length");
    const info = wrapper.querySelector(".dt-info");
    const paging = wrapper.querySelector(".dt-paging");

    if (search && length && info && paging) {
        const input = search.querySelector("input");
        if (input) input.placeholder = "Pencarian...";

        document.getElementById("searchPengajuanBooking").appendChild(search);
        document.getElementById("sortingPengajuanBooking").appendChild(length);
        document.getElementById("infoPengajuanBooking").appendChild(info);
        document.getElementById("pagingPengajuanBooking").appendChild(paging);
    } else {
        console.warn("Tools Pengguna Error: Beberapa elemen tidak ditemukan.");
    }
}
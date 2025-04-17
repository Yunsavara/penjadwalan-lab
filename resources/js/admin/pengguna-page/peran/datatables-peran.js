import DataTable from 'datatables.net';

export function initPeranDatatable() {
    const table = new DataTable("#tablePeran", {
        serverSide: true,
        processing: true,
        responsive: true,
        fixedHeader: false,
        ajax: {
            url: "/admin/api/data-peran",
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
                data: "id_peran",
                visible: false,
            },
            {
                title: "Nama Peran",
                data: "nama_peran",
                className: "min-mobile text-nowrap align-middle"
            },
            {
                title: "Prioritas",
                data: "prioritas_peran",
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
                                <li><button class="dropdown-item btn-edit-peran" data-row='${JSON.stringify(row)}'>Ubah</button></li>
                                <li><button class="dropdown-item text-danger btn-delete-peran" data-row='${JSON.stringify(row)}'>Hapus</button></li>
                            </ul>
                        </div>
                    `;
                }
            }
        ],
        // fixedColumns: {
        //     start: 3
        // },
        order: [[2, 'desc']], // Ini bukan dari index kolom javascript tapi dari backend
        select: {
            style: "multi+shift",
            selector: "td:first-child"
        },
        initComplete: function () {
            moveToolsPeran();
        },
        drawCallback: function (settings) {
            const thElements = document.querySelectorAll('#tablePeran th');
            thElements.forEach(th => {
                th.classList.add('table-white', 'text-nowrap', 'text-center');
            });
        }
    });
}

function moveToolsPeran() {
    const wrapper = document.getElementById("tablePeran").closest("#tablePeran_wrapper");

    const search = wrapper.querySelector(".dt-search");
    const length = wrapper.querySelector(".dt-length");
    const info = wrapper.querySelector(".dt-info");
    const paging = wrapper.querySelector(".dt-paging");

    if (search && length && info && paging) {
        const input = search.querySelector("input");
        if (input) input.placeholder = "Pencarian...";

        document.getElementById("searchPeran").appendChild(search);
        document.getElementById("sortingPeran").appendChild(length);
        document.getElementById("infoPeran").appendChild(info);
        document.getElementById("pagingPeran").appendChild(paging);
    } else {
        console.warn("Tools Peran Error: Beberapa elemen tidak ditemukan.");
    }
}

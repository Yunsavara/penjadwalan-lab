import DataTable from 'datatables.net';

export function initPeranDatatable() {
    const table = new DataTable("#tablePeran", {
        serverSide: true,
        processing: true,
        responsive: true,
        fixedHeader: true,
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
            },
            {
                title: "No",
                data: "index",
                orderable: false
            },
            {
                title: "Nama Peran",
                data: "name"
            },
            {
                title: "Prioritas",
                data: "priority",
                className: "text-start"
            },
            {
                title: "Aksi",
                data: null,
                orderable: false,
                searchable: false,
                className: 'min-tablet',
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
        order: [[2, 'desc']],
        select: {
            style: "multi+shift",
            selector: "td:first-child"
        },
        columnDefs: [
            { targets: 0, width: "1%" }
        ],
        initComplete: function () {
            moveToolsPeran();
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

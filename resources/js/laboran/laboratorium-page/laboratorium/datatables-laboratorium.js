import DataTable from 'datatables.net';

export function initLaboratoriumDatatable() {
    const table = new DataTable("#tableLaboratorium", {
        serverSide: true,
        processing: true,
        responsive: true,
        fixedHeader: true,
        ajax: {
            url: "/laboran/api/data-laboratorium",
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
                title: "Ruang Lab",
                data: "name"
            },
            {
                title: "Kapasitas",
                data: "kapasitas",
                className: "text-start"
            },
            {
                title: "Status",
                data: "status",
                render: function (data) {
                    if (!data) return '';
                    return data.charAt(0).toUpperCase() + data.slice(1);
                }
            },
            {
                title: "Lokasi",
                data: "lokasi_name"
            },
            {
                title: "Jenis",
                data: "jenislab_name"
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
                                <li><button class="dropdown-item btn-edit-laboratorium" data-row='${JSON.stringify(row)}'>Ubah</button></li>
                                <li><button class="dropdown-item text-danger btn-delete-laboratorium" data-row='${JSON.stringify(row)}'>Hapus</button></li>
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
            moveToolsLaboratorium();
        }
    });
}

function moveToolsLaboratorium() {
    const wrapper = document.getElementById("tableLaboratorium").closest("#tableLaboratorium_wrapper");

    const search = wrapper.querySelector(".dt-search");
    const length = wrapper.querySelector(".dt-length");
    const info = wrapper.querySelector(".dt-info");
    const paging = wrapper.querySelector(".dt-paging");

    if (search && length && info && paging) {
        const input = search.querySelector("input");
        if (input) input.placeholder = "Pencarian...";

        document.getElementById("searchLaboratorium").appendChild(search);
        document.getElementById("sortingLaboratorium").appendChild(length);
        document.getElementById("infoLaboratorium").appendChild(info);
        document.getElementById("pagingLaboratorium").appendChild(paging);
    } else {
        console.warn("Tools Laboratorium Error: Beberapa elemen tidak ditemukan.");
    }
}

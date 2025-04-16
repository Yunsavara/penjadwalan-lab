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
                data: "id_laboratorium",
                visible: false,
            },
            {
                title: "Ruang Lab",
                data: "nama_laboratorium",
                className: "min-mobile text-nowrap align-middle"
            },
            {
                title: "Kapasitas",
                data: "kapasitas_laboratorium",
                className: "text-start text-nowrap align-middle"
            },
            {
                title: "Status",
                data: "status_laboratorium",
                render: function (data) {
                    if (!data) return '';
                    return data.charAt(0).toUpperCase() + data.slice(1);
                },
                className: "text-nowrap align-middle"
            },
            {
                title: "Lokasi",
                data: "nama_lokasi",
                className: "text-nowrap align-middle"
            },
            {
                title: "Jenis",
                data: "nama_jenislab",
                className: "text-nowrap align-middle"
            },
            {
                title: "Deskripsi",
                data: "deskripsi_laboratorium",
                className: "text-wrap align-middle"
            }
            ,
            {
                title: "Aksi",
                data: null,
                orderable: false,
                searchable: false,
                className: 'min-tablet text-md-center align-middle',
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
        initComplete: function () {
            moveToolsLaboratorium();
        },
        drawCallback: function (settings) {
            const thElements = document.querySelectorAll('#tableLaboratorium th');
            thElements.forEach(th => {
                th.classList.add('table-white', 'text-nowrap', 'text-center');
            });
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

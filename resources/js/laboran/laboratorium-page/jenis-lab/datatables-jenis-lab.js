import DataTable from 'datatables.net';

export function initJenisLaboratoriumDatatable() {
    const table = new DataTable("#tableJenisLaboratorium", {
        serverSide: true,
        processing: true,
        responsive: true,
        fixedHeader: true,
        ajax: {
            url: "/laboran/api/data-jenis-laboratorium",
            method: "GET"
        },
        columns: [
            {
                title: "",
                render: DataTable.render.select(),
                orderable: false,
                data: null,
                className: "min-mobile"
            },
            {
                title: "No",
                data: "index",
                orderable: false,
                className: "min-mobile"
            },
            {
                title: "Nama Jenis Lab",
                data: "name",
                className: "min-mobile"
            },
            {
                title: "Deskripsi",
                data: "description",
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
                                <li><button class="dropdown-item btn-edit-jenis-lab" data-row='${JSON.stringify(row)}'>Ubah</button></li>
                                <li><button class="dropdown-item text-danger btn-delete-jenis-lab" data-row='${JSON.stringify(row)}'>Hapus</button></li>
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
            moveToolsJenisLaboratorium();
        }
    });
}

function moveToolsJenisLaboratorium() {
    const wrapper = document.getElementById("tableJenisLaboratorium").closest("#tableJenisLaboratorium_wrapper");

    const search = wrapper.querySelector(".dt-search");
    const length = wrapper.querySelector(".dt-length");
    const info = wrapper.querySelector(".dt-info");
    const paging = wrapper.querySelector(".dt-paging");

    if (search && length && info && paging) {
        const input = search.querySelector("input");
        if (input) input.placeholder = "Pencarian...";

        document.getElementById("searchJenisLaboratorium").appendChild(search);
        document.getElementById("sortingJenisLaboratorium").appendChild(length);
        document.getElementById("infoJenisLaboratorium").appendChild(info);
        document.getElementById("pagingJenisLaboratorium").appendChild(paging);
    } else {
        console.warn("Tools Jenis Laboratorium Error: Beberapa elemen tidak ditemukan.");
    }
}

import DataTable from 'datatables.net';
import 'datatables.net-fixedcolumns';
import 'datatables.net-fixedcolumns-bs5';

document.addEventListener("DOMContentLoaded", () => {
    initPeranDatatable();
});

function initPeranDatatable() {
    const table = new DataTable("#tablePeran", {
        serverSide: true,
        processing: true,
        responsive: true,
        ajax: {
            url: "/laboran/api/data-peran",
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
                data: "priority"
            }
        ],
        // fixedColumns: {
        //     start: 3
        // },
        order: [[2, 'asc']],
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

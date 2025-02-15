document.addEventListener("DOMContentLoaded", function () {
    // Buat opsi number di pagination biar gk kepanjangan buat responsive
    $.fn.DataTable.ext.pager.numbers_length = 3;

    let table = $("#jenislab-table").on("draw.dt", function () {
        // console.log("Event draw.dt dipanggil");

        let info = document.getElementById("jenislab-table_info");
        let pagination = document.querySelector(".dt-paging");

        // console.log("Info Element:", info);
        // console.log("Pagination Element:", pagination);

        if (info || pagination) {
            document.getElementById("infoJenisLab").appendChild(info);
            document.getElementById("paginationJenisLab").appendChild(pagination);
        }
    });
});

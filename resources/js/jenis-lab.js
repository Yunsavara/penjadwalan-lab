document.addEventListener("DOMContentLoaded", function () {
    initTableJenisLab();
});

function initTableJenisLab(){
    // Buat opsi number di pagination biar gk kepanjangan buat responsive
    $.fn.DataTable.ext.pager.numbers_length = 3;

    let table = $("#jenislab-table").on("draw.dt", function () {

        let sorting = document.querySelector(".dt-length");
        let search = document.querySelector(".dt-search");
        let info = document.getElementById("jenislab-table_info");
        let pagination = document.querySelector(".dt-paging");

        // Placeholder
        search.querySelector("input").placeholder = "Pencarian..."

        if (info && pagination && search && sorting) {
            document.getElementById("sortingJenisLab").appendChild(sorting);
            document.getElementById("searchJenisLab").appendChild(search);
            document.getElementById("infoJenisLab").appendChild(info);
            document.getElementById("paginationJenisLab").appendChild(pagination);
        }
    });
}

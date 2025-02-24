document.addEventListener("DOMContentLoaded", function () {
    let currentPage = 0;
    const editContainer = document.getElementById("editPengajuanContainer");
    const editForm = document.getElementById("updatePengajuanForm");
    const editTanggalContainer = document.getElementById("editTanggalInputs");
    const editPrevButton = document.getElementById("editPrevTanggal");
    const editNextButton = document.getElementById("editNextTanggal");
    const editAddButton = document.getElementById("editAddTanggal");
    const editRemoveButton = document.getElementById("editRemoveTanggal");
    const cancelEditButton = document.getElementById("cancelEdit");

    function updatePagination() {
        let allPages = document.querySelectorAll(".edit-tanggal-input");
        allPages.forEach((page, index) => {
            page.style.display = index === currentPage ? "block" : "none";
        });

        editPrevButton.disabled = currentPage === 0;
        editNextButton.disabled = currentPage === allPages.length - 1;
        editRemoveButton.disabled = allPages.length <= 1;
    }

    // Event listener tombol Edit dari DataTables
    $(document).on("click", ".btn-edit", function () {
        let kodePengajuan = $(this).data("kode");

        $.ajax({
            url: `/pengajuan-jadwal/edit/${kodePengajuan}`,
            type: "GET",
            dataType: "json",
            success: function (response) {
                // console.log("Response Data:", response); // Debugging

                if (!response || !response.success || !Array.isArray(response.data) || response.data.length === 0) {
                    alert("Data tidak ditemukan!");
                    return;
                }

                let pengajuan = response.data[0]; // Ambil baris pertama sebagai data pengajuan
                let jadwal = response.data; // Semua data dianggap sebagai jadwal

                if (!pengajuan) {
                    alert("Pengajuan tidak ditemukan!");
                    return;
                }

                // Inject Form Action
                let form = document.getElementById("updatePengajuanForm");
                form.action = `/pengajuan-jadwal/edit/${pengajuan.kode_pengajuan}`;

                // Set nilai input dari data pengajuan
                document.getElementById("editKodePengajuan").value = pengajuan.kode_pengajuan || "";
                document.getElementById("editPilihRuangan").value = pengajuan.lab_id || "";
                document.getElementById("editKeperluanPengajuan").value = pengajuan.keperluan || "";

                editTanggalContainer.innerHTML = "";

                jadwal.forEach((j) => {
                    let newTanggalInput = document.createElement("div");
                    newTanggalInput.classList.add("edit-tanggal-input");
                    newTanggalInput.innerHTML = `
                        <input type="date" name="tanggal_pengajuan[]" class="form-control mb-2" value="${j.tanggal || ""}" required>
                        <input type="time" name="jam_mulai[]" class="form-control mb-2" value="${j.jam_mulai || ""}" required>
                        <input type="time" name="jam_selesai[]" class="form-control mb-2" value="${j.jam_selesai || ""}" required>
                    `;
                    editTanggalContainer.appendChild(newTanggalInput);
                });

                currentPage = 0;
                updatePagination();

                editContainer.classList.remove("d-none");
            },
            error: function (xhr) {
                console.error("AJAX Error:", xhr.responseText);
                alert("Terjadi kesalahan saat mengambil data.");
            },
        });
    });

    editAddButton.addEventListener("click", function () {
        let newTanggalInput = document.createElement("div");
        newTanggalInput.classList.add("edit-tanggal-input");
        newTanggalInput.innerHTML = `
            <input type="date" name="tanggal_pengajuan[]" class="form-control mb-2" required>
            <input type="time" name="jam_mulai[]" class="form-control mb-2" required>
            <input type="time" name="jam_selesai[]" class="form-control mb-2" required>
        `;
        editTanggalContainer.appendChild(newTanggalInput);
        currentPage = document.querySelectorAll(".edit-tanggal-input").length - 1;
        updatePagination();
    });

    editPrevButton.addEventListener("click", function () {
        if (currentPage > 0) {
            currentPage--;
            updatePagination();
        }
    });

    editNextButton.addEventListener("click", function () {
        if (currentPage < document.querySelectorAll(".edit-tanggal-input").length - 1) {
            currentPage++;
            updatePagination();
        }
    });

    editRemoveButton.addEventListener("click", function () {
        let allPages = document.querySelectorAll(".edit-tanggal-input");
        if (allPages.length > 1) {
            allPages[currentPage].remove();
            currentPage = Math.max(0, currentPage - 1);
            updatePagination();
        }
    });

    cancelEditButton.addEventListener("click", function () {
        editContainer.classList.add("d-none");
        editForm.reset();
        editTanggalContainer.innerHTML = "";
    });

    updatePagination();
});

document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#tanggalBooking", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        locale: "id",
        onChange: function (selectedDates, dateStr, instance) {
            daftarTanggal = selectedDates.map(date => {
                let formattedDate = formatTanggal(date);
                let dateValue = date.toISOString().split("T")[0];
                return { formattedDate, dateValue };
            });
            jamData = {}; // Reset data jam
            showPage(1);
        }
    });

    const jamContainer = document.getElementById("jamContainer");
    const paginationContainer = document.getElementById("paginationControls");

    let daftarTanggal = []; // Menyimpan daftar tanggal
    let jamData = {}; // Menyimpan nilai input jam
    let currentPage = 1;
    const itemsPerPage = 1; // Tampilkan 1 tanggal per halaman

    function formatTanggal(date) {
        return new Intl.DateTimeFormat("id-ID", {
            day: "numeric",
            month: "long",
            year: "numeric"
        }).format(date);
    }

    function showPage(page) {
        jamContainer.innerHTML = "";
        let start = (page - 1) * itemsPerPage;
        let end = start + itemsPerPage;
        let itemsToShow = daftarTanggal.slice(start, end);

        itemsToShow.forEach(({ formattedDate, dateValue }) => {
            let inputGroup = document.createElement("div");
            inputGroup.classList.add("col-12", "mb-0");
            inputGroup.innerHTML = `
                <label class="fw-bold">Tanggal: ${formattedDate}</label>
                <div class="d-flex flex-wrap justify-content-md-between align-items-center">
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <label for="jamMulai_${dateValue}">Jam Mulai</label>
                        <input type="time" name="jam_mulai[${dateValue}]" id="jamMulai_${dateValue}" class="form-control" value="${jamData[dateValue]?.jamMulai || ""}">
                    </div>
                    <div class="col-12 col-md-5 mb-2 mb-md-0">
                        <label for="jamSelesai_${dateValue}">Jam Selesai</label>
                        <input type="time" name="jam_selesai[${dateValue}]" id="jamSelesai_${dateValue}" class="form-control" value="${jamData[dateValue]?.jamSelesai || ""}">
                    </div>
                </div>
            `;
            jamContainer.appendChild(inputGroup);

            // Tambahkan event listener untuk menyimpan nilai saat diubah
            document.getElementById(`jamMulai_${dateValue}`).addEventListener("input", (e) => {
                jamData[dateValue] = jamData[dateValue] || {};
                jamData[dateValue].jamMulai = e.target.value;
            });

            document.getElementById(`jamSelesai_${dateValue}`).addEventListener("input", (e) => {
                jamData[dateValue] = jamData[dateValue] || {};
                jamData[dateValue].jamSelesai = e.target.value;
            });
        });

        updatePagination(page);
    }

    function updatePagination(page) {
        paginationContainer.innerHTML = "";
        let totalPages = Math.ceil(daftarTanggal.length / itemsPerPage);

        if (totalPages > 1) {
            let firstButton = document.createElement("button");
            firstButton.innerHTML = `<i data-feather="chevrons-left"></i>`;
            firstButton.classList.add("btn", "btn-secondary", "me-2");
            firstButton.disabled = page === 1;
            firstButton.addEventListener("click", () => {
                currentPage = 1;
                showPage(currentPage);
            });

            let prevButton = document.createElement("button");
            prevButton.innerHTML = `<i data-feather="arrow-left"></i>`;
            prevButton.classList.add("btn", "btn-secondary", "me-2");
            prevButton.disabled = page === 1;
            prevButton.addEventListener("click", () => {
                if (currentPage > 1) {
                    currentPage--;
                    showPage(currentPage);
                }
            });

            let nextButton = document.createElement("button");
            nextButton.innerHTML = `<i data-feather="arrow-right"></i>`;
            nextButton.classList.add("btn", "btn-secondary", "me-2");
            nextButton.disabled = page === totalPages;
            nextButton.addEventListener("click", () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    showPage(currentPage);
                }
            });

            let lastButton = document.createElement("button");
            lastButton.innerHTML = `<i data-feather="chevrons-right"></i>`;
            lastButton.classList.add("btn", "btn-secondary");
            lastButton.disabled = page === totalPages;
            lastButton.addEventListener("click", () => {
                currentPage = totalPages;
                showPage(currentPage);
            });

            paginationContainer.appendChild(firstButton);
            paginationContainer.appendChild(prevButton);
            paginationContainer.appendChild(nextButton);
            paginationContainer.appendChild(lastButton);

            feather.replace();
        }
    }
});

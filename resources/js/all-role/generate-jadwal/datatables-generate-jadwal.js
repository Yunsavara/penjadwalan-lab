document.addEventListener("DOMContentLoaded", async () => {
    loadJadwalToDataTable();
});

function loadJadwalToDataTable() {
    $('#jadwalGenerateTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/jadwal/generate-jadwal',
            type: 'GET',
        },
        pageLength: 5,
        lengthMenu: [5, 10, 15, 20, 30],
        columns: [
            {
                data: null,
                name: 'index',
                render: function (data, type, row, meta) {
                    return meta.row + 1; // Indexing Table
                }
            },
            { data: 'tanggal' },
            { data: 'lokasi' },
            { data: 'ruang_lab' },
            { data: 'jenis_lab' },
            { data: 'jam_mulai' },
            { data: 'jam_selesai' },
            {
                data: 'status',
                render: function(data) {
                    return data === 'dipesan'
                        ? '<span class="badge bg-danger">Dipesan</span>'
                        : '<span class="badge bg-success">Tersedia</span>';
                }
            }
        ]
    });
}

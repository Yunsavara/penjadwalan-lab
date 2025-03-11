document.addEventListener("DOMContentLoaded", async () => {
    const data = await fetchJadwalOperasional();
    generateJadwal(data.waktu_operasional, data.laboratorium, data.bookings);
});

async function fetchJadwalOperasional() {
    try {
        let response = await fetch("/jadwal/generate-jadwal");
        let json = await response.json();
        return json;
    } catch (error) {
        console.error("Gagal mengambil data:", error);
        return { waktu_operasional: [], laboratorium: [], bookings: [] };
    }
}

function generateJadwal(waktuOperasionals, laboratorium, bookings) {
    let jadwal = [];
    let today = new Date();
    let endOfYear = new Date(today.getFullYear(), 11, 31); // Akhir tahun

    for (let d = new Date(today); d <= endOfYear; d.setDate(d.getDate() + 1)) {
        let hari = d.toLocaleDateString('id-ID', { weekday: 'long' });
        let tanggal = d.toISOString().split('T')[0];

        waktuOperasionals.forEach(waktu => {
            if (waktu.hari_operasional.includes(hari)) {
                let lokasi = waktu.lokasi.name;
                let labs = laboratorium.filter(lab => lab.lokasi_id === waktu.lokasi.id);

                labs.forEach(lab => {
                    let bookedSlots = bookings
                        .filter(b => b.lab_id === lab.id && b.tanggal === tanggal)
                        .map(b => ({
                            jam_mulai: b.jam_mulai.substring(0, 5),
                            jam_selesai: b.jam_selesai.substring(0, 5)
                        }));

                    bookedSlots.sort((a, b) => a.jam_mulai.localeCompare(b.jam_mulai));

                    let startTime = waktu.jam_mulai.substring(0, 5);
                    let endTime = waktu.jam_selesai.substring(0, 5);

                    bookedSlots.forEach(booked => {
                        if (startTime < booked.jam_mulai) {
                            jadwal.push({
                                tanggal: tanggal,
                                lokasi: lokasi,
                                ruang_lab: lab.name,
                                jenis_lab: lab.jenislab.name,
                                jam_mulai: startTime,
                                jam_selesai: booked.jam_mulai,
                                status: "tersedia"
                            });
                        }

                        jadwal.push({
                            tanggal: tanggal,
                            lokasi: lokasi,
                            ruang_lab: lab.name,
                            jenis_lab: lab.jenislab.name,
                            jam_mulai: booked.jam_mulai,
                            jam_selesai: booked.jam_selesai,
                            status: "dipesan"
                        });

                        startTime = booked.jam_selesai;
                    });

                    if (startTime < endTime) {
                        jadwal.push({
                            tanggal: tanggal,
                            lokasi: lokasi,
                            ruang_lab: lab.name,
                            jenis_lab: lab.jenislab.name,
                            jam_mulai: startTime,
                            jam_selesai: endTime,
                            status: "tersedia"
                        });
                    }
                });
            }
        });
    }

    loadJadwalToDataTable(jadwal);
}

function loadJadwalToDataTable(jadwal) {
    $('#jadwalGenerateTable').DataTable({
        data: jadwal,
        destroy: true,
        columns: [
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

// Auto-refresh DataTables setiap 5 menit (300000 ms)
setInterval(async () => {
    console.log("Refreshing jadwal...");
    const data = await fetchJadwalOperasional();
    generateJadwal(data.waktu_operasional, data.laboratorium, data.bookings);
}, 300000);

const table = document.getElementById('tableProsesPengajuan');

export function detailProsesPengajuan() {
    table.addEventListener('click', async function (event) {
        if(event.target.classList.contains('btn-detail-proses-pengajuan')) {
            const id = event.target.getAttribute('data-id');

            try {

                const response = await fetch(`/laboran/api/detail-proses-pengajuan-booking/${id}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();

                if (data) {
                    tampilkanDetailPengajuan(data);
                }

                // console.log(JSON.stringify(data, null, 2));

            } catch (error) {
                console.error("Gagal Memproses Data :", error);
            }
        }
    })
}

function tampilkanDetailPengajuan(data) {

    const { pengajuan_booking, user, laboratorium_unpam, jadwal_booking } = data;

    const kodeBooking       = document.getElementById('detailProsesPengajuanModalLabel');
    const namaEmailPengguna = document.getElementById('detailProsesPengajuanOleh');
    const prioritasPengguna = document.getElementById('detailProsesPengajuanPrioritas');
    const namaLaboratorium  = document.getElementById('detailProsesPengajuanLaboratorium');
    const keperluanBooking  = document.getElementById('detailProsesPengajuanKeperluan');
    const statusBooking     = document.getElementById('detailProsesPengajuanStatus');
    const tanggalJadwal     = document.getElementById('detailProsesPengajuanTanggalJam');

    const namaLaboratoriumUnik = [...new Set(laboratorium_unpam.map(lab => lab.nama_laboratorium))].join(', ');
    const jadwalByTanggal = {};

    jadwal_booking.forEach(jadwal => {
        const tanggal = new Date(jadwal.tanggal_jadwal);
        const tanggalFormatted = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }).format(tanggal);

        const jam = `${jadwal.jam_mulai} - ${jadwal.jam_selesai}`;
        
        if (!jadwalByTanggal[tanggalFormatted]) {
            jadwalByTanggal[tanggalFormatted] = [];
        }
        jadwalByTanggal[tanggalFormatted].push(jam);
    });

    const tanggalDanJamFormatted = Object.entries(jadwalByTanggal).map(([tanggal, jamList]) => `\n${tanggal} : ${jamList.join(', ')}`).join('');

    kodeBooking.innerText = `${pengajuan_booking.kode_booking}`;
    namaEmailPengguna.innerText = `Pengajuan Oleh :\n ${user.nama_pengguna} (${user.email_pengguna})`;
    prioritasPengguna.innerText = `Prioritas :\n ${user.prioritas} (${user.nama_peran})`;
    namaLaboratorium.innerText = `Laboratorium :\n ${namaLaboratoriumUnik}`;
    keperluanBooking.innerText = `Keperluan :\n ${pengajuan_booking.keperluan_pengajuan_booking}`;
    statusBooking.innerText = `Status :\n ${pengajuan_booking.status_pengajuan_booking}`;
    tanggalJadwal.innerText = `Tanggal dan Jam: ${tanggalDanJamFormatted}`;
}

export function terimaProsesPengajuan() {
    const alasanWrapper = document.getElementById('alasanPenolakanContainer');
    const radios = document.querySelectorAll('input[name="mode_terima"]');

    table.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-terima-proses-pengajuan')) {
            const id = event.target.getAttribute('data-id');
            const kodeBooking = event.target.getAttribute('data-kode-booking');
            const terimaBody = document.getElementById('terimaProsesPengajuanBody');
            const formTerimaProsesPengajuan = document.getElementById('formTerimaProsesPengajuan');

            terimaBody.innerText = `Terima Pengajuan Kode Booking :\n${kodeBooking} ?`;
            formTerimaProsesPengajuan.setAttribute('action', `/laboran/terima-proses-pengajuan-booking/${id}`);
        }
    });

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'otomatis') {
                alasanWrapper.style.display = 'block';
            } else {
                alasanWrapper.style.display = 'none';
                document.getElementById('alasanPenolakan').value = '';
            }
        });
    });
}

export function tolakProsesPengajuan() {
    table.addEventListener('click', function(event) {
        if(event.target.classList.contains('btn-tolak-proses-pengajuan')) {
            const id = event.target.getAttribute('data-id');
            const kodeBooking = event.target.getAttribute('data-kode-booking');
            const tolakBody = document.getElementById('tolakProsesPengajuanBody');
            const formTolakProsesPengajuan = document.getElementById('formTolakProsesPengajuan');

            tolakBody.innerText = `Tolak Pengajuan Kode Booking :\n${kodeBooking} ?`;
            formTolakProsesPengajuan.setAttribute('action', `/laboran/tolak-proses-pengajuan-booking/${id}`);
        }
    });
}

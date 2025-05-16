export function batalkanPengajuanBooking() {
    const table = document.getElementById('tablePengajuanBooking');
    table.addEventListener('click', function(event) {
        if(event.target.classList.contains('btn-batalkan-pengajuan-booking')) {
            const id = event.target.getAttribute('data-id');
            const kodeBooking = event.target.getAttribute('data-kode-booking');
            const batalkanBody = document.getElementById('batalkanPengajuanBookingBody');
            const formBatalkanPengajuanBooking = document.getElementById('formBatalkanPengajuanBooking');

            if(kodeBooking){
                console.log('Halo');
            }

            batalkanBody.innerText = `Batalkan Pengajuan Kode Booking :\n${kodeBooking} ?`;
            formBatalkanPengajuanBooking.setAttribute('action', `/pengajuan/batalkan-pengajuan-booking/${id}`);
        }
    });
}
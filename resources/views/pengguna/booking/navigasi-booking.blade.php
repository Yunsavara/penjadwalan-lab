<nav>
    <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
        <button class="nav-link active border-0 col-12 col-md-auto"
            id="tab-pengajuan-booking-tab"
            data-bs-toggle="tab"
            data-bs-target="#tab-pengajuan-booking"
            type="button"
            role="tab"
            aria-controls="tab-pengajuan-booking"
            aria-selected="true">
            Pengajuan Booking
        </button>
        <button class="nav-link border-0 col-12 col-md-auto"
            id="tab-log-tab"
            data-bs-toggle="tab"
            data-bs-target="#tab-log"
            type="button"
            role="tab"
            aria-controls="tab-log"
            aria-selected="false">
            Log
        </button>
    </div>
</nav>

<div class="tab-content bg-white border-0 shadow-sm" id="nav-tabContent">
    <div class="tab-pane fade show active border-0 pt-2"
        id="tab-pengajuan-booking"
        role="tabpanel"
        aria-labelledby="tab-pengajuan-booking-tab"
        tabindex="0">
            @include('pengguna.booking.datatable-booking')
    </div>
    <div class="tab-pane fade border-0 pt-2"
        id="tab-log"
        role="tabpanel"
        aria-labelledby="tab-log-tab"
        tabindex="0">
        {{-- isi tab Log di sini --}}
    </div>
</div>

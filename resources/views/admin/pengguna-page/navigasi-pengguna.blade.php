<nav>
    <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
        <button class="nav-link active border-0 col-12 col-md-auto"
            id="nav-pengguna-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-pengguna"
            type="button"
            role="tab"
            aria-controls="nav-pengguna"
            aria-selected="true">
            Pengguna
        </button>
        <button class="nav-link border-0 col-12 col-md-auto"
            id="nav-peran-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-peran"
            type="button"
            role="tab"
            aria-controls="nav-peran"
            aria-selected="false">
            Peran
        </button>
        <button class="nav-link border-0 col-12 col-md-auto"
            id="nav-lokasi-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-lokasi"
            type="button"
            role="tab"
            aria-controls="nav-lokasi"
            aria-selected="false">
            Lokasi
        </button>
    </div>
</nav>

<div class="tab-content bg-white border-0 shadow-sm rounded" id="nav-tabContent">
    <div class="tab-pane fade show active border-0 pt-2"
        id="nav-pengguna"
        role="tabpanel"
        aria-labelledby="nav-pengguna-tab"
        tabindex="0">
        <!-- Konten Pengguna -->
    </div>
    <div class="tab-pane fade border-0 pt-2"
        id="nav-peran"
        role="tabpanel"
        aria-labelledby="nav-peran-tab"
        tabindex="0">
        @include('admin.pengguna-page.peran.datatables-peran')
    </div>
    <div class="tab-pane fade border-0 pt-2"
        id="nav-lokasi"
        role="tabpanel"
        aria-labelledby="nav-lokasi-tab"
        tabindex="0">
        @include('admin.pengguna-page.lokasi.datatables-lokasi')
    </div>
</div>

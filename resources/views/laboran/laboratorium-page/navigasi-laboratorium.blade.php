<nav>
    <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
        <button class="nav-link active border-0 col-12 col-md-auto"
            id="nav-laboratorium-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-laboratorium"
            type="button"
            role="tab"
            aria-controls="nav-laboratorium"
            aria-selected="true">
            Laboratorium
        </button>
        <button class="nav-link border-0 col-12 col-md-auto"
            id="nav-jenis-laboratorium-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-jenis-laboratorium"
            type="button"
            role="tab"
            aria-controls="nav-jenis-laboratorium"
            aria-selected="false">
            Jenis Laboratorium
        </button>
    </div>
</nav>

<div class="tab-content bg-white border-0 shadow-sm" id="nav-tabContent">
    <div class="tab-pane fade show active border-0 pt-2"
        id="nav-laboratorium"
        role="tabpanel"
        aria-labelledby="nav-laboratorium-tab"
        tabindex="0">
        @include('laboran.laboratorium-page.laboratorium.datatables-laboratorium')
    </div>
    <div class="tab-pane fade border-0 pt-2"
        id="nav-jenis-laboratorium"
        role="tabpanel"
        aria-labelledby="nav-jenis-laboratorium-tab"
        tabindex="0">
        @include('laboran.laboratorium-page.jenis-lab.datatables-jenis-lab')
    </div>
</div>

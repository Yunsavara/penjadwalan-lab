<div class="col-12 d-flex flex-wrap justify-content-between bg-white border shadow-sm p-2">

    <div class="col-12">
        <h5><strong>Table Pengajuan</strong></h5>
        <p>Merupakan Daftar Pengajuan.</p>
    </div>

    <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
        <div id="searchLaboranPengajuan" class="col-12 col-md-auto mb-2"></div>
        <div id="sortingLaboranPengajuan" class="col-12 col-md-auto mb-2"></div>
    </div>

    <div class="col-12 position-relative overflow-auto" style="min-height: 15.5rem;">
        <table id="tableLaboranPengajuan" class="table table-striped position-absolute top-0" style="width: 100%; z-index: 0">
            {{-- Di generate disini dari javascript --}}
        </table>
    </div>


    <div class="col-12 py-2 d-flex flex-wrap align-items-center text-center justify-content-between">
        <div id="infoLaboranPengajuan" class="col-12 col-md-auto mb-3 mb-md-0"></div>
        <div id="pagingLaboranPengajuan" class="col-12 col-md-auto mb-3 mb-md-0 d-flex justify-content-center justify-content-md-auto"></div>
    </div>
</div>

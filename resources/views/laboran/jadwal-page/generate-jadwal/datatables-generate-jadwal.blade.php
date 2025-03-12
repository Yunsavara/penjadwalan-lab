<div class="col-12 d-flex flex-wrap justify-content-between bg-white border shadow-sm p-2">

    <div class="col-12">
        <h5><strong>Table Jadwal</strong></h5>
        <p>Merupakan Daftar Jadwal.</p>
    </div>

    <div class="col-12 py-2 d-flex flex-wrap align-items-center justify-content-between">
        <div id="searchLaboranJadwalGenerate" class="col-12 col-md-auto mb-2"></div>
        <div id="sortingLaboranJadwalGenerate" class="col-12 col-md-auto mb-2"></div>
    </div>

    <div class="col-12 position-relative overflow-auto" style="min-height: 15.5rem;">
        <table id="tableLaboranJadwalGenerate" class="table table-striped position-absolute top-0" style="width: 100%; z-index: 0">
            {{-- Di generate disini dari javascript --}}
        </table>
    </div>


    <div class="col-12 py-2 d-flex flex-wrap align-items-center text-center justify-content-between">
        <div id="infoLaboranJadwalGenerate" class="col-12 col-md-auto mb-3 mb-md-0"></div>
        <div id="pagingLaboranJadwalGenerate" class="col-12 col-md-auto mb-3 mb-md-0 d-flex justify-content-center justify-content-md-auto"></div>
    </div>
</div>

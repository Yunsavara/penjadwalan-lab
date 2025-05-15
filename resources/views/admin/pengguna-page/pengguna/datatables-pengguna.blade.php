<div class="col-12 p-2 d-flex flex-wrap align-items-center justify-content-between">
    <div class="col-12 col-md-auto mb-2 bg-info rounded p-2 text-center text-white">
        <span><b>Daftar Pengguna</b></span>
    </div>
    <div class="col-12 col-md-auto mb-2 text-end">
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#formPenggunaStore">
            <i data-feather="plus"></i>
        </button>
    </div>
</div> 

<div class="col-12 p-2 d-flex flex-wrap align-items-center justify-content-between">
    <div id="searchPengguna" class="col-12 col-md-auto mb-2"></div>
    <div id="sortingPengguna" class="col-12 col-md-auto mb-2"></div>
</div>

<div class="table-responsive px-2" id="tablePenggunaContainer">
    <table class="table bg-white table-hover table-bordered" id="tablePengguna" style="width: 100%;">
    </table>
</div>

<div class="col-12 p-2 d-flex flex-wrap align-items-center text-center justify-content-between">
    <div id="infoPengguna" class="col-12 col-md-auto mb-3 mb-md-0"></div>
    <div id="pagingPengguna" class="col-12 col-md-auto mb-3 mb-md-0 d-flex justify-content-center justify-content-md-auto"></div>
</div>

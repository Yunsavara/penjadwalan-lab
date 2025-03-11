<div class="col-12 py-2 d-flex flex-wrap justify-content-end mt-2">
    <button type="button" class="btn btn-primary col-12 col-md-auto" data-bs-toggle="modal" data-bs-target="#formPengajuanStore">
        Buat Pengajuan
    </button>
</div>

 <div class="accordion accordion-flush border" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button bg-primary text-white" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
          Jadwal Tersedia
        </button>
      </h2>
      <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
        <div class="accordion-body">
            @include('all-role.jadwal-page.generate-jadwal.datatables-generate-jadwal')
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed bg-warning" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
          Riwayat Saya
        </button>
      </h2>
      <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
        <div class="accordion-body">
            <nav>
                <div class="nav nav-underline mb-3 nav-fill" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-jadwal" type="button" role="tab" aria-controls="nav-jadwal" aria-selected="true">Jadwal</button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-pengajuan" type="button" role="tab" aria-controls="nav-pengajuan" aria-selected="false">Pengajuan</button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-jadwal" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                    @include('all-role.jadwal-page.jadwal.datatables-jadwal')
                </div>
                <div class="tab-pane fade" id="nav-pengajuan" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                    @include('all-role.jadwal-page.pengajuan.datatables-pengajuan')
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

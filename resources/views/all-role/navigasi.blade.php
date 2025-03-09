<ul class="nav nav-pills mb-3 d-flex flex-wrap justify-content-between" id="pills-tab" role="tablist">
    <li class="nav-item col-5" role="presentation">
      <button class="nav-link active col-12" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-jadwal" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Jadwal</button>
    </li>
    <li class="nav-item col-5" role="presentation">
      <button class="nav-link col-12" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-pengajuan" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Pengajuan</button>
    </li>
  </ul>
  <div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade show active" id="pills-jadwal" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
        @include('all-role.jadwal.datatables-jadwal')
    </div>
    <div class="tab-pane fade" id="pills-pengajuan" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
        @include('all-role.pengajuan.datatables-pengajuan')
    </div>
  </div>

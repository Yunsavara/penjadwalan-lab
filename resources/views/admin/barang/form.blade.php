@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    <div class="container-fluid px-3">
        <form action="{{ $page_meta['url'] }}" method="POST">
            @method($page_meta['method'])
            @csrf

            <div class="col-12 mb-3">
                <label for="lokasiBarang">Lokasi Ruangan</label>
                <select name="lokasi_ruangan" id="lokasiBarang" class="form-select">
                    <option value="" selected>Pilih Lokasi Ruangan</option>
                    <option value="CBT-01">CBT-01 / Viktor</option>
                </select>
            </div>

            <div class="col-12 mb-3">
                <label for="namaBarang">Nama Barang</label>
                <input type="text" class="form-control" id="namaBarang" placeholder="Komputer" autocomplete="off">
            </div>

            <div class="col-12 mb-3">
                <label for="barangMeja">Meja</label>
                <i data-feather="info" data-bs-toggle="tooltip" data-bs-placement="top" title="Jika Bukan Barang yang Berada di Meja, Pilih Tidak" style="width:1rem; margin-left:0.2rem"></i>
                <select name="meja" id="barangMeja" class="form-select">
                    <option value="tidak" selected>Tidak</option>
                    <option value="meja-01">Meja 01</option>
                    {{-- Ini Nanti Event Listener Ke Lokasi Ruangan --}}
                </select>
            </div>

            <div class="col-12 mb-3">
                <label for="spesifikasiBarang">Spesifikasi</label>
                <textarea name="spesifikasi_barang" id="spesifikasiBarang" class="form-control" style="min-height:100px; max-height:100px;" autocomplete="off"></textarea>
            </div>

            <div class="col-12 mb-3">
                <label for="deskripsiBarang">Deskripsi</label>
                <textarea name="deskripsi_barang" id="deskripsiBarang" class="form-control" style="min-height:100px; max-height:100px;" autocomplete="off"></textarea>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="reset" class="btn btn-danger me-2">Reset</button>
                <button type="submit" class="btn btn-primary">{{ $page_meta['button_text'] }}</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $("#lokasiBarang, #barangMeja").select2({
                theme: "bootstrap-5",
                placeholder: "Pilih Opsi",
                allowClear: true,
            });

            // Menutup Select2 pas klik di luar elemen
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".select2-container").length && !$(e.target).is("#lokasiBarang")) {
                    $("#lokasiBarang,#barangMeja").select2("close");
                }
            });
        });
    </script>

@endsection

@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
    <h1 class="fw-bold">{{ $page_meta['page'] }}</h1>
    <hr>

    <div class="container-fluid">
        <form action="{{ $page_meta['url'] }}" method="POST">
            @method($page_meta['method'])
            @csrf

            <div class="col-12 mb-3">
                <label for="lokasiBarang">Lokasi Barang</label>
                <select name="lokasi_barang" id="lokasiBarang" class="form-select">
                    <option value="" selected>Pilih Lokasi</option>
                    <option value="VIKTOR">Viktor</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end">
                <button type="reset" class="btn btn-danger me-2">Reset</button>
                <button type="submit" class="btn btn-primary">{{ $page_meta['button_text'] }}</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $("#lokasiBarang").select2({
                theme: "bootstrap-5",
                placeholder: "Pilih Lokasi",
                allowClear: true,
            });

            // Menutup Select2 saat klik di luar elemen
            $(document).on("click", function(e) {
                if (!$(e.target).closest(".select2-container").length && !$(e.target).is("#lokasiBarang")) {
                    $("#lokasiBarang").select2("close");
                }
            });
        });
    </script>

@endsection

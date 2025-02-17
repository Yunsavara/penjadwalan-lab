<div class="container-fluid p-3 d-flex justify-content-between flex-wrap align-items-center border rounded shadow-sm">
    {{-- Tombol Tambah --}}
    @if(isset($routeTambah))
        <div class="button-tambah col-12 col-md-auto pb-2 pb-md-0">
            <a href="{{ route($routeTambah) }}">
                <button class="btn btn-primary col-12">Tambah</button>
            </a>
        </div>
    @endif

    {{-- Pencarian --}}
    @if(isset($searchId))
        <div class="table-search col-12 col-md-auto">
            <input type="search" id="{{ $searchId }}" class="form-control col-12" placeholder="{{ $searchPlaceholder ?? 'Cari...' }}">
        </div>
    @endif

    {{-- Tabel --}}
    <div class="position-relative table-responsive col-12 mt-3" style="padding-bottom: 20rem">
        <table id="{{ $tableId ?? 'dataTable' }}" class="table table-striped text-truncate position-absolute top-0">
            <thead class="table-dark">
                <tr>
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
        </table>
    </div>
</div>

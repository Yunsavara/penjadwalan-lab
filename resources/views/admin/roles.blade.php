@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mx-2 my-3">
        <div class="d-flex justify-content-between">
            <div class="col-4">
                <h1 class="fw-bold fs-3">Role Pengguna</h1>
            </div>

            <div class="col-4 d-flex align-items-center bg-dark rounded-2 p-2 px-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-search"
                    viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                </svg>
                <input class="bg-transparent border-0 w-100 text-light mx-2 myoutline-0" type="search" name=""
                    id="" placeholder="Pencarian...">
            </div>

            <div class="col-4 text-end">
                <a href="" class="btn mybg-brown text-light">Tambah Data</a>
            </div>
        </div>
        <hr>

        <div class="my-5">
            <table class="table">
                <thead>
                    <td class="mybg-brown100">No.</td>
                    <td class="mybg-brown100">Nama</td>
                    <td class="mybg-brown100">Tingkat Prioritas</td>
                    <td class="mybg-brown100 text-center">Pilihan</td>
                </thead>

                <tr>
                    <td>1.</td>
                    <td>Admin</td>
                    <td>1</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white"
                                class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                            </svg>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>2.</td>
                    <td>Lembaga Unpam</td>
                    <td>2</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                height="16" fill="white" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                <path
                                    d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0" />
                            </svg></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection

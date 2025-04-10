@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="col-12 p-3">
        <h2>{{ $page_meta['page'] }}</h2>
        <span>{{ $page_meta['description'] }}</span>
        <hr>
        <div id="table-container">
            @include('laboran.pengguna-page.navigasi')
        </div>
    </div>
@endsection

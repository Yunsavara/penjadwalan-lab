@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="col-12 p-3">
        <h1>{{ $page_meta['page'] }}</h1>
        <span>{{ $page_meta['description'] }}</span>
        <hr>
    </div>
@endsection

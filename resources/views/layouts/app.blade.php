<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS and JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

    {{-- Logo Unpam Buat Favicon --}}
    <link rel="icon" href="{{ asset('images/unpam-logo.png') }}" type="image">

    <title>@yield('title', 'Penjadwalan Lab')</title>

</head>
<body class="pt-4 bg-body-tertiary">

    {{-- Header atau Navbar --}}
    @include('layouts.header')

    <div class="wrapper d-flex bg-body-tertiary vh-100">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Content --}}
        <div class="content flex-grow pt-3">

            <div class="hidden-container">
                <!-- Search Box Disini Biar Lebarnya Sesuai Dengan Content -->
                @include('layouts.search-box')
                @include('layouts.notif')
            </div>

            {{-- Error Toast --}}
            <x-validation></x-validation>

            @yield('content')
        </div>

    </div>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            feather.replace();
        })
    </script>
</body>
</html>

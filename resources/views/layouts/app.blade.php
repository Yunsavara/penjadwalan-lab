<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <link rel="icon" href="{{ asset('images/unpam-logo.png') }}" type="image">
    <title>@yield('title', 'Penjadwalan Lab')</title>

    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

     <!-- Font Poppin -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link
         href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
         rel="stylesheet">
</head>
<body>

    {{-- Header atau Navbar --}}
    @include('layouts.header')

    <div class="wrapper">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Content --}}
        <div class="content">

            <!-- Search Box Disini Biar Lebarnya Sesuai Dengan Content -->
            @include('layouts.search-box')

            @yield('content')
        </div>

    </div>

    <script>
        feather.replace();
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>

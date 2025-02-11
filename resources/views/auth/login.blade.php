<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/unpam-logo.png') }}" type="image">
    <title>Login</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>

    <nav class="navbar navbar-expand-lg d-flex justify-content-between border-bottom shadow">
        <div class="container-fluid px-5">
            <div class="navbar-brand">
                <img src="{{ asset('images/unpam-logo.png') }}" width="50px" alt="" srcset="">
                <b class="mysite-title mx-3 fs-4"><i>Penjadwalan Lab</i></b>
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="container-fluid"></div>

            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    @if (Route::has('login'))
                        @auth
                            @if (auth()->user()->role == 'admin')
                                <a href="{{ url('/admin/dashboard') }}" class="nav-link">
                                    Admin Dashboard
                                </a>
                            @endif
                            @if (auth()->user()->role == 'laboran')
                                <a href="{{ url('/laboran/dashboard') }}" class="nav-link">
                                    Laboran Dashboard
                                </a>
                            @endif
                            @if (auth()->user()->role == 'user')
                                <a href="{{ url('/user/dashboard') }}" class="nav-link">
                                    User Dashboard
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="nav-link">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="nav-link">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif

                </div>
            </div>
        </div>

    </nav>

    <div class="container-fluid d-flex align-items-center" style="height: 88vh">
        <div class="container col-5 d-block border mx-auto p-0 rounded-3 shadow">
            <div class="login-image col-12">
                <img src="{{ asset('images/cbt.jpg') }}" alt="foto login page" class="rounded-top img-fluid w-100" style="height: 13rem">
            </div>
            <div class="login-page col-12 py-3 px-5">
                <div class="login-form px-5">
                    <h3 class="text-center fw-bold">Login</h3>
                    <hr>
                    <form action="{{ $page_meta['url'] }}" method="POST">
                        @method($page_meta['method'])
                        @csrf
                        <div class="col-12 mb-3">
                            <label for="userEmail">Email</label>
                            <input type="email" name="email" id="userEmail" class="form-control @error ('email') is-invalid @enderror" autocomplete="off" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label for="userPassword">Password</label>
                            <input type="password" name="password" id="userPassword" class="form-control @error ('password') is-invalid @enderror" autocomplete="off" required>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <button type="submit" class="btn btn-primary col-12">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- Boostrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>
</html>

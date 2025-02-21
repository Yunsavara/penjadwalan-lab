<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\AllRole\BookingController;
use App\Http\Controllers\Laboran\JenisLabController;
use App\Http\Controllers\Laboran\LaboratoriumUnpamController;

Route::group(['middleware' => 'guest'], function() {
    // Home
    Route::get('/', [LoginController::class, 'home'])->name('home');

    // Registrasi Route

    Route::get('/register', [RegisterController::class,'index'])->name('register');
    Route::post('/register', [RegisterController::class,'store']);

    // Login Route
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

});

Route::group(['middleware'=> 'auth'], function() {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Booking atau pengajuan jadwal
    Route::get('/pengajuan-jadwal', [BookingController::class, 'index'])->name('pengajuan');
    Route::get('/tambah-pengajuan', [BookingController::class, 'create'])->name('pengajuan.create');

});

Route::group(['middleware' => ['role:admin']], function() {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class,'admin'])->name('admin.dashboard');

    // Manajemen - Pengguna
    Route::get('/admin/pengguna', [UsersController::class, 'index'])->name('admin.pengguna');

    // Manajemen - Roles
    Route::get('/admin/roles', [RolesController::class, 'index'])->name('admin.roles');

    // Barang
    Route::get('/admin/barang', [BarangController::class, 'index'])->name('admin.barang');
    Route::get('/admin/tambah-barang', [BarangController::class, 'create'])->name('admin.barang.create');
});

Route::group(['middleware' => ['role:laboran']], function() {
    Route::get('/laboran/dashboard', [DashboardController::class,'laboran'])->name('laboran.dashboard');

    // Jenis Lab
    Route::get('/laboran/jenis-lab', [JenisLabController::class, 'index'])->name('laboran.jenis-lab');
    Route::get('/laboran/jenis-lab/data', [JenisLabController::class, 'getData'])->name('jenislab.getData');

    Route::get('/laboran/tambah-jenis-lab', [JenisLabController::class, 'create'])->name('laboran.jenis-lab.create');
    Route::post('/laboran/tambah-jenis-lab', [JenisLabController::class, 'store']);
    Route::get('/laboran/ubah-jenis-lab/{jenislab:slug}', [JenisLabController::class, 'edit'])->name('laboran.jenis-lab.edit');
    Route::put('/laboran/ubah-jenis-lab/{jenislab:slug}', [JenisLabController::class, 'update']);

    // Laboratorium
    Route::get('/laboran/laboratorium', [LaboratoriumUnpamController::class, 'index'])->name('laboran.laboratorium');
    Route::get('/laboran/laboratorium/laboratorium-data', [LaboratoriumUnpamController::class, 'getData']);

    Route::get('/laboran/tambah-laboratorium', [LaboratoriumUnpamController::class, 'create'])->name('laboran.laboratorium.create');
    Route::post('/laboran/tambah-laboratorium', [LaboratoriumUnpamController::class, 'store']);
    Route::get('/laboran/ubah-laboratorium/{laboratorium:slug}', [LaboratoriumUnpamController::class, 'edit'])->name('laboran.laboratorium.edit');
    Route::put('/laboran/ubah-laboratorium/{laboratorium:slug}', [LaboratoriumUnpamController::class, 'update']);
});

Route::group(['middleware' => ['role:prodi']], function() {
    Route::get('/prodi/dashboard', [DashboardController::class,'prodi'])->name('prodi.dashboard');
});

Route::group(['middleware' => ['role:user']], function() {
    Route::get('/dashboard', [DashboardController::class,'user'])->name('user.dashboard');
});

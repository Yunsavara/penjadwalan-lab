<?php

use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\JenislabController;
use App\Http\Controllers\Admin\LaboratoriumUnpamController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\DashboardController;

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
});

Route::group(['middleware' => ['role:admin']], function() {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class,'admin'])->name('admin.dashboard');

    // Manajemen - Pengguna
    Route::get('/admin/pengguna', [UsersController::class, 'index'])->name('admin.pengguna');

    // Manajemen - Roles
    Route::get('/admin/roles', [RolesController::class, 'index'])->name('admin.roles');

    // Semester
    Route::get('/admin/semester', [SemesterController::class, 'index'])->name('admin.semester');
    Route::get('/admin/semester/semester-data', [SemesterController::class, 'getData']);

    Route::get('/admin/tambah-semester', [SemesterController::class, 'create'])->name('admin.semester.create');
    Route::post('/admin/tambah-semester', [SemesterController::class, 'store']);
    Route::get('/admin/ubah-semester/{semester:slug}', [SemesterController::class, 'edit'])->name('admin.semester.edit');
    Route::put('/admin/ubah-semester/{semester:slug}', [SemesterController::class, 'update']);

    // Barang
    Route::get('/admin/barang', [BarangController::class, 'index'])->name('admin.barang');
    Route::get('/admin/tambah-barang', [BarangController::class, 'create'])->name('admin.barang.create');

    // Jenis Lab
    Route::get('/admin/jenis-lab', [JenislabController::class, 'index'])->name('admin.jenis-lab');
    Route::get('/admin/jenis-lab/data', [JenislabController::class, 'getData'])->name('jenislab.getData');

    Route::get('/admin/tambah-jenis-lab', [JenislabController::class, 'create'])->name('admin.jenis-lab.create');
    Route::post('/admin/tambah-jenis-lab', [JenislabController::class, 'store']);
    Route::get('/admin/ubah-jenis-lab/{jenislab:slug}', [JenislabController::class, 'edit'])->name('admin.jenis-lab.edit');
    Route::put('/admin/ubah-jenis-lab/{jenislab:slug}', [JenislabController::class, 'update']);

    // Laboratorium
    Route::get('/admin/laboratorium', [LaboratoriumUnpamController::class, 'index'])->name('admin.laboratorium');
    Route::get('/admin/laboratorium/laboratorium-data', [LaboratoriumUnpamController::class, 'getData']);

    Route::get('/admin/tambah-laboratorium', [LaboratoriumUnpamController::class, 'create'])->name('admin.laboratorium.create');
    Route::post('/admin/tambah-laboratorium', [LaboratoriumUnpamController::class, 'store']);
    Route::get('/admin/ubah-laboratorium/{laboratorium:slug}', [LaboratoriumUnpamController::class, 'edit'])->name('admin.laboratorium.edit');
    Route::put('/admin/ubah-laboratorium/{laboratorium:slug}', [LaboratoriumUnpamController::class, 'update']);
});

Route::group(['middleware' => ['role:laboran']], function() {
    Route::get('/laboran/dashboard', [DashboardController::class,'laboran'])->name('laboran.dashboard');
});

Route::group(['middleware' => ['role:user']], function() {
    Route::get('/dashboard', [DashboardController::class,'user'])->name('user.dashboard');
});

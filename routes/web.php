<?php

use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\LokasiController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Laboran\JenisLabController;

use App\Http\Controllers\Laboran\LaboratoriumUnpamController;
use App\Http\Controllers\Pengguna\JadwalBookingController;
use App\Http\Controllers\Pengguna\PengajuanBookingController;
use Illuminate\Support\Facades\Route;


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

    // Pengguna Page
    Route::get('/admin/pengguna', [UsersController::class, 'index'])->name('admin.pengguna');

    // Pengguna Datatables
    Route::get('/admin/api/data-pengguna', [UsersController::class, 'getApiPengguna']);

    // Pengguna Store, Update & Soft Delete
    Route::post('/admin/tambah-pengguna', [UsersController::class, 'store'])->name('admin.pengguna.store');
    Route::put('/admin/ubah-pengguna/{id}', [UsersController::class, 'update']);
    Route::delete('/admin/hapus-pengguna/{id}', [UsersController::class, 'softDelete']);

    // Peran Datatables
    Route::get('/admin/api/data-peran', [RolesController::class, 'getApiPeran']);

    // Peran Store, Update & Soft Delete
    Route::post('/admin/tambah-peran', [RolesController::class, 'store'])->name('admin.peran.store');
    Route::put('/admin/ubah-peran/{id}', [RolesController::class, 'update']);
    Route::delete('/admin/hapus-peran/{id}', [RolesController::class, 'softDelete']);

    // Lokasi Datatables
    Route::get('/admin/api/data-lokasi', [LokasiController::class, 'getApiLokasi']);

    // Lokasi Store, Update & Soft Delete
    Route::post('/admin/tambah-lokasi', [LokasiController::class, 'store'])->name('admin.lokasi.store');
    Route::put('/admin/ubah-lokasi/{id}', [LokasiController::class, 'update']);
    Route::delete('/admin/hapus-lokasi/{id}', [LokasiController::class, 'softDelete']);



    // Barang
    Route::get('/admin/barang', [BarangController::class, 'index'])->name('admin.barang');
    Route::get('/admin/tambah-barang', [BarangController::class, 'create'])->name('admin.barang.create');
});



Route::group(['middleware' => ['role:admin,laboran']], function() {
    Route::get('/laboran/dashboard', [DashboardController::class,'laboran'])->name('laboran.dashboard');

    // Laboratorium Page
    Route::get('/laboran/laboratorium', [LaboratoriumUnpamController::class, 'index'])->name('laboran.laboratorium');

    // Laboratorium Datatables
    Route::get('/laboran/api/data-laboratorium', [LaboratoriumUnpamController::class, 'getApiLaboratorium']);

    // Laboratorium Store, Update & Soft Delete
    Route::post('/laboran/tambah-laboratorium', [LaboratoriumUnpamController::class, 'store'])->name('laboran.laboratorium.store');
    Route::put('/laboran/ubah-laboratorium/{id}', [LaboratoriumUnpamController::class, 'update']);
    Route::delete('/laboran/hapus-laboratorium/{id}', [LaboratoriumUnpamController::class, 'softDelete']);

    // Jenis Laboratorium Datatables
    Route::get('/laboran/api/data-jenis-laboratorium', [JenisLabController::class, 'getApiJenisLaboratorium']);

    // Jenis Laboratorium Store, Update & Soft Delete
    Route::post('/laboran/tambah-jenis-laboratorium', [JenisLabController::class, 'store'])->name('laboran.jenis-lab.store');
    Route::put('/laboran/ubah-jenis-laboratorium/{id}', [JenisLabController::class, 'update']);
    Route::delete('/laboran/hapus-jenis-laboratorium/{id}', [JenisLabController::class, 'softDelete']);

});

Route::group(['middleware' => ['role:admin,lembaga,prodi,user']], function() {
    Route::get('/dashboard', [DashboardController::class,'dashboardPengguna'])->name('dashboard');

    // Pengajuan Page
    Route::get('/pengajuan', [PengajuanBookingController::class, 'index'])->name('pengajuan');

    // Form Pengajuan
    Route::get('/pengajuan/api/data-laboratorium/{lokasiId}', [PengajuanBookingController::class, 'getLaboratoriumByLokasi']);
    Route::get('/pengajuan/api/data-hari-operasional/{lokasiId}', [PengajuanBookingController::class, 'getHariOperasionalByLokasi']);
    Route::get('/pengajuan/api/data-jam-operasional/{hariOperasionalId}', [PengajuanBookingController::class, 'getJamOperasional']);

    // Pengajuan Booking Datatables
    Route::get('/pengajuan/api/data-pengajuan-booking', [PengajuanBookingController::class, 'getApiPengajuanBooking']);

    // Pengajuan Store
    Route::get('/pengajuan/buat-pengajuan-booking', [PengajuanBookingController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan/tambah-pengajuan-booking', [PengajuanBookingController::class, 'store'])->name('pengajuan.store');
 
    // Jadwal Page
    Route::get('/jadwal', [JadwalBookingController::class, 'index'])->name('jadwal');
});

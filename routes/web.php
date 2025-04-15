<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AllRole\JadwalController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Laboran\JenisLabController;
use App\Http\Controllers\AllRole\PengajuanController;
use App\Http\Controllers\AllRole\GenerateJadwalController;
use App\Http\Controllers\Laboran\LaboranPengajuanController;
use App\Http\Controllers\Laboran\LaboratoriumUnpamController;
use App\Http\Controllers\Laboran\LaboranLogPengajuanController;
use App\Http\Controllers\Laboran\LaboranGenerateJadwalController;
use App\Http\Controllers\Laboran\LaboranBookingLogPengajuanController;
use App\Models\LaboratoriumUnpam;

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

    // Barang
    Route::get('/admin/barang', [BarangController::class, 'index'])->name('admin.barang');
    Route::get('/admin/tambah-barang', [BarangController::class, 'create'])->name('admin.barang.create');
});



Route::group(['middleware' => ['role:admin,laboran']], function() {
    Route::get('/laboran/dashboard', [DashboardController::class,'laboran'])->name('laboran.dashboard');

    // Manajemen - Pengguna
    Route::get('/laboran/pengguna', [UsersController::class, 'index'])->name('laboran.pengguna');
    Route::get('/laboran/api/data-peran', [UsersController::class, 'getApiRoles']);

    // Laboratorium Page
    Route::get('/laboran/laboratorium', [LaboratoriumUnpamController::class, 'index'])->name('laboran.laboratorium');

    // Laboratorium Datatables
    Route::get('/laboran/api/data-laboratorium', [LaboratoriumUnpamController::class, 'getApiLaboratorium']);

    // Laboratorium Store, Update & Soft Delete
    Route::post('/laboran/tambah-laboratorium', [LaboratoriumUnpamController::class, 'store'])->name('laboran.laboratorium.store');
    Route::put('/laboran/ubah-laboratorium/{laboratorium:slug}', [LaboratoriumUnpamController::class, 'update']);
    Route::delete('/laboran/hapus-laboratorium/{Laboratorium:slug}', [LaboratoriumUnpamController::class, 'softDelete']);

    // Jenis Laboratorium Datatables
    Route::get('/laboran/api/data-jenis-laboratorium', [JenisLabController::class, 'getApiJenisLaboratorium']);

    // Jenis Laboratorium Store, Update & Soft Delete
    Route::post('/laboran/tambah-jenis-laboratorium', [JenisLabController::class, 'store'])->name('laboran.jenis-lab.store');
    Route::put('/laboran/ubah-jenis-laboratorium/{Jenislab:slug_jenis_lab}', [JenisLabController::class, 'update']);

    // Booking atau Pengajuan
    Route::get('/laboran/jadwal', [LaboranPengajuanController::class, 'index'])->name('laboran.pengajuan');
    Route::get('/laboran/jadwal/pengajuan-data', [LaboranPengajuanController::class, 'getDataBooking']);

    Route::post('/laboran/pengajuan-diterima', [LaboranPengajuanController::class, 'terimaPengajuan'])->name('pengajuan.terima');
    Route::post('/laboran/pengajuan-ditolak', [LaboranPengajuanController::class, 'tolakPengajuan'])->name('pengajuan.tolak');

    // Jadwal Generate (Bikin Bisa batalin jadwal nanti)
    Route::get('/laboran/jadwal/generate-jadwal', [LaboranGenerateJadwalController::class, 'generateJadwal'])->name('jadwal.generate');

    // Booking Log
    Route::get('/laboran/jadwal/booking-log', [LaboranBookingLogPengajuanController::class, 'getDataBookingLog']);
});



Route::group(['middleware' => ['role:lembaga,prodi,user']], function() {
    Route::get('/dashboard', [DashboardController::class,'dashboardAllRole'])->name('dashboard');

    // Booking atau Pengajuan
    Route::get('/jadwal', [PengajuanController::class, 'index'])->name('pengajuan');
    Route::post('/jadwal', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/jadwal/pengajuan-data', [PengajuanController::class, 'getDataBooking']); //datatables
    Route::get('/jadwal/pengajuan-detail/{kode_pengajuan}', [PengajuanController::class, 'getDetailBooking']); //detail
    Route::get('/jadwal/pengajuan-update/{kode_pengajuan}', [PengajuanController::class, 'edit'])->name('pengajuan.update');
    Route::put('/jadwal/pengajuan-update/{kode_pengajuan}', [PengajuanController::class, 'update']);
    Route::post('/jadwal/pengajuan-batalkan', [PengajuanController::class, 'batalkanBooking'])->name('pengajuan.batalkan');

    // Jadwal Yang Di Booking
    Route::get('/jadwal/jadwal-data', [JadwalController::class, 'getDataJadwal']);
    Route::get('/jadwal/jadwal-detail/{kode_pengajuan}', [JadwalController::class, 'getDetailJadwal']);
    Route::post('/jadwal/jadwal-batalkan', [JadwalController::class, 'batalkanJadwal'])->name('jadwal.batalkan');

    // Jadwal Generate
    Route::get('/jadwal/generate-jadwal', [GenerateJadwalController::class, 'generateJadwal'])->name('jadwal.generate');

});

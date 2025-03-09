<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\AllRole\JadwalController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Laboran\JenisLabController;
use App\Http\Controllers\AllRole\PengajuanController;
use App\Http\Controllers\Laboran\LaboratoriumUnpamController;
use App\Http\Controllers\Laboran\PengajuanController as LaboranPengajuanController;

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

    // Booking atau Pengajuan
    Route::get('/jadwal', [PengajuanController::class, 'index'])->name('pengajuan');
    Route::post('/jadwal', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/jadwal/pengajuan-data', [PengajuanController::class, 'getDataBooking']); //datatables
    Route::get('/jadwal/pengajuan-detail/{kode_pengajuan}', [PengajuanController::class, 'getDetailBooking']); //detail
    Route::get('/jadwal/pengajuan-update/{kode_pengajuan}', [PengajuanController::class, 'edit'])->name('pengajuan.update');
    Route::put('/jadwal/pengajuan-update/{kode_pengajuan}', [PengajuanController::class, 'update']);
    Route::post('/jadwal/pengajuan-batalkan', [PengajuanController::class, 'batalkanBooking'])->name('pengajuan.batalkan');

    // Jadwal
    Route::get('/jadwal/jadwal-data', [JadwalController::class, 'getDataJadwal']);
    Route::get('/jadwal/jadwal-detail/{kode_pengajuan}', [JadwalController::class, 'getDetailJadwal']);
    Route::post('/jadwal/jadwal-batalkan', [JadwalController::class, 'batalkanJadwal'])->name('jadwal.batalkan');


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

    // Pengajuan
    Route::get('/laboran/pengajuan-jadwal', [LaboranPengajuanController::class, 'index'])->name('laboran.pengajuan');
    Route::get('/laboran/pengajuan-jadwal/pengajuan-jadwal-data', [LaboranPengajuanController::class, 'getDataPengajuan']);
    Route::get('/laboran/pengajuan-jadwal/jadwal-data', [LaboranPengajuanController::class, 'getDataJadwal']);
    Route::post('/laboran/pengajuan-jadwal/update-status', [LaboranPengajuanController::class, 'updateStatus'])->name('pengajuan.update-status');
});

Route::group(['middleware' => ['role:lembaga']], function() {
    Route::get('/lembaga/dashboard', [DashboardController::class,'lembaga'])->name('lembaga.dashboard');
});

Route::group(['middleware' => ['role:prodi']], function() {
    Route::get('/prodi/dashboard', [DashboardController::class,'prodi'])->name('prodi.dashboard');
});

Route::group(['middleware' => ['role:user']], function() {
    Route::get('/dashboard', [DashboardController::class,'user'])->name('user.dashboard');
});

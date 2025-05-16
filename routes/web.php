<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\UserController;
use App\Models\User;

Route::get('/', [QrcodeController::class, 'welcome'])->name('welcome');
Route::post('/generateqr', [QrcodeController::class, 'generateQr'])->name('generate.qr');
Route::get('/generateqr', function () {
    return view('generateqr');
})->name('generateqr');
Route::get('/halamanacara', [AcaraController::class, 'halamanacara'])->name('halamanacara');
Route::get('/halamanacara/{id}', [AcaraController::class, 'halamandetailacara'])->name('halamandetail');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $users = User::all(); // Ambil semua user
        return view('dashboard.index', compact('users')); // Kirim ke view
    })->name('dashboard.index');
    Route::post('/absensi/record', [KehadiranController::class, 'record'])->name('kehadiran.record');
    Route::get('/absensi', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/acara/{acara}', [AcaraController::class, 'show'])->name('acara.show');
    Route::get('/acara/{acara}/export-csv', [AcaraController::class, 'exportCsv'])->name('acara.exportCsv');
    Route::middleware('is_admin')->group(function(){
        Route::resource('acara', AcaraController::class);
        Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
        Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
        Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
        Route::get('/anggota/{anggota}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
        Route::put('/anggota/{anggota}', [AnggotaController::class, 'update'])->name('anggota.update');
        Route::delete('/anggota/{anggota}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
        Route::get('/anggota/{anggota}/show', [AnggotaController::class, 'show'])->name('anggota.show');
        Route::get('/anggota/import', [App\Http\Controllers\AnggotaController::class, 'importForm'])->name('anggota.import');
        Route::post('/anggota/import', [App\Http\Controllers\AnggotaController::class, 'importProcess'])->name('anggota.import.process');
        Route::post('anggota/mass-update', [AnggotaController::class, 'massUpdate'])->name('anggota.mass-update');
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.destroy');

    });
});

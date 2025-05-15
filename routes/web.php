<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QrcodeController;
Route::get('/', [QrcodeController::class, 'welcome'])->name('welcome');
Route::post('/generate-qr', [QrcodeController::class, 'generateQr'])->name('generate.qr');
Route::get('/generateqr', function () {
    return view('generateqr');
})->name('generateqr');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');
Route::post('/register', [AnggotaController::class, 'store'])->name('register.store');

Route::middleware('auth')->group(function () {
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
    });
    Route::post('/absensi/record', [KehadiranController::class, 'record'])->name('kehadiran.record');
    Route::get('/absensi', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/acara/{acara}', [AcaraController::class, 'show'])->name('acara.show');
    Route::get('/acara/{acara}/export-csv', [AcaraController::class, 'exportCsv'])->name('acara.exportCsv');
});

<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\QrcodeController;
Route::get('/', [QrcodeController::class, 'welcome'])->name('welcome');
Route::post('/generate-qr', [QrcodeController::class, 'generateQr'])->name('generate.qr');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');




Route::get('/test-acara-create', [AcaraController::class, 'create']);
// Event routes
Route::get('/acara', [AcaraController::class, 'index'])->name('acara.index');

Route::middleware('auth')->group(function () {
    Route::middleware('is_admin')->group(function(){

        Route::get('/acara/create', [AcaraController::class, 'create'])->name('acara.create');
        Route::post('/acara', [AcaraController::class, 'store'])->name('acara.store');
        Route::delete('/acara/{acara}', [AcaraController::class, 'destroy'])->name('acara.destroy');
        Route::get('/acara/{acara}/edit', [AcaraController::class, 'edit'])->name('acara.edit');
        Route::put('/acara/{acara}', [AcaraController::class, 'update'])->name('acara.update');
    });
    Route::post('/absensi/record', [KehadiranController::class, 'record'])->name('kehadiran.record');
    Route::get('/absensi', [KehadiranController::class, 'index'])->name('kehadiran.index');
    Route::get('/acara/{acara}', [AcaraController::class, 'show'])->name('acara.show');
    Route::get('/acara/{acara}/export-csv', [AcaraController::class, 'exportCsv'])->name('acara.exportCsv');
});
// Route::get('/acara/create', [AcaraController::class, 'create'])->name('acara.create');
// Route::post('/acara/store', [AcaraController::class, 'store'])->name('acara.store');


// Attendance routes

// Admin only routes
// Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
//     Route::get('/kehadiran', [KehadiranController::class, 'adminIndex'])->name('admin.kehadiran.index');
    
// });
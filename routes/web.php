<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcaraController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');




Route::get('/test-acara-create', [AcaraController::class, 'create']);
// Event routes
Route::get('/acara', [AcaraController::class, 'index'])->name('acara.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/acara', [AcaraController::class, 'index'])->name('acara.index');
    Route::get('/acara/create', [AcaraController::class, 'create'])->name('acara.create');
    Route::post('/acara/store', [AcaraController::class, 'store'])->name('acara.store');
    Route::get('/acara/{acara}', [AcaraController::class, 'show'])->name('acara.show');
});
// Route::get('/acara/create', [AcaraController::class, 'create'])->name('acara.create');
// Route::post('/acara/store', [AcaraController::class, 'store'])->name('acara.store');
// Route::get('/acara/{acara}', [AcaraController::class, 'show'])->name('acara.show');


// Attendance routes
Route::get('/absensi', [KehadiranController::class, 'index'])->name('kehadiran.index');
Route::post('/absensi/record', [KehadiranController::class, 'record'])->name('kehadiran.record');

// Admin only routes
// Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
//     Route::get('/kehadiran', [KehadiranController::class, 'adminIndex'])->name('admin.kehadiran.index');
    
// });
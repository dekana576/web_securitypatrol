<?php

use App\Http\Controllers\Admin\CheckpointController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\SalesOfficeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    // Group khusus Admin
    Route::middleware(['cek_login:admin'])->group(function () {

        // ====================== User Management ======================
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/data', [UserController::class, 'getdata'])->name('data');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });

        // ====================== Region Management ======================
        Route::prefix('region')->name('region.')->group(function () {
            Route::get('/', [RegionController::class, 'index'])->name('index');
            Route::get('/data', [RegionController::class, 'getdata'])->name('data');
            Route::get('/create', [RegionController::class, 'create'])->name('create');
            Route::post('/', [RegionController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [RegionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [RegionController::class, 'update'])->name('update');
            Route::delete('/{id}', [RegionController::class, 'destroy'])->name('destroy');
        });

        // ====================== Sales Office Management ======================
        Route::prefix('sales_office')->name('sales_office.')->group(function () {
            Route::get('/', [SalesOfficeController::class, 'index'])->name('index');
            Route::get('/data', [SalesOfficeController::class, 'getdata'])->name('data');
            Route::get('/create', [SalesOfficeController::class, 'create'])->name('create');
            Route::post('/', [SalesOfficeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SalesOfficeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SalesOfficeController::class, 'update'])->name('update');
            Route::delete('/{id}', [SalesOfficeController::class, 'destroy'])->name('destroy');
        });

        // ====================== Checkpoint Management ======================
        Route::prefix('checkpoint')->name('checkpoint.')->group(function () {
            Route::get('/', [CheckpointController::class, 'index'])->name('index');
            Route::get('/data', [CheckpointController::class, 'data'])->name('data');
            Route::get('/create', [CheckpointController::class, 'create'])->name('create');
            Route::post('/', [CheckpointController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CheckpointController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CheckpointController::class, 'update'])->name('update');
            Route::delete('/{id}', [CheckpointController::class, 'destroy'])->name('destroy');
        });

    });

    // Group khusus Security (jika ingin ditambahkan route khusus security)
    Route::middleware(['cek_login:security'])->group(function () {
        // Tambahkan route khusus untuk security di sini
    });

});

// Dashboard & Auth
Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
Route::prefix('login')->name('login.')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::post('/', [AuthController::class, 'Login'])->name('login');
});

Route::get('/logout',[AuthController::class, 'logout'])->name('logout');




Route::get('/get-sales-offices/{region}', [CheckpointController::class, 'getSalesOfficesByRegion']);


// Static Views (sementara)
Route::get('/data_patrol', fn () => view('admin.data_patrol.data_patrol'))->name('data_patrol');
Route::get('/jadwal_patrol', fn () => view('admin.jadwal_patrol'))->name('jadwal_patrol');

Route::get('/kriteria_checkpoint', fn () => view('admin.checkpoint.kriteria_checkpoint'))->name('kriteria_checkpoint');
Route::get('/user_jadwal', fn () => view('user.user_jadwal'))->name('user_jadwal');

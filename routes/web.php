<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RegionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard & Auth
Route::get('/dashboard', fn () => view('admin.dashboard'))->name('dashboard');
Route::get('/login', fn () => view('auth.login'))->name('login');

// User Management
Route::get('/user', fn () => view('admin.user.user'))->name('user.index');
Route::get('/adduser', fn () => view('admin.user.add_user'))->name('user.create');

// Region Routes (Group untuk kerapihan dan konsistensi)
Route::prefix('region')->name('region.')->group(function () {
    Route::get('/', [RegionController::class, 'index'])->name('index');
    Route::get('/data', [RegionController::class, 'getdata'])->name('data');
    Route::get('/create', [RegionController::class, 'create'])->name('create');
    Route::post('/', [RegionController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [RegionController::class, 'edit'])->name('edit'); // <- ditambahkan
    Route::put('/{id}', [RegionController::class, 'update'])->name('update');
    Route::delete('/{id}', [RegionController::class, 'destroy'])->name('destroy');
});

// Static Views (sementara)
Route::get('/data_patrol', fn () => view('admin.data_patrol.data_patrol'))->name('data_patrol');
Route::get('/jadwal_patrol', fn () => view('admin.jadwal_patrol'))->name('jadwal_patrol');
Route::get('/checkpoint', fn () => view('admin.checkpoint.checkpoint'))->name('checkpoint');
Route::get('/kriteria_checkpoint', fn () => view('admin.checkpoint.kriteria_checkpoint'))->name('kriteria_checkpoint');
Route::get('/sales_office', fn () => view('admin.sales_office.sales_office'))->name('sales_office');
Route::get('/user_jadwal', fn () => view('user.user_jadwal'))->name('user_jadwal');

<?php

use App\Http\Controllers\Admin\RegionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Admin Routes
Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/user', function () {
    return view('admin.user.user');
});
Route::get('/adduser', function () {
    return view('admin.user.add_user');
});

Route::get('/login', function () {
    return view('auth.login');
});


Route::get('/region', [RegionController::class, 'index'])->name('region.index');
Route::get('/region-data', [RegionController::class, 'getdata'])->name('region.data');
Route::get('/region/create', [RegionController::class, 'create'])->name('region.create');
Route::post('/region', [RegionController::class, 'store'])->name('region.store');
Route::put('/region/{id}', [RegionController::class, 'update'])->name('region.update');
Route::delete('/region/{id}', [RegionController::class, 'destroy'])->name('region.destroy');

Route::get('/data_patrol', function () {
    return view('admin.data_patrol.data_patrol');
});

Route::get('/jadwal_patrol', function () {
    return view('admin.jadwal_patrol');
});

Route::get('/checkpoint', function () {
    return view('admin.checkpoint.checkpoint');
});

Route::get('/kriteria_checkpoint', function () {
    return view('admin.checkpoint.kriteria_checkpoint');
});

Route::get('/sales_office', function () {
    return view('admin.sales_office.sales_office');
});

Route::get('/user_jadwal', function () {
    return view('user.user_jadwal');
});

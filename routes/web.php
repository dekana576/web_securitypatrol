<?php

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

Route::get('/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/user', function () {
    return view('admin.user');
});
Route::get('/adduser', function () {
    return view('admin.add_user');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/region', function () {
    return view('admin.region');
});

Route::get('/data_patrol', function () {
    return view('admin.data_patrol');
});

Route::get('/jadwal_patrol', function () {
    return view('admin.jadwal_patrol');
});

Route::get('/checkpoint', function () {
    return view('admin.checkpoint');
});

Route::get('/kriteria_checkpoint', function () {
    return view('admin.kriteria_checkpoint');
});

Route::get('/sales_office', function () {
    return view('admin.sales_office');
});

Route::get('/user_jadwal', function () {
    return view('user.user_jadwal');
});

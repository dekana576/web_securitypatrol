<?php

use App\Http\Controllers\Admin\CheckpointController;
use App\Http\Controllers\Admin\CheckpointCriteriaController;
use App\Http\Controllers\Admin\DataPatrolAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\SalesOfficeController;
use App\Http\Controllers\Admin\SecurityScheduleController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Security\DataPatrolController;
use App\Http\Controllers\Security\FeedbackController;
use App\Http\Controllers\Security\UserHomeController;
use App\Http\Controllers\Security\ScanQRController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Security\UserSecurityScheduleController;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['auth'])->group(function () {

    // Group khusus Admin
    Route::middleware(['cek_login:admin'])->group(function () {


        Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
            Route::get('/checkpoint/export/pdf', [CheckpointController::class, 'exportPDF'])->name('export.pdf');

        });

        Route::prefix('security_schedule')->name('security_schedule.')->group(function () {
            Route::get('/', [SecurityScheduleController::class, 'index'])->name('index');
            Route::get('/data', [SecurityScheduleController::class, 'data'])->name('data');
            Route::get('/{regionId}/{salesOfficeId}/{bulan}/{tahun}', [SecurityScheduleController::class, 'show'])->name('show');
            Route::get('/create', [SecurityScheduleController::class, 'create'])->name('create');
            Route::post('/store', [SecurityScheduleController::class, 'store'])->name('store');
            Route::get('/{region}/{salesOffice}/{bulan}/{tahun}/edit', [SecurityScheduleController::class, 'edit'])->name('edit');
            Route::put('/{region}/{sales_office}/{bulan}/{tahun}', [SecurityScheduleController::class, 'update'])->name('update');
            Route::delete('/{regionId}/{salesOfficeId}/{bulan}/{tahun}', [SecurityScheduleController::class, 'destroy'])->name('destroy');


        });

        Route::prefix('checkpoint-criteria')->name('checkpoint_criteria.')->group(function () {
            Route::get('/{checkpointId}', [CheckpointCriteriaController::class, 'index'])->name('index');
            Route::post('/{checkpointId}', [CheckpointCriteriaController::class, 'store'])->name('store');
            Route::delete('/{id}', [CheckpointCriteriaController::class, 'destroy'])->name('destroy');
        });


        Route::prefix('data_patrol')->name('data_patrol.')->group(function () {
            Route::get('/', [DataPatrolAdminController::class, 'index'])->name('index');
            Route::get('/data', [DataPatrolAdminController::class, 'getData'])->name('data');
            Route::delete('/{id}', [DataPatrolAdminController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/view', [DataPatrolAdminController::class, 'show'])->name('show');
            Route::put('/{id}/approve', [DataPatrolAdminController::class, 'approve'])->name('approve');
            Route::put('/{id}/approveView', [DataPatrolAdminController::class, 'approveView'])->name('approve');
            Route::put('/{id}/feedback', [DataPatrolAdminController::class, 'updateFeedback'])->name('feedback');



        });

    });

    // Group khusus Security (jika ingin ditambahkan route khusus security)
    Route::middleware(['cek_login:security'])->group(function () {
        // Halaman utama security
        Route::get('/security', [UserHomeController::class, 'index'])->name('user.home');
        Route::get('/scan-qr', [ScanQRController::class, 'form'])->name('user.scan.qr');
        Route::get('/scan-qr/result', [ScanQRController::class, 'result'])->name('user.scan.qr.result');
        Route::get('/patrol/{checkpoint}/create', [DataPatrolController::class, 'create'])->name('patrol.create');
        Route::post('/patrol', [DataPatrolController::class, 'store'])->name('patrol.store');
        Route::get('/jadwal-saya', [UserSecurityScheduleController::class, 'index'])->name('schedule.index');
        
        Route::get('/security/feedback', [FeedbackController::class, 'index'])->name('security.feedback');
        Route::get('/security/feedback/{id}', [FeedbackController::class, 'show'])->name('security.feedback.show');
        Route::put('/feedback/{id}/done', [FeedbackController::class, 'markAsDone'])->name('security.feedback.done');





    });

});

// Dashboard & Auth

Route::prefix('login')->name('login.')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');
    Route::post('/', [AuthController::class, 'Login'])->name('login');
});

Route::get('/logout',[AuthController::class, 'logout'])->name('logout');
Route::get('/get-sales-offices/{regionId}', [AjaxController::class, 'getSalesOfficesByRegion']);
Route::get('/get-patrol-chart/{salesOfficeId}', [AjaxController::class, 'getPatrolChartBySalesOffice']);



Route::get('/debug-patrol/{id}', function ($id) {
    $data = App\Models\DataPatrol::find($id);
    // dd(Storage::disk('public')->exists($data->image));
    dd([
    'image' => $data->image,
    'path' => storage_path('app/public/' . $data->image),
    'exists' => Storage::disk('public')->exists($data->image)
]);
});







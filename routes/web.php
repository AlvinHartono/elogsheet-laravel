<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MstBusinessUnitController;
use App\Http\Controllers\MstPlantController;
use App\Http\Controllers\MstUserController;
use App\Http\Controllers\MstMastervalueController;
use App\Http\Controllers\MstRoleController;
use App\Http\Controllers\RptQualityController;
use App\Http\Controllers\RptLampGlassController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogsheetDryFraController;
/*
|--------------------------------------------------------------------------
| Guest Routes (Login)
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('root');


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */
    Route::resource('business-unit', MstBusinessUnitController::class);
    Route::resource('master-role', MstRoleController::class);
    Route::resource('master-plant', MstPlantController::class);
    Route::resource('user', MstUserController::class)->only(['index']);
    Route::resource('master-value', MstMastervalueController::class);


    /*
    |--------------------------------------------------------------------------
    | Report: F/RFA-001 - Quality Report
    |--------------------------------------------------------------------------
    */

    // ================= PRODUKSI =================
    Route::prefix('report-quality')->name('report-quality.')->group(function () {
        Route::resource('/', RptQualityController::class)->only(['index', 'show'])->names([
            'index' => 'index',
            'show'  => 'show',
        ]);

        // approve / reject per tanggal
        Route::post('/approve-date', [RptQualityController::class, 'approveDate'])->name('approve-date');
        Route::post('/reject-date', [RptQualityController::class, 'rejectDate'])->name('reject-date');

        // approve / reject per tiket
        Route::post('/{id}/approve', [RptQualityController::class, 'approveTicket'])->name('approve');
        Route::post('/{id}/reject', [RptQualityController::class, 'rejectTicket'])->name('reject');

        // export
        Route::get('/preview', [RptQualityController::class, 'exportLayoutPreview'])->name('export.view');
        Route::get('/excel', [RptQualityController::class, 'exportExcel'])->name('export.excel');
        Route::get('/pdf', [RptQualityController::class, 'exportPdf'])->name('export.pdf');
    });

    // ================= QC =================
    Route::prefix('report-quality-qc')->name('report-quality.qc.')->group(function () {
        Route::get('/', [RptQualityController::class, 'indexQc'])->name('index');

        // approve / reject per tanggal
        Route::post('/approve-date', [RptQualityController::class, 'approveDateQc'])->name('approve-date');
        Route::post('/reject-date', [RptQualityController::class, 'rejectDateQc'])->name('reject-date');

        // approve / reject per tiket
        Route::post('/{id}/approve', [RptQualityController::class, 'approveTicketQc'])->name('approve');
        Route::post('/{id}/reject', [RptQualityController::class, 'rejectTicketQc'])->name('reject');

        // export
        Route::get('/preview', [RptQualityController::class, 'exportLayoutPreviewQc'])->name('export.view');
        Route::get('/pdf', [RptQualityController::class, 'exportPdfQc'])->name('export.pdf');
    });



    /*
    |--------------------------------------------------------------------------
    | Report: F/RFA-013 - Checklist Lamps and Glass Control
    |--------------------------------------------------------------------------
    */
    Route::resource('report-lampnglass', RptLampGlassController::class)->only(['index', 'show']);
    Route::post('/lamp-glass/{id}/approve', [RptLampGlassController::class, 'approve'])->name('lamp_glass.approve');
    Route::post('/lamp-glass/{id}/reject', [RptLampGlassController::class, 'reject'])->name('lamp_glass.reject');
    Route::get('/report-lampnglass-preview', [RptLampGlassController::class, 'exportLayoutPreview'])->name('report-lampnglass.export.view');
    Route::get('/report-lampnglass-excel', [RptLampGlassController::class, 'exportExcel'])->name('report-lampnglass.export');
    Route::get('/report-lampnglass-pdf', [RptLampGlassController::class, 'exportPdf'])->name('report-lampnglass.export.pdf');


    /*
    |--------------------------------------------------------------------------
    | Logsheet: F/RFA-010 - Monitoring Dry Fractionation Plant Logsheet
    |--------------------------------------------------------------------------
    */
    // Produksi
    Route::resource('logsheet-dryfractination', LogsheetDryFraController::class)->only(['index', 'show']);
    Route::post('/logsheet-dryfractination/store', [LogsheetDryFraController::class, 'store'])
        ->name('dryfrac.store');
});

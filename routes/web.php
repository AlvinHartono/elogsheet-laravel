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
use App\Models\MMastervalue;
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
    // Produksi
    Route::resource('report-quality', RptQualityController::class)->only(['index', 'show']);
    Route::post('/report-quality/approve-date', [RptQualityController::class, 'approveDate'])->name('report-quality.approve-date');
    Route::post('/report-quality/reject-date', [RptQualityController::class, 'rejectDate'])->name('report-quality.reject-date');
    Route::get('/report-quality-preview', [RptQualityController::class, 'exportLayoutPreview'])->name('report-quality.export.view');
    Route::get('/report-quality-excel', [RptQualityController::class, 'exportExcel'])->name('report-quality.export');
    Route::get('/report-quality-pdf', [RptQualityController::class, 'exportPdf'])->name('report-quality.export.pdf');

    // QC
    Route::get('/report-quality-qc', [RptQualityController::class, 'indexQc'])->name('report-quality.qc.index');
    Route::get('/report-quality-preview-qc', [RptQualityController::class, 'exportLayoutPreviewQc'])->name('report-quality.qc.export.view');




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
});

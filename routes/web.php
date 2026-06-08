<?php

use App\Http\Controllers\Dashboard\{DashboardController , AuthController };
use App\Http\Controllers\Dashboard\CaseController;
use App\Http\Controllers\Dashboard\CaseExpenseController;
use App\Http\Controllers\Dashboard\CaseFileController;
use App\Http\Controllers\Dashboard\CasePaymentController;
use App\Http\Controllers\Dashboard\CaseSessionController;
use App\Http\Controllers\Dashboard\ClientController;
use App\Http\Controllers\Dashboard\FinanceController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\SettingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('login' , [AuthController::class , 'login' ])->name('login');
Route::post('login/store', [AuthController::class ,'loginCheck']);


Route::group(['middleware'=> 'auth'], function () {
    Route::get('/', [DashboardController::class , 'index'])->name('dashboard');
    Route::apiResource('cases' , CaseController::class) ;
    Route::resource('clients', ClientController::class);
    Route::apiResource('sessions', CaseSessionController::class);
    Route::apiResource('case-payments', CasePaymentController::class);
    Route::apiResource('case-expenses', CaseExpenseController::class);
    Route::patch('/case-expenses/{caseExpense}/pay', [CaseExpenseController::class, 'payExpense']);
    Route::apiResource('case-files', CaseFileController::class);
    Route::get('/case-file/download/{caseFile}', [CaseFileController::class, 'download'])->name('case-files.download');
    Route::get('finances', [FinanceController::class , 'index']);

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/office', [SettingController::class, 'saveOffice'])->name('settings.office');
    Route::put('/settings/personal', [SettingController::class, 'savePersonal'])->name('settings.personal');
    Route::put('/settings/password', [SettingController::class, 'savePassword'])->name('settings.password');


    Route::get('/notifications',             [NotificationController::class, 'index']);
    Route::patch('/notifications/read-all',  [NotificationController::class, 'markAllAsRead']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});

Route::get('/profile', function () {
    return Inertia::render('Profile/Index');
})->name('profile.index');

<?php

use App\Http\Controllers\DailyInputController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Auth;
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



Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    });

    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('employees', EmployeeController::class);
    
    Route::get('/user-emloyee', [EmployeeController::class, 'employeeMerge']);
    Route::get('/employee-data', [EmployeeController::class, 'emloyeesData']);

    Route::resource('products', ProductsController::class);

    Route::resource('daily-input', DailyInputController::class);
    Route::post('/daily-input-detail', [DailyInputController::class, 'detailStore'])->name('daily.input.detail');
    Route::post('/daily-input-detail-edit{id}', [DailyInputController::class, 'detailEdit'])->name('daily.input.detail.edit');
    Route::post('/daily-input/fnssku', [DailyInputController::class, 'checkFnsku'])->name('daily.input.fnsku');
    Route::post('/daily-input-detail-delete{id}', [DailyInputController::class, 'delete'])->name('daily.input.detail.delete');
    Route::get('/report-by-employee', [DailyInputController::class, 'reportByEmployee'])->name('report.by.employee');
    Route::post('/employee-search', [DailyInputController::class, 'reportByEmployee'])->name('employee.search');

    Route::get('/report-by-time', [DailyInputController::class, 'reportByTime'])->name('report.by.time');
    Route::post('/time-search', [DailyInputController::class, 'reportByTime'])->name('time.search');

    Route::get('/monthly-summary', [DailyInputController::class, 'monthlySummary'])->name('monthly.summary');
    Route::post('/summary-search', [DailyInputController::class, 'monthlySummary'])->name('summary.search');
});
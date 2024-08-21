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

    // Route::get('/', function () {
    //     return view('dashboard');
    // });
    Route::get('/', [DailyInputController::class, 'dashboard'])->name('dashboard');

    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('employees', EmployeeController::class);

    // user employee merge
    Route::get('/user-emloyee', [EmployeeController::class, 'employeeMerge']);

    // products data
    Route::get('/products-data/merge', [EmployeeController::class, 'productsData']);

    // daily Input data
    Route::get('/daily/input/merge', [EmployeeController::class, 'dailyInputMerge']);

    // daily Input Detail data
    Route::get('/daily-input-details/merge', [EmployeeController::class, 'dailyInputDetailMerge']);
    Route::post('/update-rate', [EmployeeController::class, 'emplpyeeRate']);

    Route::resource('products', ProductsController::class);

    Route::resource('daily-input', DailyInputController::class);
    Route::post('/daily-input-detail', [DailyInputController::class, 'detailStore'])->name('daily.input.detail');
    Route::post('/daily-input-detail-edit{id}', [DailyInputController::class, 'detailEdit'])->name('daily.input.detail.edit');
    Route::post('/daily-input/fnssku', [DailyInputController::class, 'checkFnsku'])->name('daily.input.fnsku');
    Route::post('/daily-input-detail-delete{id}', [DailyInputController::class, 'delete'])->name('daily.input.detail.delete');

    Route::get('/import/products', [ProductsController::class, 'importProducts'])->name('import.products');
    Route::post('/upload/products', [ProductsController::class, 'upload'])->name('csv.upload');
    Route::post('/saveimp/products', [ProductsController::class, 'saveColumns'])->name('csv.saveColumns');

    Route::get('/delete-duplicate', [ProductsController::class, 'deleteDuplicate'])->name('delete-duplicate');
    
    Route::get('/import/table', [ProductsController::class, 'importTable'])->name('import.table');

    Route::post('/import/csv', [ProductsController::class, 'uploadCSV'])->name('import.csv');
    Route::post('/import/walmart', [ProductsController::class, 'uploadWalmart'])->name('import.walmart');

    Route::get('/report-by-employee', [DailyInputController::class, 'reportByEmployee'])->name('report.by.employee');
    Route::post('/employee-search', [DailyInputController::class, 'reportByEmployee'])->name('employee.search');

    Route::get('/report-by-time', [DailyInputController::class, 'reportByTime'])->name('report.by.time');
    Route::post('/time-search', [DailyInputController::class, 'reportByTime'])->name('time.search');

    Route::get('/monthly-summary', [DailyInputController::class, 'monthlySummary'])->name('monthly.summary');
    Route::post('/summary-search', [DailyInputController::class, 'monthlySummary'])->name('summary.search');

    Route::get('/system-setting', [DailyInputController::class, 'systemSetting'])->name('system.setting');
    Route::post('/system-setting-add', [DailyInputController::class, 'systemSetting'])->name('system.setting.add');
    Route::post('/department-add', [DailyInputController::class, 'depAdd'])->name('department.add');

    Route::post('/temp-products/merge', [ProductsController::class, 'tempProductMerge'])->name('temp.products.merge');
});
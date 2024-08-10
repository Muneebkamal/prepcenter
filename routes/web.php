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
    
    Route::get('/merge-data', [EmployeeController::class, 'mergeData']);

    Route::resource('products', ProductsController::class);

    Route::resource('daily-input', DailyInputController::class);
    Route::post('/daily-input-detail', [DailyInputController::class, 'detailStore'])->name('daily.input.detail');
    Route::post('/daily-input-detail-edit{id}', [DailyInputController::class, 'detailEdit'])->name('daily.input.detail.edit');
    Route::post('/daily-input/fnssku', [DailyInputController::class, 'checkFnsku'])->name('daily.input.fnsku');
    Route::post('/daily-input-detail-delete{id}', [DailyInputController::class, 'delete'])->name('daily.input.detail.delete');

});
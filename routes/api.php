<?php

use App\Http\Controllers\Admin\Table\TableBookController;
use App\Http\Controllers\Admin\Table\TableController;
use App\Http\Controllers\Admin\Statistical\StatisticalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/table-book/table-details/{id}', [TableBookController::class, 'getTableDetails']);
Route::get('/dishes/filter', [TableBookController::class, 'filterDishes'])->name('dishes.filter');
Route::post('/admin/statistical/filter', [StatisticalController::class, 'filter'])->name('statistical.filter');

Route::post('/admin/statistical/filterSoDoanhThuThang', [StatisticalController::class, 'filterSoDoanhThuThang'])->name('statistical.filterSoDoanhThuThang');

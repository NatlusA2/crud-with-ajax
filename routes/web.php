<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\DatabaseConnectionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('ajax-crud-datatable', [productController::class, 'index']);
Route::post('store', [productController::class, 'store']);

Route::get('/check-db', [DatabaseConnectionController::class, 'check']);
Route::post('edit', [productController::class, 'edit']);
Route::post('delete', [EmployeeController::class, 'destroy']);
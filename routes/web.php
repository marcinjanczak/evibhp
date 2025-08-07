<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Start;

Route::get('/', [Start::class, 'index']);


Route::get('/employees', [EmployeeController::class, 'index']);
Route::get('/rentals', [RentalController::class, 'index']);
Route::get('/items', [ItemController::class, 'index']);
Route::resource('items', \App\Http\Controllers\ItemController::class);


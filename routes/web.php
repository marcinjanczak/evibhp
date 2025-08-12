<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Start;

Route::get('/', [Start::class, 'index']);
Route::resource('employees', EmployeeController::class);
Route::resource('items', ItemController::class);
Route::resource('rentals', RentalController::class);
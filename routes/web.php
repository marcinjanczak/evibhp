<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Start;

Route::get('/', [Start::class, 'index']);


// Routy Dla Employee
Route::resource('employee', EmployeeController::class);
Route::get('/employees', [EmployeeController::class, 'index'])-> name('employees.index');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])-> name('employees.destroy');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])-> name('employees.edit');
Route::put('/employees/{employee}/edit', [EmployeeController::class, 'update'])-> name('employees.update');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');


// Routy Dla items
// Route::resource('items', ItemController::class);
// Route::get('/items', [ItemController::class, 'index']) -> name('item.index');
// Route::delete('/item/{item}');
// Route::get('/item/{item}/edit'), ItemController::class, 'edit']) -> name('employees.edit');
// Route::put('/item/{item}/edit'), ItemController::class, 'update']) -> name('employees.update');
// Route::get('/item/create', ItemController::class, 'create')->name('item.store');
// Route::post('/item', [ItemController::class, 'store'])->name('item.store');



Route::get('/rentals', [RentalController::class, 'index']);



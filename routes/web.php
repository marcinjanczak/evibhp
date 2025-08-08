<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Start;

Route::get('/', [Start::class, 'index']);

Route::resource('employee', EmployeeController::class);


Route::get('employees', [EmployeeController::class, 'index'])-> name('employees.index');
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])-> name('employees.destroy');
Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])-> name('employees.edit');
Route::put('/employees/{employee}/edit', [EmployeeController::class, 'update'])-> name('employees.update');
Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
Route::get('/wypozyczone/archiwalne', [RentalController::class, 'date'])->name('wypozyczone.date');
Route::get('/wypozyczone/create', [RentalController::class, 'create'])->name('wypozyczone.create');
Route::post('/wypozyczone', [RentalController::class, 'store'])->name('wypozyczone.store');
Route::get('/wypozyczone', [RentalController::class, 'index'])->name('wypozyczone.index');
Route::get('/wypozyczone/{wypozyczone}/edit', [RentalController::class, 'edit'])->name('wypozyczone.edit');
Route::delete('/wypozyczone/{wypozyczone}', [RentalController::class, 'destroy'])->name('wypozyczone.destroy');
Route::resource('wypozyczone', RentalController::class);
Route::post('wypozyczone/{wypozyczone}/return', [RentalController::class, 'markAsReturned'])->name('wypozyczone.return');


Route::put('/wypozyczone/{wypozyczone}/edit', [RentalController::class, 'update'])->name('wypozyczone.update');

Route::get('/rentals', [RentalController::class, 'index']);

Route::get('/items', [ItemController::class, 'index']) -> name('item.index');
Route::resource('items', \App\Http\Controllers\ItemController::class);


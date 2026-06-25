<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Start;

Route::get('/', [Start::class, 'index']);
Route::resource('employees', EmployeeController::class);
Route::resource('items', ProductController::class);
Route::resource('issues', IssueController::class);
Route::resource('positions', PositionController::class);
Route::get('vehicles', [VehicleController::class, 'index'])->name('vehicles.index');


Route::post('/issues/{issue}/archive', [IssueController::class, 'archive'])->name('issues.archive');

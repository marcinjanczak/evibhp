<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Start;
use App\Http\Controllers\Worker;

Route::get('/', [Start::class, 'index']);
// Route::resources('employees', EmployeeController::class)->except(['show']);
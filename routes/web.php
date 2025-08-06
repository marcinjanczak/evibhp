<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Start;
use App\Http\Controllers\Worker;

Route::get('/', [Start::class, 'index']);
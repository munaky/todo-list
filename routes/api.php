<?php

use App\Http\Controllers\ChartController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/todos', [TodoController::class, 'create']);
Route::get('/todos/export', [TodoController::class, 'export']);

Route::get('/chart', [ChartController::class, 'get']);

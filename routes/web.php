<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ImageUploadController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/get', [ImageUploadController::class, 'index']);

Route::post('/customers', [PostController::class, 'store']);

Route::post('/upload', [ImageUploadController::class, 'store']);
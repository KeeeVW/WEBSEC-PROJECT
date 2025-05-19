<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CryptographyController;

Route::get('/cryptography', [CryptographyController::class, 'index'])->name('cryptography');

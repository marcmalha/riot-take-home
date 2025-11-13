<?php

use App\Http\Controllers\EncryptionController;
use App\Http\Controllers\DataSignatureController;
use Illuminate\Support\Facades\Route;

Route::post('/encrypt', [EncryptionController::class, 'encrypt']);
Route::post('/decrypt', [EncryptionController::class, 'decrypt']);
Route::post('/sign', [DataSignatureController::class, 'sign']);
Route::post('/verify', [DataSignatureController::class, 'verify']);

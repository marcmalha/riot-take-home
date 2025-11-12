<?php

use App\Http\Controllers\EncryptionController;
use Illuminate\Support\Facades\Route;

Route::post('/encrypt', [EncryptionController::class, 'encrypt']);

// TODO: Implement endpoints later on
// Route::post('/decrypt', [EncryptionController::class, 'decrypt']);
//
// Route::post('/sign', [SignatureController::class, 'sign']);
//
// Route::post('/verify', [SignatureController::class, 'verify']);

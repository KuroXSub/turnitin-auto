<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Middleware\ValidateUploadToken;

Route::prefix('v1')->group(function () {

    Route::post('/request-token', [TokenController::class, 'requestToken'])
         ->middleware('throttle:1,8');

    Route::post('/upload', [DocumentController::class, 'store'])
         ->middleware([
            'throttle:3,60',
            ValidateUploadToken::class
         ]);

    Route::get('/status/{verification_code}', [StatusController::class, 'checkStatus']);
});
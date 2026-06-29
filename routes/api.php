<?php

use Dpb\Sanctuary\Http\Api\Auth\LoginController;
use Dpb\Sanctuary\Http\Api\Handshake\HandshakeController;
use Dpb\Sanctuary\Http\Api\User\UserInfoController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

$guard = Config::get('sanctuary.auth_guard', 'sanctuary_api');

Route::prefix('v1')->group(function () use ($guard) {

    Route::post('/handshake', HandshakeController::class);

    Route::middleware("auth:{$guard}")->group(function() {
        Route::post('/login', LoginController::class);
        Route::get('/me', UserInfoController::class);
    });
});
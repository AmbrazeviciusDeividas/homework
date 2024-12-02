<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyJobOwnership;
use App\Http\Middleware\ApiKeyMiddleware;


Route::middleware( [ ApiKeyMiddleware::class ] )->group( function () {
    Route::post( '/jobs', [ JobController::class, 'create' ] );
    Route::get( '/jobs/{id}', [ JobController::class, 'retrieve' ] );
    Route::delete( '/jobs/{id}', [ JobController::class, 'delete' ] );
} );

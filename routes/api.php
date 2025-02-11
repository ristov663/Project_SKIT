<?php

use App\Http\Controllers\EventApiController;
use App\Http\Controllers\ReservationApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('events', EventApiController::class);
Route::apiResource('reservations', ReservationApiController::class)->only('store');

Route::put('reservations/{reservation}/cancel', [ReservationApiController::class, 'cancelReservation']);
Route::put('reservations/{reservation}/confirm', [ReservationApiController::class, 'confirmReservation']);

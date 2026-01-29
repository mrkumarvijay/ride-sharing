<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\DriverController;

// Passenger APIs
Route::prefix('passenger')->group(function () {
    Route::post('/create-ride', [PassengerController::class, 'createRide']);
    Route::post('/approve-driver', [PassengerController::class, 'approveDriver']);
    Route::post('/mark-completed', [PassengerController::class, 'markCompleted']);
});

// Driver APIs
Route::prefix('driver')->group(function () {
    Route::post('/update-location', [DriverController::class, 'updateLocation']);
    Route::get('/nearby-rides', [DriverController::class, 'getNearbyRides']);
    Route::post('/request-ride', [DriverController::class, 'requestRide']);
    Route::post('/mark-completed', [DriverController::class, 'markCompleted']);
});

// Admin APIs
Route::get('/admin/rides', [AdminController::class, 'index']);
Route::get('/admin/rides/{id}', [AdminController::class, 'show']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

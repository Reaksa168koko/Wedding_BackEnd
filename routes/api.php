<?php

use App\Http\Controllers\AuthController; 
use App\Http\Controllers\EventsController;
use App\Http\Controllers\GuestsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/



Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });
});


Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->group(function () {
    
    // EVENTS
    Route::get('/events', [EventsController::class, 'index']);
    Route::post('/events', [EventsController::class, 'store']);
    Route::get('/events/{id}', [EventsController::class, 'show']);
    Route::patch('/events/{id}', [EventsController::class, 'update']);
    Route::delete('/events/{id}', [EventsController::class, 'destroy']);

    // GUESTS
    Route::get('/guests', [GuestsController::class, 'index']);
    Route::post('/guests', [GuestsController::class, 'store']);
    Route::get('/guests/{id}', [GuestsController::class, 'show']);
    Route::patch('/guests/{id}', [GuestsController::class, 'update']);
    Route::delete('/guests/{id}', [GuestsController::class, 'destroy']);

    // TRANSACTIONS
    Route::get('/transactions', [TransactionsController::class, 'index']);
    Route::post('/transactions', [TransactionsController::class, 'store']);
    Route::get('/transactions/{id}', [TransactionsController::class, 'show']);
    Route::patch('/transactions/{id}', [TransactionsController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionsController::class, 'destroy']);
    
});
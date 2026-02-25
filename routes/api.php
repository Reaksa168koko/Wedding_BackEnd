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
Route::post('/login', [AuthController::class, 'login']);

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email verified successfully']);
})->middleware(['signed'])->name('verification.verify');



Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });


    Route::post('/email/verify/resend', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent again']); 
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
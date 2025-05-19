<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ComplaintController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

//Complaint
Route::post('/complaints', [ComplaintController::class, 'store']); // Create
Route::get('/complaints', [ComplaintController::class, 'index']);  // Read

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //Items
    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);

    Route::get('/items/search', [ItemController::class, 'search']);

    //Reviews
    Route::get('/reviews/all', [ReviewController::class, 'getAllReviews']);

    // Review terkait item tertentu
    Route::get('/items/{itemId}/reviews', [ReviewController::class, 'index']);
    Route::post('/items/{itemId}/reviews', [ReviewController::class, 'store']);

    // Update & Delete Review (user sendiri)
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
Route::get('/', function () {
    return "yes";
});

Route::get('/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});
Route::prefix('users')->group(function () {
    Route::get('/all', [UserController::class, 'index'])->name('users.all');
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});
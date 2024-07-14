<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::namespace('\App\Http\Controllers')->group(function () {
    Route::post('/orders', [ApiController::class, 'orders'])->name('api.orders');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/api-auth.php';

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('admin', function(){
        return response()->json([
            'message' => 'Welcome to the admin panel!',
        ]);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', function(){
        return response()->json([
            'message' => 'Welcome to the user panel!',
        ]);
    });
});
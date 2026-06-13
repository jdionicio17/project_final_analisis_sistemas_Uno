<?php

use App\Http\Controllers\Api\V1\AdmisionHospitalariaController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('tenant')->group(function (): void {
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
});

Route::middleware(['tenant', 'jwt.auth'])->group(function (): void {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/admisiones/catalogos', [AdmisionHospitalariaController::class, 'catalogos']);
    Route::get('/admisiones', [AdmisionHospitalariaController::class, 'index']);
    Route::post('/admisiones', [AdmisionHospitalariaController::class, 'store']);
    Route::get('/admisiones/{admision}', [AdmisionHospitalariaController::class, 'show']);
    Route::post('/admisiones/{admision}/cancelar', [AdmisionHospitalariaController::class, 'cancelar']);
});

Route::middleware(['tenant', 'jwt.refresh'])->group(function (): void {
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
});
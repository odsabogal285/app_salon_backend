<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group(['prefix' => 'v1'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [LoginController::class, 'register']);
    Route::post('/forgot-password', [LoginController::class, 'forgotPassword']);
    Route::get('/forgot-password/{token}', [LoginController::class, 'verifyPasswordResetToken']);
    Route::post('/forgot-password/{token}', [LoginController::class, 'updatePassword']);
});


Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
    Route::get('/me', [LoginController::class, 'me']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::resource('/services', ServiceController::class);
    Route::resource('/appointments', AppointmentController::class);
    Route::get('/appointments-user', [AppointmentController::class, 'getAppointmentsByUser']);
});



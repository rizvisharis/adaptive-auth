<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IpInfoController;
use App\Http\Controllers\TrainingDataController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/training-data-context', [TrainingDataController::class, 'getContextData']);
Route::get('/training-data-behavior', [TrainingDataController::class, 'getBehaviorData']);
Route::get('/ip-info', [IpInfoController::class, 'getIpInfo']);

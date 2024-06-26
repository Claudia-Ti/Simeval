<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\MonitoringCriteriaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/criteria_by_period/{idPeriod}',[CriteriaController::class,'apiGetCriteriaByPeriod']);
Route::get('/monitoring_criteria_by_period/{idPeriod}',[MonitoringCriteriaController::class,'apiGetMonCriteriaByPeriod']);
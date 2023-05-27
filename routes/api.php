<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SymptomController;
use App\Http\Controllers\Api\DiseaseController;


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


Route::get('/symptom', [SymptomController::class, 'index']);
Route::get('/symptom/getSymptomDiseases', [SymptomController::class, 'getSymptomDiseases']);

Route::get('/disease/getDiseaseSymptoms', [DiseaseController::class, 'getDiseaseSymptoms']);
Route::post('/disease/getDiagnose', [DiseaseController::class, 'getDiagnose']);
Route::post('/disease/getDiseases', [DiseaseController::class, 'getDiseases']);
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SymptomController;
use App\Http\Controllers\Api\DiseaseController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'symptom'], function () {
    Route::get('/', [SymptomController::class, 'index']);
    Route::get('/diseases', [SymptomController::class, 'getSymptomDiseases']);
});

Route::group(['prefix' => 'disease'], function () {
    Route::get('/symptoms', [DiseaseController::class, 'getDiseaseSymptoms']);
    Route::post('/diagnose', [DiseaseController::class, 'getDiagnose']);
    Route::get('/', [DiseaseController::class, 'getDiseases']);
    Route::post('/downloadReport', [DiseaseController::class, 'downloadReport']);
});

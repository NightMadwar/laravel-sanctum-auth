<?php

use App\Http\Controllers\ConsumedMedicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DrugController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\NotificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::apiResource('drugs', DrugController::class);
    Route::apiResource('consumed_medications', ConsumedMedicationController::class);
    Route::put('consumed_medications/{id}', [ConsumedMedicationController::class, 'update']);

    Route::get('consumed_medications2', [ConsumedMedicationController::class, 'getMorningMedications']);
    Route::get('consumed_medications3', [ConsumedMedicationController::class, 'getAfternoonMedications']);
    Route::get('consumed_medications4', [ConsumedMedicationController::class, 'getEveningMedications']);
    Route::get('search', [ConsumedMedicationController::class, 'searchByDrugName']);
    Route::post('medications', [MedicationController::class, 'store']);
    // Get all notifications for a user
Route::get('/notifications', [NotificationController::class, 'index']);

// Create a new notification
Route::post('/notifications', [NotificationController::class, 'store']);
});

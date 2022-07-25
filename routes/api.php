<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/projects', [ProjectController::class, 'getProjects']);
    Route::patch('/project/update', [ProjectController::class, 'update']);
    Route::post('/project/new', [ProjectController::class, 'store']);
    Route::delete('/project/delete/{id}', [ProjectController::class, 'delete'])->where('id', '[0-9]+');
    
    Route::post('/task/new', [TaskController::class, 'store']);
    Route::patch('/task/update', [TaskController::class, 'update']);
    Route::delete('/task/delete/{id}', [TaskController::class, 'delete'])->where('id', '[0-9]+');

});
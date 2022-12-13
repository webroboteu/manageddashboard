<?php

Route::group([
    'middleware' => 'api',
    'prefix' => 'api/v1',
    'namespace' => 'Botble\webrobotdashboard\Http\Controllers\API',
], function () {
    Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/{id}', [ProjectController::class, 'show']);
    Route::post('projects', [ProjectController::class, 'store']);
    Route::put('projects/{id}', [ProjectController::class, 'update']);
    Route::delete('projects/{id}', [ProjectController::class, 'delete']);
    Route::get('tasks/{idProject}', [TaskController::class, 'index']);
    Route::get('tasks/{idProject}/{id}', [TaskController::class, 'show']);
    Route::post('tasks/{idProject}', [TaskController::class, 'store']);
    Route::put('tasks/{idProject}/{id}', [TaskController::class, 'update']);
    Route::delete('tasks/{idProject}/{id}', [TaskController::class, 'delete']);
});
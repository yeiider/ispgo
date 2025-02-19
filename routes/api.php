<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TaskCommentController;
use App\Http\Controllers\Api\TaskAttachmentController;
use App\Http\Controllers\Api\TaskControllerApi;

/*
|--------------------------------------------------------------------------
| Rutas protegidas con auth:api
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('tasks', TaskControllerApi::class);
    Route::apiResource('comments', TaskCommentController::class);
    Route::apiResource('attachments', TaskAttachmentController::class);
});

/*
|--------------------------------------------------------------------------
| Otras rutas sin middleware de autenticación
|--------------------------------------------------------------------------
*/



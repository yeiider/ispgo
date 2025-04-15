<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceApi;
use App\Http\Controllers\Api\TaskAttachmentController;
use App\Http\Controllers\Api\TaskCommentController;
use App\Http\Controllers\Api\TaskControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas protegidas con auth:api
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/v1/invoice/search', [InvoiceApi::class, 'searchInvoices']);
    Route::post('/v1/invoice/pay', [InvoiceApi::class, 'registerPayment']);
    Route::apiResource('tasks', TaskControllerApi::class);
    Route::apiResource('comments', TaskCommentController::class);
    Route::apiResource('attachments', TaskAttachmentController::class);
});

/*
|--------------------------------------------------------------------------
| Otras rutas sin middleware de autenticación
|--------------------------------------------------------------------------
*/

Route::prefix('v1')
    //->middleware('auth:api')
    ->group(base_path('routes/api_v1.php'));



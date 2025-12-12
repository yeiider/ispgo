<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvoiceApi;
use App\Http\Controllers\Api\CotizacionController;
use App\Http\Controllers\Api\McpController;
use App\Http\Controllers\Api\TaskAttachmentController;
use App\Http\Controllers\Api\TaskCommentController;
use App\Http\Controllers\Api\TaskControllerApi;
use App\Http\Controllers\API\FileUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhooks\OnePayWebhookController;

/*
|--------------------------------------------------------------------------
| Rutas protegidas con auth:api
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()->load(['roles', 'permissions'])->withoutRelations();
    });

    // Returns the currently authenticated user including role information
    Route::get('/me', function (Request $request) {
        $user = $request->user();

        // Eager load roles for efficiency
        $user->load('roles');

        // Using Spatie\Permission helpers provided by HasRoles trait
        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect();
        $permissions = method_exists($user, 'getPermissionNames') ? $user->getPermissionNames() : collect();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
            'roles' => $roles,
            // convenience single role (first role if multiple)
            'role' => $roles->first() ?: null,
            'permissions' => $permissions,
            // raw user (without relations) for clients that need extra attributes
            'user' => $user->withoutRelations(),
        ]);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/v1/invoice/search', [InvoiceApi::class, 'searchInvoices']);
    Route::post('/v1/invoice/pay', [InvoiceApi::class, 'registerPayment']);
    Route::post('/v1/cotizaciones', [CotizacionController::class, 'store']);
    Route::apiResource('tasks', TaskControllerApi::class);
    Route::apiResource('comments', TaskCommentController::class);
    Route::apiResource('attachments', TaskAttachmentController::class);

    /*
    |--------------------------------------------------------------------------
    | File Upload Routes - Two-Step Upload Pattern
    |--------------------------------------------------------------------------
    | Endpoints para carga de archivos con el patrón de dos pasos:
    | 1. POST /upload/temp - Carga temporal para previsualización
    | 2. POST /upload/confirm - Confirmar y mover a ubicación permanente
    | 3. DELETE /upload/temp - Eliminar archivo temporal (opcional)
    */
    Route::prefix('upload')->group(function () {
        Route::post('/temp', [FileUploadController::class, 'uploadTemp']);
        Route::post('/confirm', [FileUploadController::class, 'confirmUpload']);
        Route::delete('/temp', [FileUploadController::class, 'deleteTempFile']);
    });
});

/*
|--------------------------------------------------------------------------
| Otras rutas sin middleware de autenticación
|--------------------------------------------------------------------------
*/

Route::post('/login', [\App\Http\Controllers\API\AuthController::class, 'login']);
Route::prefix('v1')
    ->middleware('auth:api')
    ->group(base_path('routes/api_v1.php'));



// OnePay webhook endpoint
Route::post('/webhooks/onepay', [OnePayWebhookController::class, 'handle']);



<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
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



Route::apiResource('/customers', \App\Http\Controllers\API\Customers\CustomerController::class);

Route::apiResource('/addresses', \App\Http\Controllers\API\Customers\AddressController::class);

Route::apiResource('/tax-details', \App\Http\Controllers\API\Customers\TaxDetailController::class);

Route::apiResource('/document-types', \App\Http\Controllers\API\Customers\DocumentTypeController::class);

Route::apiResource('/fiscal-regimes', \App\Http\Controllers\API\Customers\FiscalRegimeController::class);

Route::apiResource('/tax-identification-types', \App\Http\Controllers\API\Customers\TaxIdentificationTypeController::class);

Route::apiResource('/taxpayer-_types', \App\Http\Controllers\API\Customers\TaxpayerTypeController::class);

Route::apiResource('/cash-registers', \App\Http\Controllers\API\Finance\CashRegisterController::class);

Route::apiResource('/expenses', \App\Http\Controllers\API\Finance\ExpenseController::class);

Route::apiResource('/incomes', \App\Http\Controllers\API\Finance\IncomeController::class);

Route::apiResource('/transactions', \App\Http\Controllers\API\Finance\TransactionController::class);

Route::apiResource('/categories', \App\Http\Controllers\API\Inventory\CategoryController::class);

Route::apiResource('/equipment-assignments', \App\Http\Controllers\API\Inventory\EquipmentAssignmentController::class);

Route::apiResource('/products', \App\Http\Controllers\API\Inventory\ProductController::class);

Route::apiResource('/suppliers', \App\Http\Controllers\API\Inventory\SupplierController::class);

Route::apiResource('/warehouses', \App\Http\Controllers\API\Inventory\WarehouseController::class);

Route::apiResource('/credit-notes', \App\Http\Controllers\API\Invoice\CreditNoteController::class);

Route::apiResource('/daily-invoice-balances', \App\Http\Controllers\API\Invoice\DailyInvoiceBalanceController::class);

Route::apiResource('/invoices', \App\Http\Controllers\API\Invoice\InvoiceController::class);

Route::apiResource('/payment-promises', \App\Http\Controllers\API\Invoice\PaymentPromiseController::class);

Route::apiResource('/pages', \App\Http\Controllers\API\PageBuilder\PagesController::class);

Route::apiResource('/page-translations', \App\Http\Controllers\API\PageBuilder\PageTranslationController::class);

Route::apiResource('/plans', \App\Http\Controllers\API\Services\PlanController::class);

Route::apiResource('/services', \App\Http\Controllers\API\Services\ServiceController::class);

Route::apiResource('/service-actions', \App\Http\Controllers\API\Services\ServiceActionController::class);

Route::apiResource('/boards', \App\Http\Controllers\API\SupportTickets\BoardController::class);

Route::apiResource('/columns', \App\Http\Controllers\API\SupportTickets\ColumnController::class);

Route::apiResource('/labels', \App\Http\Controllers\API\SupportTickets\LabelController::class);

Route::apiResource('/label_tasks', \App\Http\Controllers\API\SupportTickets\LabelTaskController::class);

Route::apiResource('/tasks', \App\Http\Controllers\API\SupportTickets\TaskController::class);

Route::apiResource('/task-comments', \App\Http\Controllers\API\SupportTickets\TaskCommentController::class);

Route::apiResource('/boxes', App\Http\Controllers\API\BoxController::class);

Route::apiResource('/contracts', App\Http\Controllers\API\ContractController::class);

Route::apiResource('/daily-boxes', App\Http\Controllers\API\DailyBoxController::class);

Route::apiResource('/email-templates', App\Http\Controllers\API\EmailTemplateController::class);

Route::apiResource('/html-templates', App\Http\Controllers\API\HtmlTemplateController::class);

Route::apiResource('/password-resets', App\Http\Controllers\API\PasswordResetController::class);

Route::apiResource('/tickets', App\Http\Controllers\API\TicketController::class);

Route::apiResource('/users', App\Http\Controllers\API\UserController::class);

<?php
use Illuminate\Support\Facades\Route;


Route::apiResource('/customers', \App\Http\Controllers\API\Customers\CustomerController::class);

Route::apiResource('/addresses', \App\Http\Controllers\API\Customers\AddressController::class);

Route::apiResource('/tax-details', \App\Http\Controllers\API\Customers\TaxDetailController::class);

Route::apiResource('/document-types', \App\Http\Controllers\API\Customers\DocumentTypeController::class);

Route::apiResource('/fiscal-regimes', \App\Http\Controllers\API\Customers\FiscalRegimeController::class);

Route::apiResource('/tax-identification-types', \App\Http\Controllers\API\Customers\TaxIdentificationTypeController::class);

Route::apiResource('/taxpayer-types', \App\Http\Controllers\API\Customers\TaxpayerTypeController::class);

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

Route::apiResource('/task-attachments', \App\Http\Controllers\API\SupportTickets\TaskAttachmentController::class);

Route::apiResource('/boxes', App\Http\Controllers\API\BoxController::class);

Route::apiResource('/contracts', App\Http\Controllers\API\ContractController::class);

Route::apiResource('/daily-boxes', App\Http\Controllers\API\DailyBoxController::class);

Route::apiResource('/email-templates', App\Http\Controllers\API\EmailTemplateController::class);

Route::apiResource('/html-templates', App\Http\Controllers\API\HtmlTemplateController::class);

Route::apiResource('/password-resets', App\Http\Controllers\API\PasswordResetController::class);

Route::apiResource('/tickets', App\Http\Controllers\API\TicketController::class);
Route::put('/tickets/{id}/status', [App\Http\Controllers\API\TicketController::class, 'updateStatus']);

// Ticket Comments
Route::apiResource('/comments', App\Http\Controllers\API\TicketCommentController::class)->except(['index', 'store']);
Route::get('/tickets/{ticket_id}/comments', [App\Http\Controllers\API\TicketCommentController::class, 'index']);
Route::post('/tickets/{ticket_id}/comments', [App\Http\Controllers\API\TicketCommentController::class, 'store']);

// Ticket Attachments
Route::apiResource('/attachments', App\Http\Controllers\API\TicketAttachmentController::class)->except(['store']);
Route::post('/tickets/{ticket_id}/attachments', [App\Http\Controllers\API\TicketAttachmentController::class, 'store']);
Route::post('/comments/{comment_id}/attachments', [App\Http\Controllers\API\TicketAttachmentController::class, 'storeForComment']);
Route::post('/customers', [App\Http\Controllers\Api\CustomerController::class, 'store'])->name('customers.store');

// Mobile App Routes
Route::get('/app-movil/tickets-data', [App\Http\Controllers\API\AppMovil\MobileAppController::class, 'getTicketsData']);
Route::get('/app-movil/services', [App\Http\Controllers\API\AppMovil\MobileAppController::class, 'getServices']);
Route::get('/app-movil/customers', [App\Http\Controllers\API\AppMovil\MobileAppController::class, 'getCustomers']);
Route::get('/app-movil/equipment-assignments', [App\Http\Controllers\API\AppMovil\MobileAppController::class, 'getEquipmentAssignments']);

Route::patch('/app-movil/{service_id}/update-service', [App\Http\Controllers\API\AppMovil\MobileAppController::class, 'updateServiceFields']);

Route::apiResource('/users', App\Http\Controllers\API\UserController::class);

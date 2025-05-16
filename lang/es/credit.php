<?php

return [
    // Common
    'credit' => 'Crédito',
    'credits' => 'Créditos',

    // CreditAccount
    'credit_account' => 'Cuenta de Crédito',
    'credit_accounts' => 'Cuentas de Crédito',
    'customer' => 'Cliente',
    'principal' => 'Principal',
    'interest_rate' => 'Tasa de Interés',
    'grace_period_days' => 'Días de Período de Gracia',
    'status' => 'Estado',
    'related_information' => 'Información Relacionada',
    'products' => 'Productos',
    'installments' => 'Cuotas',
    'payments' => 'Pagos',
    'annual_interest_rate_percentage' => 'Porcentaje de tasa de interés anual',
    'number_of_days_before_applying_penalties' => 'Número de días antes de aplicar penalidades',

    // Status options
    'active' => 'Activo',
    'in_grace' => 'En Período de Gracia',
    'overdue' => 'Vencido',
    'closed' => 'Cerrado',
    'pending' => 'Pendiente',
    'paid' => 'Pagado',

    // CreditAccountProduct
    'product' => 'Producto',
    'quantity' => 'Cantidad',
    'product_details' => 'Detalles del Producto',
    'original_price' => 'Precio Original',
    'unit_price' => 'Precio Unitario',
    'price_difference' => 'Diferencia de Precio',
    'subtotal' => 'Subtotal',
    'select_product_credit' => 'Seleccione el producto que se entregará al cliente a crédito',
    'number_units_credit' => 'Número de unidades que se entregarán al cliente a crédito',
    'regular_price' => 'Precio regular del producto',
    'price_per_unit' => 'Precio por unidad para este producto a crédito (auto-completado con precio regular, pero editable)',
    'difference_price' => 'Diferencia entre el precio unitario y el precio original del producto',
    'total_quantity_price' => 'Total = Cantidad × Precio Unitario (se actualiza automáticamente)',
    'no_product_selected' => 'Ningún producto seleccionado',
    'sku' => 'SKU',
    'brand' => 'Marca',
    'available_qty' => 'Cantidad Disponible',
    'no_difference' => 'Sin diferencia',

    // CreditInstallment
    'credit_installment' => 'Cuota de Crédito',
    'credit_installments' => 'Cuotas de Crédito',
    'due_date' => 'Fecha de Vencimiento',
    'amount_due' => 'Monto a Pagar',
    'principal_portion' => 'Porción Principal',
    'interest_portion' => 'Porción de Interés',
    'account_entries' => 'Entradas de Cuenta',

    // CreditPayment
    'credit_payment' => 'Pago de Crédito',
    'credit_payments' => 'Pagos de Crédito',
    'paid_at' => 'Pagado el',
    'amount' => 'Monto',
    'method' => 'Método',
    'reference' => 'Referencia',
    'notes' => 'Notas',
    'payment_reference' => 'Número de referencia de pago, ID de transacción, etc.',

    // Payment methods
    'cash' => 'Efectivo',
    'bank_transfer' => 'Transferencia Bancaria',
    'credit_card' => 'Tarjeta de Crédito',
    'debit_card' => 'Tarjeta de Débito',
    'check' => 'Cheque',
    'other' => 'Otro',

    // AccountEntry
    'account_entry' => 'Entrada de Cuenta',
    'creditable' => 'Acreditable',
    'entry_type' => 'Tipo de Entrada',
    'balance_after' => 'Saldo Después',
    'created_at' => 'Creado el',
    'updated_at' => 'Actualizado el',

    // Entry types
    'debit' => 'Débito',
    'credit' => 'Crédito',

    // Actions - GrantGracePeriod
    'grant_grace_period' => 'Otorgar Período de Gracia',
    'days' => 'Días',
    'reason' => 'Razón',
    'days_grant_grace' => 'Número de días para otorgar como período de gracia',
    'reason_grant_grace' => 'Razón para otorgar período de gracia',
    'select_one_account' => 'Por favor seleccione solo una cuenta de crédito a la vez.',
    'grace_period_granted' => '¡Período de gracia de :days días otorgado exitosamente!',
    'error_granting_grace' => 'Error al otorgar período de gracia: :message',
    'granted_grace_period' => 'Período de gracia otorgado',

    // Actions - RegisterPayment
    'register_payment' => 'Registrar Pago',
    'payment_registered' => '¡Pago de $:amount registrado exitosamente!',
    'error_registering_payment' => 'Error al registrar pago: :message',
];

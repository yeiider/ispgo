<?php

return [
    // Common
    'credit' => 'Credit',
    'credits' => 'Credits',

    // CreditAccount
    'credit_account' => 'Credit Account',
    'credit_accounts' => 'Credit Accounts',
    'customer' => 'Customer',
    'principal' => 'Principal',
    'interest_rate' => 'Interest Rate',
    'grace_period_days' => 'Grace Period Days',
    'status' => 'Status',
    'related_information' => 'Related Information',
    'products' => 'Products',
    'installments' => 'Installments',
    'payments' => 'Payments',
    'annual_interest_rate_percentage' => 'Annual interest rate percentage',
    'number_of_days_before_applying_penalties' => 'Number of days before applying penalties',

    // Status options
    'active' => 'Active',
    'in_grace' => 'In Grace Period',
    'overdue' => 'Overdue',
    'closed' => 'Closed',
    'pending' => 'Pending',
    'paid' => 'Paid',

    // CreditAccountProduct
    'product' => 'Product',
    'quantity' => 'Quantity',
    'product_details' => 'Product Details',
    'original_price' => 'Original Price',
    'unit_price' => 'Unit Price',
    'price_difference' => 'Price Difference',
    'subtotal' => 'Subtotal',
    'select_product_credit' => 'Select the product to be given to the customer on credit',
    'number_units_credit' => 'Number of units to be given to the customer on credit',
    'regular_price' => 'Regular price of the product',
    'price_per_unit' => 'Price per unit for this product on credit (auto-filled with regular price, but editable)',
    'difference_price' => 'Difference between unit price and original product price',
    'total_quantity_price' => 'Total = Quantity × Unit Price (updates automatically)',
    'no_product_selected' => 'No product selected',
    'sku' => 'SKU',
    'brand' => 'Brand',
    'available_qty' => 'Available Qty',
    'no_difference' => 'No difference',

    // CreditInstallment
    'credit_installment' => 'Credit Installment',
    'credit_installments' => 'Credit Installments',
    'due_date' => 'Due Date',
    'amount_due' => 'Amount Due',
    'principal_portion' => 'Principal Portion',
    'interest_portion' => 'Interest Portion',
    'account_entries' => 'Account Entries',

    // CreditPayment
    'credit_payment' => 'Credit Payment',
    'credit_payments' => 'Credit Payments',
    'paid_at' => 'Paid At',
    'amount' => 'Amount',
    'method' => 'Method',
    'reference' => 'Reference',
    'notes' => 'Notes',
    'payment_reference' => 'Payment reference number, transaction ID, etc.',

    // Payment methods
    'cash' => 'Cash',
    'bank_transfer' => 'Bank Transfer',
    'credit_card' => 'Credit Card',
    'debit_card' => 'Debit Card',
    'check' => 'Check',
    'other' => 'Other',

    // AccountEntry
    'account_entry' => 'Account Entry',
    'creditable' => 'Creditable',
    'entry_type' => 'Entry Type',
    'balance_after' => 'Balance After',
    'created_at' => 'Created At',
    'updated_at' => 'Updated At',

    // Entry types
    'debit' => 'Debit',
    'credit' => 'Credit',

    // Actions - GrantGracePeriod
    'grant_grace_period' => 'Grant Grace Period',
    'days' => 'Days',
    'reason' => 'Reason',
    'days_grant_grace' => 'Number of days to grant as grace period',
    'reason_grant_grace' => 'Reason for granting grace period',
    'select_one_account' => 'Please select only one credit account at a time.',
    'grace_period_granted' => 'Grace period of :days days granted successfully!',
    'error_granting_grace' => 'Error granting grace period: :message',
    'granted_grace_period' => 'Granted grace period',

    // Actions - RegisterPayment
    'register_payment' => 'Register Payment',
    'payment_registered' => 'Payment of $:amount registered successfully!',
    'error_registering_payment' => 'Error registering payment: :message',
];

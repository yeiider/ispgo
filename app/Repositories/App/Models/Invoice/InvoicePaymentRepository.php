<?php

namespace App\Repositories\App\Models\Invoice;

use App\Models\Invoice\InvoicePayment;

class InvoicePaymentRepository
{
    /**
     * @var InvoicePayment
     */
    protected $model;

    /**
     * InvoicePaymentRepository constructor.
     */
    public function __construct(InvoicePayment $model)
    {
        $this->model = $model;
    }

    /**
     * Get all invoice payments.
     */
    public function all()
    {
        return $this->model->with(['invoice', 'user'])->orderBy('id', 'desc')->get();
    }

    /**
     * Get invoice payment by id.
     */
    public function getById($id)
    {
        return $this->model->with(['invoice', 'user'])->findOrFail($id);
    }

    /**
     * Get payments for a specific invoice.
     */
    public function getByInvoiceId($invoiceId)
    {
        return $this->model->where('invoice_id', $invoiceId)
            ->with(['user'])
            ->orderBy('payment_date', 'desc')
            ->get();
    }

    /**
     * Save invoice payment.
     */
    public function save($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update invoice payment.
     */
    public function update($data, $id)
    {
        $payment = $this->getById($id);
        $payment->update($data);
        return $payment;
    }

    /**
     * Delete invoice payment.
     */
    public function delete($id)
    {
        $payment = $this->getById($id);
        return $payment->delete();
    }
}

<?php

namespace App\Services\Invoice;

use App\Repositories\App\Models\Invoice\InvoicePaymentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InvoicePaymentService
{
    /**
     * @var InvoicePaymentRepository
     */
    protected $invoicePaymentRepository;

    /**
     * InvoicePaymentService constructor.
     *
     * @param InvoicePaymentRepository $invoicePaymentRepository
     */
    public function __construct(InvoicePaymentRepository $invoicePaymentRepository)
    {
        $this->invoicePaymentRepository = $invoicePaymentRepository;
    }

    /**
     * Get all invoice payments.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->invoicePaymentRepository->all();
    }

    /**
     * Get invoice payment by id.
     *
     * @param int $id
     * @return InvoicePayment
     */
    public function getById(int $id)
    {
        return $this->invoicePaymentRepository->getById($id);
    }

    /**
     * Get payments for a specific invoice.
     *
     * @param int $invoiceId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByInvoiceId(int $invoiceId)
    {
        return $this->invoicePaymentRepository->getByInvoiceId($invoiceId);
    }

    /**
     * Save invoice payment.
     *
     * @param array $data
     * @return InvoicePayment
     */
    public function save(array $data)
    {
        DB::beginTransaction();
        try {
            $payment = $this->invoicePaymentRepository->save($data);
            
            // Update invoice after payment
            $invoice = $payment->invoice;
            $invoice->amount = $invoice->payments()->sum('amount');
            $invoice->outstanding_balance = $invoice->real_outstanding_balance;
            
            if ($invoice->isFullyPaid()) {
                $invoice->status = 'paid';
                $invoice->outstanding_balance = 0;
            }
            
            $invoice->save();
            
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to save payment: ' . $e->getMessage());
        }
    }

    /**
     * Update invoice payment.
     *
     * @param array $data
     * @param int $id
     * @return InvoicePayment
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $payment = $this->invoicePaymentRepository->update($data, $id);
            
            // Recalculate invoice totals
            $invoice = $payment->invoice;
            $invoice->amount = $invoice->payments()->sum('amount');
            $invoice->outstanding_balance = $invoice->real_outstanding_balance;
            
            if ($invoice->isFullyPaid()) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'unpaid';
            }
            
            $invoice->save();
            
            DB::commit();
            return $payment;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update payment: ' . $e->getMessage());
        }
    }

    /**
     * Delete invoice payment.
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $payment = $this->invoicePaymentRepository->getById($id);
            $invoice = $payment->invoice;
            
            $this->invoicePaymentRepository->delete($id);
            
            // Recalculate invoice totals
            $invoice->amount = $invoice->payments()->sum('amount');
            $invoice->outstanding_balance = $invoice->real_outstanding_balance;
            
            if ($invoice->isFullyPaid()) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'unpaid';
            }
            
            $invoice->save();
            
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete payment: ' . $e->getMessage());
        }
    }
}

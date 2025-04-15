<?php
namespace App\Repositories\App\Models\Invoice;

use App\Models\Invoice\Invoice;

class InvoiceRepository
{
	 /**
     * @var Invoice
     */
    protected Invoice $invoice;

    /**
     * Invoice constructor.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get all invoice.
     *
     * @return Invoice $invoice
     */
    public function all()
    {
        return $this->invoice->get();
    }

     /**
     * Get invoice by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->invoice->find($id);
    }

    /**
     * Save Invoice
     *
     * @param $data
     * @return Invoice
     */
     public function save(array $data)
    {
        return Invoice::create($data);
    }

     /**
     * Update Invoice
     *
     * @param $data
     * @return Invoice
     */
    public function update(array $data, int $id)
    {
        $invoice = $this->invoice->find($id);
        $invoice->update($data);
        return $invoice;
    }

    /**
     * Delete Invoice
     *
     * @param $data
     * @return Invoice
     */
   	 public function delete(int $id)
    {
        $invoice = $this->invoice->find($id);
        $invoice->delete();
        return $invoice;
    }
}

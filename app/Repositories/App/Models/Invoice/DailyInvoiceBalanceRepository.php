<?php
namespace App\Repositories\App\Models\Invoice;

use App\Models\Invoice\DailyInvoiceBalance;

class DailyInvoiceBalanceRepository
{
	 /**
     * @var DailyInvoiceBalance
     */
    protected DailyInvoiceBalance $dailyInvoiceBalance;

    /**
     * DailyInvoiceBalance constructor.
     *
     * @param DailyInvoiceBalance $dailyInvoiceBalance
     */
    public function __construct(DailyInvoiceBalance $dailyInvoiceBalance)
    {
        $this->dailyInvoiceBalance = $dailyInvoiceBalance;
    }

    /**
     * Get all dailyInvoiceBalance.
     *
     * @return DailyInvoiceBalance $dailyInvoiceBalance
     */
    public function all()
    {
        return $this->dailyInvoiceBalance->get();
    }

     /**
     * Get dailyInvoiceBalance by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->dailyInvoiceBalance->find($id);
    }

    /**
     * Save DailyInvoiceBalance
     *
     * @param $data
     * @return DailyInvoiceBalance
     */
     public function save(array $data)
    {
        return DailyInvoiceBalance::create($data);
    }

     /**
     * Update DailyInvoiceBalance
     *
     * @param $data
     * @return DailyInvoiceBalance
     */
    public function update(array $data, int $id)
    {
        $dailyInvoiceBalance = $this->dailyInvoiceBalance->find($id);
        $dailyInvoiceBalance->update($data);
        return $dailyInvoiceBalance;
    }

    /**
     * Delete DailyInvoiceBalance
     *
     * @param $data
     * @return DailyInvoiceBalance
     */
   	 public function delete(int $id)
    {
        $dailyInvoiceBalance = $this->dailyInvoiceBalance->find($id);
        $dailyInvoiceBalance->delete();
        return $dailyInvoiceBalance;
    }
}

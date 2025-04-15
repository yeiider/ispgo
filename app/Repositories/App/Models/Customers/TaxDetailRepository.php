<?php
namespace App\Repositories\App\Models\Customers;

use App\Models\Customers\TaxDetail;

class TaxDetailRepository
{
	 /**
     * @var TaxDetail
     */
    protected TaxDetail $taxDetail;

    /**
     * TaxDetail constructor.
     *
     * @param TaxDetail $taxDetail
     */
    public function __construct(TaxDetail $taxDetail)
    {
        $this->taxDetail = $taxDetail;
    }

    /**
     * Get all taxDetail.
     *
     * @return TaxDetail $taxDetail
     */
    public function all()
    {
        return $this->taxDetail->get();
    }

     /**
     * Get taxDetail by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->taxDetail->find($id);
    }

    /**
     * Save TaxDetail
     *
     * @param $data
     * @return TaxDetail
     */
     public function save(array $data)
    {
        return TaxDetail::create($data);
    }

     /**
     * Update TaxDetail
     *
     * @param $data
     * @return TaxDetail
     */
    public function update(array $data, int $id)
    {
        $taxDetail = $this->taxDetail->find($id);
        $taxDetail->update($data);
        return $taxDetail;
    }

    /**
     * Delete TaxDetail
     *
     * @param $data
     * @return TaxDetail
     */
   	 public function delete(int $id)
    {
        $taxDetail = $this->taxDetail->find($id);
        $taxDetail->delete();
        return $taxDetail;
    }
}

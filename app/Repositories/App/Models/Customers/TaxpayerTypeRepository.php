<?php
namespace App\Repositories\App\Models\Customers;

use App\Models\Customers\TaxpayerType;

class TaxpayerTypeRepository
{
	 /**
     * @var TaxpayerType
     */
    protected TaxpayerType $taxpayerType;

    /**
     * TaxpayerType constructor.
     *
     * @param TaxpayerType $taxpayerType
     */
    public function __construct(TaxpayerType $taxpayerType)
    {
        $this->taxpayerType = $taxpayerType;
    }

    /**
     * Get all taxpayerType.
     *
     * @return TaxpayerType $taxpayerType
     */
    public function all()
    {
        return $this->taxpayerType->get();
    }

     /**
     * Get taxpayerType by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->taxpayerType->find($id);
    }

    /**
     * Save TaxpayerType
     *
     * @param $data
     * @return TaxpayerType
     */
     public function save(array $data)
    {
        return TaxpayerType::create($data);
    }

     /**
     * Update TaxpayerType
     *
     * @param $data
     * @return TaxpayerType
     */
    public function update(array $data, int $id)
    {
        $taxpayerType = $this->taxpayerType->find($id);
        $taxpayerType->update($data);
        return $taxpayerType;
    }

    /**
     * Delete TaxpayerType
     *
     * @param $data
     * @return TaxpayerType
     */
   	 public function delete(int $id)
    {
        $taxpayerType = $this->taxpayerType->find($id);
        $taxpayerType->delete();
        return $taxpayerType;
    }
}

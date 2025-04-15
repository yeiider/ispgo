<?php
namespace App\Repositories\App\Models\Customers;

use App\Models\Customers\TaxIdentificationType;

class TaxIdentificationTypeRepository
{
	 /**
     * @var TaxIdentificationType
     */
    protected TaxIdentificationType $taxIdentificationType;

    /**
     * TaxIdentificationType constructor.
     *
     * @param TaxIdentificationType $taxIdentificationType
     */
    public function __construct(TaxIdentificationType $taxIdentificationType)
    {
        $this->taxIdentificationType = $taxIdentificationType;
    }

    /**
     * Get all taxIdentificationType.
     *
     * @return TaxIdentificationType $taxIdentificationType
     */
    public function all()
    {
        return $this->taxIdentificationType->get();
    }

     /**
     * Get taxIdentificationType by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->taxIdentificationType->find($id);
    }

    /**
     * Save TaxIdentificationType
     *
     * @param $data
     * @return TaxIdentificationType
     */
     public function save(array $data)
    {
        return TaxIdentificationType::create($data);
    }

     /**
     * Update TaxIdentificationType
     *
     * @param $data
     * @return TaxIdentificationType
     */
    public function update(array $data, int $id)
    {
        $taxIdentificationType = $this->taxIdentificationType->find($id);
        $taxIdentificationType->update($data);
        return $taxIdentificationType;
    }

    /**
     * Delete TaxIdentificationType
     *
     * @param $data
     * @return TaxIdentificationType
     */
   	 public function delete(int $id)
    {
        $taxIdentificationType = $this->taxIdentificationType->find($id);
        $taxIdentificationType->delete();
        return $taxIdentificationType;
    }
}

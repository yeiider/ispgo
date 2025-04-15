<?php
namespace App\Repositories\App\Models\Inventory;

use App\Models\Inventory\Supplier;

class SupplierRepository
{
	 /**
     * @var Supplier
     */
    protected Supplier $supplier;

    /**
     * Supplier constructor.
     *
     * @param Supplier $supplier
     */
    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * Get all supplier.
     *
     * @return Supplier $supplier
     */
    public function all()
    {
        return $this->supplier->get();
    }

     /**
     * Get supplier by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->supplier->find($id);
    }

    /**
     * Save Supplier
     *
     * @param $data
     * @return Supplier
     */
     public function save(array $data)
    {
        return Supplier::create($data);
    }

     /**
     * Update Supplier
     *
     * @param $data
     * @return Supplier
     */
    public function update(array $data, int $id)
    {
        $supplier = $this->supplier->find($id);
        $supplier->update($data);
        return $supplier;
    }

    /**
     * Delete Supplier
     *
     * @param $data
     * @return Supplier
     */
   	 public function delete(int $id)
    {
        $supplier = $this->supplier->find($id);
        $supplier->delete();
        return $supplier;
    }
}

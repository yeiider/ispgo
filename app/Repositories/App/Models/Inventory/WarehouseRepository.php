<?php
namespace App\Repositories\App\Models\Inventory;

use App\Models\Inventory\Warehouse;

class WarehouseRepository
{
	 /**
     * @var Warehouse
     */
    protected Warehouse $warehouse;

    /**
     * Warehouse constructor.
     *
     * @param Warehouse $warehouse
     */
    public function __construct(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
    }

    /**
     * Get all warehouse.
     *
     * @return Warehouse $warehouse
     */
    public function all()
    {
        return $this->warehouse->get();
    }

     /**
     * Get warehouse by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->warehouse->find($id);
    }

    /**
     * Save Warehouse
     *
     * @param $data
     * @return Warehouse
     */
     public function save(array $data)
    {
        return Warehouse::create($data);
    }

     /**
     * Update Warehouse
     *
     * @param $data
     * @return Warehouse
     */
    public function update(array $data, int $id)
    {
        $warehouse = $this->warehouse->find($id);
        $warehouse->update($data);
        return $warehouse;
    }

    /**
     * Delete Warehouse
     *
     * @param $data
     * @return Warehouse
     */
   	 public function delete(int $id)
    {
        $warehouse = $this->warehouse->find($id);
        $warehouse->delete();
        return $warehouse;
    }
}

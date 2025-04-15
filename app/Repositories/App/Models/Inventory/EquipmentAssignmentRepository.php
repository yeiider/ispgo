<?php
namespace App\Repositories\App\Models\Inventory;

use App\Models\Inventory\EquipmentAssignment;

class EquipmentAssignmentRepository
{
	 /**
     * @var EquipmentAssignment
     */
    protected EquipmentAssignment $equipmentAssignment;

    /**
     * EquipmentAssignment constructor.
     *
     * @param EquipmentAssignment $equipmentAssignment
     */
    public function __construct(EquipmentAssignment $equipmentAssignment)
    {
        $this->equipmentAssignment = $equipmentAssignment;
    }

    /**
     * Get all equipmentAssignment.
     *
     * @return EquipmentAssignment $equipmentAssignment
     */
    public function all()
    {
        return $this->equipmentAssignment->get();
    }

     /**
     * Get equipmentAssignment by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->equipmentAssignment->find($id);
    }

    /**
     * Save EquipmentAssignment
     *
     * @param $data
     * @return EquipmentAssignment
     */
     public function save(array $data)
    {
        return EquipmentAssignment::create($data);
    }

     /**
     * Update EquipmentAssignment
     *
     * @param $data
     * @return EquipmentAssignment
     */
    public function update(array $data, int $id)
    {
        $equipmentAssignment = $this->equipmentAssignment->find($id);
        $equipmentAssignment->update($data);
        return $equipmentAssignment;
    }

    /**
     * Delete EquipmentAssignment
     *
     * @param $data
     * @return EquipmentAssignment
     */
   	 public function delete(int $id)
    {
        $equipmentAssignment = $this->equipmentAssignment->find($id);
        $equipmentAssignment->delete();
        return $equipmentAssignment;
    }
}

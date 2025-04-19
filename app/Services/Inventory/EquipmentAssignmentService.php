<?php
namespace App\Services\Inventory;

use App\Repositories\App\Models\Inventory\EquipmentAssignmentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class EquipmentAssignmentService
{
	/**
     * @var EquipmentAssignmentRepository $equipmentAssignmentRepository
     */
    protected $equipmentAssignmentRepository;

    /**
     * DummyClass constructor.
     *
     * @param EquipmentAssignmentRepository $equipmentAssignmentRepository
     */
    public function __construct(EquipmentAssignmentRepository $equipmentAssignmentRepository)
    {
        $this->equipmentAssignmentRepository = $equipmentAssignmentRepository;
    }

    /**
     * Get all equipmentAssignmentRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->equipmentAssignmentRepository->all();
    }

    /**
     * Get equipmentAssignmentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->equipmentAssignmentRepository->getById($id);
    }

    /**
     * Validate equipmentAssignmentRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->equipmentAssignmentRepository->save($data);
    }

    /**
     * Update equipmentAssignmentRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $equipmentAssignmentRepository = $this->equipmentAssignmentRepository->update($data, $id);
            DB::commit();
            return $equipmentAssignmentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete equipmentAssignmentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $equipmentAssignmentRepository = $this->equipmentAssignmentRepository->delete($id);
            DB::commit();
            return $equipmentAssignmentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

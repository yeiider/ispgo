<?php
namespace App\Services\Inventory;

use App\Repositories\App\Models\Inventory\WarehouseRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class WarehouseService
{
	/**
     * @var WarehouseRepository $warehouseRepository
     */
    protected $warehouseRepository;

    /**
     * DummyClass constructor.
     *
     * @param WarehouseRepository $warehouseRepository
     */
    public function __construct(WarehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    /**
     * Get all warehouseRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->warehouseRepository->all();
    }

    /**
     * Get warehouseRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->warehouseRepository->getById($id);
    }

    /**
     * Validate warehouseRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->warehouseRepository->save($data);
    }

    /**
     * Update warehouseRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $warehouseRepository = $this->warehouseRepository->update($data, $id);
            DB::commit();
            return $warehouseRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete warehouseRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $warehouseRepository = $this->warehouseRepository->delete($id);
            DB::commit();
            return $warehouseRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

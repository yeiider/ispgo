<?php
namespace App\Services\Inventory;

use App\Repositories\App\Models\Inventory\SupplierRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SupplierService
{
	/**
     * @var SupplierRepository $supplierRepository
     */
    protected $supplierRepository;

    /**
     * DummyClass constructor.
     *
     * @param SupplierRepository $supplierRepository
     */
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Get all supplierRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->supplierRepository->all();
    }

    /**
     * Get supplierRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->supplierRepository->getById($id);
    }

    /**
     * Validate supplierRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->supplierRepository->save($data);
    }

    /**
     * Update supplierRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $supplierRepository = $this->supplierRepository->update($data, $id);
            DB::commit();
            return $supplierRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete supplierRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $supplierRepository = $this->supplierRepository->delete($id);
            DB::commit();
            return $supplierRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

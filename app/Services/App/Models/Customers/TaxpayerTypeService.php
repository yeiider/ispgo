<?php
namespace App\Services\App\Models\Customers;

use App\Models\Customers\TaxpayerType;
use App\Repositories\App\Models\Customers\TaxpayerTypeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TaxpayerTypeService
{
	/**
     * @var TaxpayerTypeRepository $taxpayerTypeRepository
     */
    protected $taxpayerTypeRepository;

    /**
     * DummyClass constructor.
     *
     * @param TaxpayerTypeRepository $taxpayerTypeRepository
     */
    public function __construct(TaxpayerTypeRepository $taxpayerTypeRepository)
    {
        $this->taxpayerTypeRepository = $taxpayerTypeRepository;
    }

    /**
     * Get all taxpayerTypeRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->taxpayerTypeRepository->all();
    }

    /**
     * Get taxpayerTypeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->taxpayerTypeRepository->getById($id);
    }

    /**
     * Validate taxpayerTypeRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->taxpayerTypeRepository->save($data);
    }

    /**
     * Update taxpayerTypeRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $taxpayerTypeRepository = $this->taxpayerTypeRepository->update($data, $id);
            DB::commit();
            return $taxpayerTypeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete taxpayerTypeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $taxpayerTypeRepository = $this->taxpayerTypeRepository->delete($id);
            DB::commit();
            return $taxpayerTypeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

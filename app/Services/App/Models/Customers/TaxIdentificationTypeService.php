<?php
namespace App\Services\App\Models\Customers;

use App\Models\Customers\TaxIdentificationType;
use App\Repositories\App\Models\Customers\TaxIdentificationTypeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TaxIdentificationTypeService
{
	/**
     * @var TaxIdentificationTypeRepository $taxIdentificationTypeRepository
     */
    protected $taxIdentificationTypeRepository;

    /**
     * DummyClass constructor.
     *
     * @param TaxIdentificationTypeRepository $taxIdentificationTypeRepository
     */
    public function __construct(TaxIdentificationTypeRepository $taxIdentificationTypeRepository)
    {
        $this->taxIdentificationTypeRepository = $taxIdentificationTypeRepository;
    }

    /**
     * Get all taxIdentificationTypeRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->taxIdentificationTypeRepository->all();
    }

    /**
     * Get taxIdentificationTypeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->taxIdentificationTypeRepository->getById($id);
    }

    /**
     * Validate taxIdentificationTypeRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->taxIdentificationTypeRepository->save($data);
    }

    /**
     * Update taxIdentificationTypeRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $taxIdentificationTypeRepository = $this->taxIdentificationTypeRepository->update($data, $id);
            DB::commit();
            return $taxIdentificationTypeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete taxIdentificationTypeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $taxIdentificationTypeRepository = $this->taxIdentificationTypeRepository->delete($id);
            DB::commit();
            return $taxIdentificationTypeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

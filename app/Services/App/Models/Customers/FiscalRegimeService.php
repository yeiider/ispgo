<?php
namespace App\Services\App\Models\Customers;

use App\Models\Customers\FiscalRegime;
use App\Repositories\App\Models\Customers\FiscalRegimeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FiscalRegimeService
{
	/**
     * @var FiscalRegimeRepository $fiscalRegimeRepository
     */
    protected $fiscalRegimeRepository;

    /**
     * DummyClass constructor.
     *
     * @param FiscalRegimeRepository $fiscalRegimeRepository
     */
    public function __construct(FiscalRegimeRepository $fiscalRegimeRepository)
    {
        $this->fiscalRegimeRepository = $fiscalRegimeRepository;
    }

    /**
     * Get all fiscalRegimeRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->fiscalRegimeRepository->all();
    }

    /**
     * Get fiscalRegimeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->fiscalRegimeRepository->getById($id);
    }

    /**
     * Validate fiscalRegimeRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->fiscalRegimeRepository->save($data);
    }

    /**
     * Update fiscalRegimeRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $fiscalRegimeRepository = $this->fiscalRegimeRepository->update($data, $id);
            DB::commit();
            return $fiscalRegimeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete fiscalRegimeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $fiscalRegimeRepository = $this->fiscalRegimeRepository->delete($id);
            DB::commit();
            return $fiscalRegimeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

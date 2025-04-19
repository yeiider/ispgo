<?php
namespace App\Services\Finance;

use App\Repositories\App\Models\Finance\IncomeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class IncomeService
{
	/**
     * @var IncomeRepository $incomeRepository
     */
    protected $incomeRepository;

    /**
     * DummyClass constructor.
     *
     * @param IncomeRepository $incomeRepository
     */
    public function __construct(IncomeRepository $incomeRepository)
    {
        $this->incomeRepository = $incomeRepository;
    }

    /**
     * Get all incomeRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->incomeRepository->all();
    }

    /**
     * Get incomeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->incomeRepository->getById($id);
    }

    /**
     * Validate incomeRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->incomeRepository->save($data);
    }

    /**
     * Update incomeRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $incomeRepository = $this->incomeRepository->update($data, $id);
            DB::commit();
            return $incomeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete incomeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $incomeRepository = $this->incomeRepository->delete($id);
            DB::commit();
            return $incomeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

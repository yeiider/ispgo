<?php
namespace App\Services\Finance;

use App\Repositories\App\Models\Finance\ExpenseRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ExpenseService
{
	/**
     * @var ExpenseRepository $expenseRepository
     */
    protected $expenseRepository;

    /**
     * DummyClass constructor.
     *
     * @param ExpenseRepository $expenseRepository
     */
    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Get all expenseRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->expenseRepository->all();
    }

    /**
     * Get expenseRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->expenseRepository->getById($id);
    }

    /**
     * Validate expenseRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->expenseRepository->save($data);
    }

    /**
     * Update expenseRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $expenseRepository = $this->expenseRepository->update($data, $id);
            DB::commit();
            return $expenseRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete expenseRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $expenseRepository = $this->expenseRepository->delete($id);
            DB::commit();
            return $expenseRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

<?php
namespace App\Services\Finance;

use App\Repositories\App\Models\Finance\TransactionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TransactionService
{
	/**
     * @var TransactionRepository $transactionRepository
     */
    protected $transactionRepository;

    /**
     * DummyClass constructor.
     *
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * Get all transactionRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->transactionRepository->all();
    }

    /**
     * Get transactionRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->transactionRepository->getById($id);
    }

    /**
     * Validate transactionRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->transactionRepository->save($data);
    }

    /**
     * Update transactionRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $transactionRepository = $this->transactionRepository->update($data, $id);
            DB::commit();
            return $transactionRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete transactionRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $transactionRepository = $this->transactionRepository->delete($id);
            DB::commit();
            return $transactionRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

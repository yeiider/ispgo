<?php
namespace App\Services;

use App\Models\Contract;
use App\Repositories\ContractRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ContractService
{
	/**
     * @var ContractRepository $contractRepository
     */
    protected $contractRepository;

    /**
     * DummyClass constructor.
     *
     * @param ContractRepository $contractRepository
     */
    public function __construct(ContractRepository $contractRepository)
    {
        $this->contractRepository = $contractRepository;
    }

    /**
     * Get all contractRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->contractRepository->all();
    }

    /**
     * Get contractRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->contractRepository->getById($id);
    }

    /**
     * Validate contractRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->contractRepository->save($data);
    }

    /**
     * Update contractRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $contractRepository = $this->contractRepository->update($data, $id);
            DB::commit();
            return $contractRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete contractRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $contractRepository = $this->contractRepository->delete($id);
            DB::commit();
            return $contractRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

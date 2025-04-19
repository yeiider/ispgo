<?php
namespace App\Services\Finance;

use App\Repositories\App\Models\Finance\CashRegisterRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CashRegisterService
{
	/**
     * @var CashRegisterRepository $cashRegisterRepository
     */
    protected $cashRegisterRepository;

    /**
     * DummyClass constructor.
     *
     * @param CashRegisterRepository $cashRegisterRepository
     */
    public function __construct(CashRegisterRepository $cashRegisterRepository)
    {
        $this->cashRegisterRepository = $cashRegisterRepository;
    }

    /**
     * Get all cashRegisterRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->cashRegisterRepository->all();
    }

    /**
     * Get cashRegisterRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->cashRegisterRepository->getById($id);
    }

    /**
     * Validate cashRegisterRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->cashRegisterRepository->save($data);
    }

    /**
     * Update cashRegisterRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $cashRegisterRepository = $this->cashRegisterRepository->update($data, $id);
            DB::commit();
            return $cashRegisterRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete cashRegisterRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $cashRegisterRepository = $this->cashRegisterRepository->delete($id);
            DB::commit();
            return $cashRegisterRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

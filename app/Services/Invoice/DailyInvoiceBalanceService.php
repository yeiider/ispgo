<?php
namespace App\Services\Invoice;

use App\Repositories\App\Models\Invoice\DailyInvoiceBalanceRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DailyInvoiceBalanceService
{
	/**
     * @var DailyInvoiceBalanceRepository $dailyInvoiceBalanceRepository
     */
    protected $dailyInvoiceBalanceRepository;

    /**
     * DummyClass constructor.
     *
     * @param DailyInvoiceBalanceRepository $dailyInvoiceBalanceRepository
     */
    public function __construct(DailyInvoiceBalanceRepository $dailyInvoiceBalanceRepository)
    {
        $this->dailyInvoiceBalanceRepository = $dailyInvoiceBalanceRepository;
    }

    /**
     * Get all dailyInvoiceBalanceRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->dailyInvoiceBalanceRepository->all();
    }

    /**
     * Get dailyInvoiceBalanceRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->dailyInvoiceBalanceRepository->getById($id);
    }

    /**
     * Validate dailyInvoiceBalanceRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->dailyInvoiceBalanceRepository->save($data);
    }

    /**
     * Update dailyInvoiceBalanceRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $dailyInvoiceBalanceRepository = $this->dailyInvoiceBalanceRepository->update($data, $id);
            DB::commit();
            return $dailyInvoiceBalanceRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete dailyInvoiceBalanceRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $dailyInvoiceBalanceRepository = $this->dailyInvoiceBalanceRepository->delete($id);
            DB::commit();
            return $dailyInvoiceBalanceRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

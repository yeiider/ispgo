<?php
namespace App\Services\Invoice;

use App\Repositories\App\Models\Invoice\PaymentPromiseRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PaymentPromiseService
{
	/**
     * @var PaymentPromiseRepository $paymentPromiseRepository
     */
    protected $paymentPromiseRepository;

    /**
     * DummyClass constructor.
     *
     * @param PaymentPromiseRepository $paymentPromiseRepository
     */
    public function __construct(PaymentPromiseRepository $paymentPromiseRepository)
    {
        $this->paymentPromiseRepository = $paymentPromiseRepository;
    }

    /**
     * Get all paymentPromiseRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->paymentPromiseRepository->all();
    }

    /**
     * Get paymentPromiseRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->paymentPromiseRepository->getById($id);
    }

    /**
     * Validate paymentPromiseRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->paymentPromiseRepository->save($data);
    }

    /**
     * Update paymentPromiseRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $paymentPromiseRepository = $this->paymentPromiseRepository->update($data, $id);
            DB::commit();
            return $paymentPromiseRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete paymentPromiseRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $paymentPromiseRepository = $this->paymentPromiseRepository->delete($id);
            DB::commit();
            return $paymentPromiseRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

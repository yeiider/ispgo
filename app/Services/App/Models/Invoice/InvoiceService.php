<?php
namespace App\Services\App\Models\Invoice;

use App\Models\Invoice\Invoice;
use App\Repositories\App\Models\Invoice\InvoiceRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class InvoiceService
{
	/**
     * @var InvoiceRepository $invoiceRepository
     */
    protected $invoiceRepository;

    /**
     * DummyClass constructor.
     *
     * @param InvoiceRepository $invoiceRepository
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Get all invoiceRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->invoiceRepository->all();
    }

    /**
     * Get invoiceRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->invoiceRepository->getById($id);
    }

    /**
     * Validate invoiceRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->invoiceRepository->save($data);
    }

    /**
     * Update invoiceRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $invoiceRepository = $this->invoiceRepository->update($data, $id);
            DB::commit();
            return $invoiceRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete invoiceRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $invoiceRepository = $this->invoiceRepository->delete($id);
            DB::commit();
            return $invoiceRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

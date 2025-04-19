<?php
namespace App\Services\Customers;

use App\Repositories\App\Models\Customers\TaxDetailRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TaxDetailService
{
	/**
     * @var TaxDetailRepository $taxDetailRepository
     */
    protected $taxDetailRepository;

    /**
     * DummyClass constructor.
     *
     * @param TaxDetailRepository $taxDetailRepository
     */
    public function __construct(TaxDetailRepository $taxDetailRepository)
    {
        $this->taxDetailRepository = $taxDetailRepository;
    }

    /**
     * Get all taxDetailRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->taxDetailRepository->all();
    }

    /**
     * Get taxDetailRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->taxDetailRepository->getById($id);
    }

    /**
     * Validate taxDetailRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->taxDetailRepository->save($data);
    }

    /**
     * Update taxDetailRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $taxDetailRepository = $this->taxDetailRepository->update($data, $id);
            DB::commit();
            return $taxDetailRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete taxDetailRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $taxDetailRepository = $this->taxDetailRepository->delete($id);
            DB::commit();
            return $taxDetailRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

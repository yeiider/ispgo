<?php
namespace App\Services\Customers;

use App\Repositories\App\Models\Customers\CustomerRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CustomerService
{
	/**
     * @var CustomerRepository $customerRepository
     */
    protected $customerRepository;

    /**
     * DummyClass constructor.
     *
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Get all customerRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->customerRepository->all();
    }

    /**
     * Get customerRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->customerRepository->getById($id);
    }

    /**
     * Validate customerRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->customerRepository->save($data);
    }

    /**
     * Update customerRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $customerRepository = $this->customerRepository->update($data, $id);
            DB::commit();
            return $customerRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete customerRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $customerRepository = $this->customerRepository->delete($id);
            DB::commit();
            return $customerRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

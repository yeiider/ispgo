<?php
namespace App\Repositories\App\Models\Customers;

use App\Models\Customers\Customer;

class CustomerRepository
{
	 /**
     * @var Customer
     */
    protected Customer $customer;

    /**
     * Customer constructor.
     *
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get all customer.
     *
     * @return Customer $customer
     */
    public function all()
    {
        return $this->customer->get();
    }

     /**
     * Get customer by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->customer->find($id);
    }

    /**
     * Save Customer
     *
     * @param $data
     * @return Customer
     */
     public function save(array $data)
    {
        return Customer::create($data);
    }

     /**
     * Update Customer
     *
     * @param $data
     * @return Customer
     */
    public function update(array $data, int $id)
    {
        $customer = $this->customer->find($id);
        $customer->update($data);
        return $customer;
    }

    /**
     * Delete Customer
     *
     * @param $data
     * @return Customer
     */
   	 public function delete(int $id)
    {
        $customer = $this->customer->find($id);
        $customer->delete();
        return $customer;
    }
}

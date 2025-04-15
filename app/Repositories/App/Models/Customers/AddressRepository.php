<?php
namespace App\Repositories\App\Models\Customers;

use App\Models\Customers\Address;

class AddressRepository
{
	 /**
     * @var Address
     */
    protected Address $address;

    /**
     * Address constructor.
     *
     * @param Address $address
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * Get all address.
     *
     * @return Address $address
     */
    public function all()
    {
        return $this->address->get();
    }

     /**
     * Get address by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->address->find($id);
    }

    /**
     * Save Address
     *
     * @param $data
     * @return Address
     */
     public function save(array $data)
    {
        return Address::create($data);
    }

     /**
     * Update Address
     *
     * @param $data
     * @return Address
     */
    public function update(array $data, int $id)
    {
        $address = $this->address->find($id);
        $address->update($data);
        return $address;
    }

    /**
     * Delete Address
     *
     * @param $data
     * @return Address
     */
   	 public function delete(int $id)
    {
        $address = $this->address->find($id);
        $address->delete();
        return $address;
    }
}

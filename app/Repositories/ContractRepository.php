<?php
namespace App\Repositories;

use App\Models\Contract;

class ContractRepository
{
	 /**
     * @var Contract
     */
    protected Contract $contract;

    /**
     * Contract constructor.
     *
     * @param Contract $contract
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get all contract.
     *
     * @return Contract $contract
     */
    public function all()
    {
        return $this->contract->get();
    }

     /**
     * Get contract by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->contract->find($id);
    }

    /**
     * Save Contract
     *
     * @param $data
     * @return Contract
     */
     public function save(array $data)
    {
        return Contract::create($data);
    }

     /**
     * Update Contract
     *
     * @param $data
     * @return Contract
     */
    public function update(array $data, int $id)
    {
        $contract = $this->contract->find($id);
        $contract->update($data);
        return $contract;
    }

    /**
     * Delete Contract
     *
     * @param $data
     * @return Contract
     */
   	 public function delete(int $id)
    {
        $contract = $this->contract->find($id);
        $contract->delete();
        return $contract;
    }
}

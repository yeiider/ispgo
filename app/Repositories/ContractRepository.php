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
     * @param string $id
     * @return mixed
     */
    public function getById(string $id)
    {
        return $this->contract->findOrFail($id);
    }

    /**
     * Save Contract
     *
     * @param array $data
     * @return Contract
     */
     public function save(array $data)
    {
        return Contract::create($data);
    }

     /**
     * Update Contract
     *
     * @param array $data
     * @param string $id
     * @return Contract
     */
    public function update(array $data, string $id)
    {
        $contract = $this->contract->findOrFail($id);
        $contract->update($data);
        return $contract;
    }

    /**
     * Delete Contract
     *
     * @param string $id
     * @return Contract
     */
   	 public function delete(string $id)
    {
        $contract = $this->contract->findOrFail($id);
        $contract->delete();
        return $contract;
    }
}

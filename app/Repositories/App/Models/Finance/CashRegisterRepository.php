<?php
namespace App\Repositories\App\Models\Finance;

use App\Models\Finance\CashRegister;

class CashRegisterRepository
{
	 /**
     * @var CashRegister
     */
    protected CashRegister $cashRegister;

    /**
     * CashRegister constructor.
     *
     * @param CashRegister $cashRegister
     */
    public function __construct(CashRegister $cashRegister)
    {
        $this->cashRegister = $cashRegister;
    }

    /**
     * Get all cashRegister.
     *
     * @return CashRegister $cashRegister
     */
    public function all()
    {
        return $this->cashRegister->get();
    }

     /**
     * Get cashRegister by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->cashRegister->find($id);
    }

    /**
     * Save CashRegister
     *
     * @param $data
     * @return CashRegister
     */
     public function save(array $data)
    {
        return CashRegister::create($data);
    }

     /**
     * Update CashRegister
     *
     * @param $data
     * @return CashRegister
     */
    public function update(array $data, int $id)
    {
        $cashRegister = $this->cashRegister->find($id);
        $cashRegister->update($data);
        return $cashRegister;
    }

    /**
     * Delete CashRegister
     *
     * @param $data
     * @return CashRegister
     */
   	 public function delete(int $id)
    {
        $cashRegister = $this->cashRegister->find($id);
        $cashRegister->delete();
        return $cashRegister;
    }
}

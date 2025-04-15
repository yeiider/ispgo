<?php
namespace App\Repositories\App\Models\Finance;

use App\Models\Finance\Income;

class IncomeRepository
{
	 /**
     * @var Income
     */
    protected Income $income;

    /**
     * Income constructor.
     *
     * @param Income $income
     */
    public function __construct(Income $income)
    {
        $this->income = $income;
    }

    /**
     * Get all income.
     *
     * @return Income $income
     */
    public function all()
    {
        return $this->income->get();
    }

     /**
     * Get income by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->income->find($id);
    }

    /**
     * Save Income
     *
     * @param $data
     * @return Income
     */
     public function save(array $data)
    {
        return Income::create($data);
    }

     /**
     * Update Income
     *
     * @param $data
     * @return Income
     */
    public function update(array $data, int $id)
    {
        $income = $this->income->find($id);
        $income->update($data);
        return $income;
    }

    /**
     * Delete Income
     *
     * @param $data
     * @return Income
     */
   	 public function delete(int $id)
    {
        $income = $this->income->find($id);
        $income->delete();
        return $income;
    }
}

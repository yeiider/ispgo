<?php
namespace App\Repositories\App\Models\Finance;

use App\Models\Finance\Expense;

class ExpenseRepository
{
	 /**
     * @var Expense
     */
    protected Expense $expense;

    /**
     * Expense constructor.
     *
     * @param Expense $expense
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Get all expense.
     *
     * @return Expense $expense
     */
    public function all()
    {
        return $this->expense->get();
    }

     /**
     * Get expense by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->expense->find($id);
    }

    /**
     * Save Expense
     *
     * @param $data
     * @return Expense
     */
     public function save(array $data)
    {
        return Expense::create($data);
    }

     /**
     * Update Expense
     *
     * @param $data
     * @return Expense
     */
    public function update(array $data, int $id)
    {
        $expense = $this->expense->find($id);
        $expense->update($data);
        return $expense;
    }

    /**
     * Delete Expense
     *
     * @param $data
     * @return Expense
     */
   	 public function delete(int $id)
    {
        $expense = $this->expense->find($id);
        $expense->delete();
        return $expense;
    }
}

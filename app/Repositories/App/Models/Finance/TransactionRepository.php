<?php
namespace App\Repositories\App\Models\Finance;

use App\Models\Finance\Transaction;

class TransactionRepository
{
	 /**
     * @var Transaction
     */
    protected Transaction $transaction;

    /**
     * Transaction constructor.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get all transaction.
     *
     * @return Transaction $transaction
     */
    public function all()
    {
        return $this->transaction->get();
    }

     /**
     * Get transaction by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->transaction->find($id);
    }

    /**
     * Save Transaction
     *
     * @param $data
     * @return Transaction
     */
     public function save(array $data)
    {
        return Transaction::create($data);
    }

     /**
     * Update Transaction
     *
     * @param $data
     * @return Transaction
     */
    public function update(array $data, int $id)
    {
        $transaction = $this->transaction->find($id);
        $transaction->update($data);
        return $transaction;
    }

    /**
     * Delete Transaction
     *
     * @param $data
     * @return Transaction
     */
   	 public function delete(int $id)
    {
        $transaction = $this->transaction->find($id);
        $transaction->delete();
        return $transaction;
    }
}

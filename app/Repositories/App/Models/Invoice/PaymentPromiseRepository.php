<?php
namespace App\Repositories\App\Models\Invoice;

use App\Models\Invoice\PaymentPromise;

class PaymentPromiseRepository
{
	 /**
     * @var PaymentPromise
     */
    protected PaymentPromise $paymentPromise;

    /**
     * PaymentPromise constructor.
     *
     * @param PaymentPromise $paymentPromise
     */
    public function __construct(PaymentPromise $paymentPromise)
    {
        $this->paymentPromise = $paymentPromise;
    }

    /**
     * Get all paymentPromise.
     *
     * @return PaymentPromise $paymentPromise
     */
    public function all()
    {
        return $this->paymentPromise->get();
    }

     /**
     * Get paymentPromise by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->paymentPromise->find($id);
    }

    /**
     * Save PaymentPromise
     *
     * @param $data
     * @return PaymentPromise
     */
     public function save(array $data)
    {
        return PaymentPromise::create($data);
    }

     /**
     * Update PaymentPromise
     *
     * @param $data
     * @return PaymentPromise
     */
    public function update(array $data, int $id)
    {
        $paymentPromise = $this->paymentPromise->find($id);
        $paymentPromise->update($data);
        return $paymentPromise;
    }

    /**
     * Delete PaymentPromise
     *
     * @param $data
     * @return PaymentPromise
     */
   	 public function delete(int $id)
    {
        $paymentPromise = $this->paymentPromise->find($id);
        $paymentPromise->delete();
        return $paymentPromise;
    }
}

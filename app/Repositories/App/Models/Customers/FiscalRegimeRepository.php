<?php
namespace App\Repositories\App\Models\Customers;

use App\Models\Customers\FiscalRegime;

class FiscalRegimeRepository
{
	 /**
     * @var FiscalRegime
     */
    protected FiscalRegime $fiscalRegime;

    /**
     * FiscalRegime constructor.
     *
     * @param FiscalRegime $fiscalRegime
     */
    public function __construct(FiscalRegime $fiscalRegime)
    {
        $this->fiscalRegime = $fiscalRegime;
    }

    /**
     * Get all fiscalRegime.
     *
     * @return FiscalRegime $fiscalRegime
     */
    public function all()
    {
        return $this->fiscalRegime->get();
    }

     /**
     * Get fiscalRegime by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->fiscalRegime->find($id);
    }

    /**
     * Save FiscalRegime
     *
     * @param $data
     * @return FiscalRegime
     */
     public function save(array $data)
    {
        return FiscalRegime::create($data);
    }

     /**
     * Update FiscalRegime
     *
     * @param $data
     * @return FiscalRegime
     */
    public function update(array $data, int $id)
    {
        $fiscalRegime = $this->fiscalRegime->find($id);
        $fiscalRegime->update($data);
        return $fiscalRegime;
    }

    /**
     * Delete FiscalRegime
     *
     * @param $data
     * @return FiscalRegime
     */
   	 public function delete(int $id)
    {
        $fiscalRegime = $this->fiscalRegime->find($id);
        $fiscalRegime->delete();
        return $fiscalRegime;
    }
}

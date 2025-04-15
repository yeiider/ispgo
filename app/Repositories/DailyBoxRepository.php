<?php
namespace App\Repositories;

use App\Models\DailyBox;

class DailyBoxRepository
{
	 /**
     * @var DailyBox
     */
    protected DailyBox $dailyBox;

    /**
     * DailyBox constructor.
     *
     * @param DailyBox $dailyBox
     */
    public function __construct(DailyBox $dailyBox)
    {
        $this->dailyBox = $dailyBox;
    }

    /**
     * Get all dailyBox.
     *
     * @return DailyBox $dailyBox
     */
    public function all()
    {
        return $this->dailyBox->get();
    }

     /**
     * Get dailyBox by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->dailyBox->find($id);
    }

    /**
     * Save DailyBox
     *
     * @param $data
     * @return DailyBox
     */
     public function save(array $data)
    {
        return DailyBox::create($data);
    }

     /**
     * Update DailyBox
     *
     * @param $data
     * @return DailyBox
     */
    public function update(array $data, int $id)
    {
        $dailyBox = $this->dailyBox->find($id);
        $dailyBox->update($data);
        return $dailyBox;
    }

    /**
     * Delete DailyBox
     *
     * @param $data
     * @return DailyBox
     */
   	 public function delete(int $id)
    {
        $dailyBox = $this->dailyBox->find($id);
        $dailyBox->delete();
        return $dailyBox;
    }
}

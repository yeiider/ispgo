<?php
namespace App\Repositories;

use App\Models\Box;

class BoxRepository
{
	 /**
     * @var Box
     */
    protected Box $box;

    /**
     * Box constructor.
     *
     * @param Box $box
     */
    public function __construct(Box $box)
    {
        $this->box = $box;
    }

    /**
     * Get all box.
     *
     * @return Box $box
     */
    public function all()
    {
        return $this->box->get();
    }

     /**
     * Get box by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->box->find($id);
    }

    /**
     * Save Box
     *
     * @param $data
     * @return Box
     */
     public function save(array $data)
    {
        return Box::create($data);
    }

     /**
     * Update Box
     *
     * @param $data
     * @return Box
     */
    public function update(array $data, int $id)
    {
        $box = $this->box->find($id);
        $box->update($data);
        return $box;
    }

    /**
     * Delete Box
     *
     * @param $data
     * @return Box
     */
   	 public function delete(int $id)
    {
        $box = $this->box->find($id);
        $box->delete();
        return $box;
    }
}

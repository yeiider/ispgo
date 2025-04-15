<?php
namespace App\Repositories\App\Models\SupportTickets;

use App\Models\SupportTickets\LabelTask;

class LabelTaskRepository
{
	 /**
     * @var LabelTask
     */
    protected LabelTask $labelTask;

    /**
     * LabelTask constructor.
     *
     * @param LabelTask $labelTask
     */
    public function __construct(LabelTask $labelTask)
    {
        $this->labelTask = $labelTask;
    }

    /**
     * Get all labelTask.
     *
     * @return LabelTask $labelTask
     */
    public function all()
    {
        return $this->labelTask->get();
    }

     /**
     * Get labelTask by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->labelTask->find($id);
    }

    /**
     * Save LabelTask
     *
     * @param $data
     * @return LabelTask
     */
     public function save(array $data)
    {
        return LabelTask::create($data);
    }

     /**
     * Update LabelTask
     *
     * @param $data
     * @return LabelTask
     */
    public function update(array $data, int $id)
    {
        $labelTask = $this->labelTask->find($id);
        $labelTask->update($data);
        return $labelTask;
    }

    /**
     * Delete LabelTask
     *
     * @param $data
     * @return LabelTask
     */
   	 public function delete(int $id)
    {
        $labelTask = $this->labelTask->find($id);
        $labelTask->delete();
        return $labelTask;
    }
}

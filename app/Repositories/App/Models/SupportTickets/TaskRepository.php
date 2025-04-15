<?php
namespace App\Repositories\App\Models\SupportTickets;

use App\Models\SupportTickets\Task;

class TaskRepository
{
	 /**
     * @var Task
     */
    protected Task $task;

    /**
     * Task constructor.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get all task.
     *
     * @return Task $task
     */
    public function all()
    {
        return $this->task->get();
    }

     /**
     * Get task by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->task->find($id);
    }

    /**
     * Save Task
     *
     * @param $data
     * @return Task
     */
     public function save(array $data)
    {
        return Task::create($data);
    }

     /**
     * Update Task
     *
     * @param $data
     * @return Task
     */
    public function update(array $data, int $id)
    {
        $task = $this->task->find($id);
        $task->update($data);
        return $task;
    }

    /**
     * Delete Task
     *
     * @param $data
     * @return Task
     */
   	 public function delete(int $id)
    {
        $task = $this->task->find($id);
        $task->delete();
        return $task;
    }
}

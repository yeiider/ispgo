<?php
namespace App\Repositories\App\Models\SupportTickets;

use App\Models\SupportTickets\TaskComment;

class TaskCommentRepository
{
	 /**
     * @var TaskComment
     */
    protected TaskComment $taskComment;

    /**
     * TaskComment constructor.
     *
     * @param TaskComment $taskComment
     */
    public function __construct(TaskComment $taskComment)
    {
        $this->taskComment = $taskComment;
    }

    /**
     * Get all taskComment.
     *
     * @return TaskComment $taskComment
     */
    public function all()
    {
        return $this->taskComment->get();
    }

     /**
     * Get taskComment by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->taskComment->find($id);
    }

    /**
     * Save TaskComment
     *
     * @param $data
     * @return TaskComment
     */
     public function save(array $data)
    {
        return TaskComment::create($data);
    }

     /**
     * Update TaskComment
     *
     * @param $data
     * @return TaskComment
     */
    public function update(array $data, int $id)
    {
        $taskComment = $this->taskComment->find($id);
        $taskComment->update($data);
        return $taskComment;
    }

    /**
     * Delete TaskComment
     *
     * @param $data
     * @return TaskComment
     */
   	 public function delete(int $id)
    {
        $taskComment = $this->taskComment->find($id);
        $taskComment->delete();
        return $taskComment;
    }
}

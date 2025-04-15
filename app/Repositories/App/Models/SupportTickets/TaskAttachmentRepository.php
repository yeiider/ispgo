<?php
namespace App\Repositories\App\Models\SupportTickets;

use App\Models\SupportTickets\TaskAttachment;

class TaskAttachmentRepository
{
	 /**
     * @var TaskAttachment
     */
    protected TaskAttachment $taskAttachment;

    /**
     * TaskAttachment constructor.
     *
     * @param TaskAttachment $taskAttachment
     */
    public function __construct(TaskAttachment $taskAttachment)
    {
        $this->taskAttachment = $taskAttachment;
    }

    /**
     * Get all taskAttachment.
     *
     * @return TaskAttachment $taskAttachment
     */
    public function all()
    {
        return $this->taskAttachment->get();
    }

     /**
     * Get taskAttachment by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->taskAttachment->find($id);
    }

    /**
     * Save TaskAttachment
     *
     * @param $data
     * @return TaskAttachment
     */
     public function save(array $data)
    {
        return TaskAttachment::create($data);
    }

     /**
     * Update TaskAttachment
     *
     * @param $data
     * @return TaskAttachment
     */
    public function update(array $data, int $id)
    {
        $taskAttachment = $this->taskAttachment->find($id);
        $taskAttachment->update($data);
        return $taskAttachment;
    }

    /**
     * Delete TaskAttachment
     *
     * @param $data
     * @return TaskAttachment
     */
   	 public function delete(int $id)
    {
        $taskAttachment = $this->taskAttachment->find($id);
        $taskAttachment->delete();
        return $taskAttachment;
    }
}

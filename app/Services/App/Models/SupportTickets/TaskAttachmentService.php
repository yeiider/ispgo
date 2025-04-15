<?php
namespace App\Services\App\Models\SupportTickets;

use App\Models\SupportTickets\TaskAttachment;
use App\Repositories\App\Models\SupportTickets\TaskAttachmentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TaskAttachmentService
{
	/**
     * @var TaskAttachmentRepository $taskAttachmentRepository
     */
    protected $taskAttachmentRepository;

    /**
     * DummyClass constructor.
     *
     * @param TaskAttachmentRepository $taskAttachmentRepository
     */
    public function __construct(TaskAttachmentRepository $taskAttachmentRepository)
    {
        $this->taskAttachmentRepository = $taskAttachmentRepository;
    }

    /**
     * Get all taskAttachmentRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->taskAttachmentRepository->all();
    }

    /**
     * Get taskAttachmentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->taskAttachmentRepository->getById($id);
    }

    /**
     * Validate taskAttachmentRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->taskAttachmentRepository->save($data);
    }

    /**
     * Update taskAttachmentRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $taskAttachmentRepository = $this->taskAttachmentRepository->update($data, $id);
            DB::commit();
            return $taskAttachmentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete taskAttachmentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $taskAttachmentRepository = $this->taskAttachmentRepository->delete($id);
            DB::commit();
            return $taskAttachmentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

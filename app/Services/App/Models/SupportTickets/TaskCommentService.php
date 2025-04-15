<?php
namespace App\Services\App\Models\SupportTickets;

use App\Models\SupportTickets\TaskComment;
use App\Repositories\App\Models\SupportTickets\TaskCommentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TaskCommentService
{
	/**
     * @var TaskCommentRepository $taskCommentRepository
     */
    protected $taskCommentRepository;

    /**
     * DummyClass constructor.
     *
     * @param TaskCommentRepository $taskCommentRepository
     */
    public function __construct(TaskCommentRepository $taskCommentRepository)
    {
        $this->taskCommentRepository = $taskCommentRepository;
    }

    /**
     * Get all taskCommentRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->taskCommentRepository->all();
    }

    /**
     * Get taskCommentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->taskCommentRepository->getById($id);
    }

    /**
     * Validate taskCommentRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->taskCommentRepository->save($data);
    }

    /**
     * Update taskCommentRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $taskCommentRepository = $this->taskCommentRepository->update($data, $id);
            DB::commit();
            return $taskCommentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete taskCommentRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $taskCommentRepository = $this->taskCommentRepository->delete($id);
            DB::commit();
            return $taskCommentRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

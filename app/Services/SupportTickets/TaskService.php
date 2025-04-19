<?php
namespace App\Services\SupportTickets;

use App\Repositories\App\Models\SupportTickets\TaskRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TaskService
{
	/**
     * @var TaskRepository $taskRepository
     */
    protected $taskRepository;

    /**
     * DummyClass constructor.
     *
     * @param TaskRepository $taskRepository
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get all taskRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->taskRepository->all();
    }

    /**
     * Get taskRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->taskRepository->getById($id);
    }

    /**
     * Validate taskRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->taskRepository->save($data);
    }

    /**
     * Update taskRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $taskRepository = $this->taskRepository->update($data, $id);
            DB::commit();
            return $taskRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete taskRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $taskRepository = $this->taskRepository->delete($id);
            DB::commit();
            return $taskRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

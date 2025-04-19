<?php
namespace App\Services\SupportTickets;

use App\Repositories\App\Models\SupportTickets\LabelTaskRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LabelTaskService
{
	/**
     * @var LabelTaskRepository $labelTaskRepository
     */
    protected $labelTaskRepository;

    /**
     * DummyClass constructor.
     *
     * @param LabelTaskRepository $labelTaskRepository
     */
    public function __construct(LabelTaskRepository $labelTaskRepository)
    {
        $this->labelTaskRepository = $labelTaskRepository;
    }

    /**
     * Get all labelTaskRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->labelTaskRepository->all();
    }

    /**
     * Get labelTaskRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->labelTaskRepository->getById($id);
    }

    /**
     * Validate labelTaskRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->labelTaskRepository->save($data);
    }

    /**
     * Update labelTaskRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $labelTaskRepository = $this->labelTaskRepository->update($data, $id);
            DB::commit();
            return $labelTaskRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete labelTaskRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $labelTaskRepository = $this->labelTaskRepository->delete($id);
            DB::commit();
            return $labelTaskRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

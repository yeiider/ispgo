<?php
namespace App\Services\App\Models\SupportTickets;

use App\Models\SupportTickets\Board;
use App\Repositories\App\Models\SupportTickets\BoardRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BoardService
{
	/**
     * @var BoardRepository $boardRepository
     */
    protected $boardRepository;

    /**
     * DummyClass constructor.
     *
     * @param BoardRepository $boardRepository
     */
    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    /**
     * Get all boardRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->boardRepository->all();
    }

    /**
     * Get boardRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->boardRepository->getById($id);
    }

    /**
     * Validate boardRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->boardRepository->save($data);
    }

    /**
     * Update boardRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $boardRepository = $this->boardRepository->update($data, $id);
            DB::commit();
            return $boardRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete boardRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $boardRepository = $this->boardRepository->delete($id);
            DB::commit();
            return $boardRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

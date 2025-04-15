<?php
namespace App\Services\App\Models\SupportTickets;

use App\Models\SupportTickets\Column;
use App\Repositories\App\Models\SupportTickets\ColumnRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ColumnService
{
	/**
     * @var ColumnRepository $columnRepository
     */
    protected $columnRepository;

    /**
     * DummyClass constructor.
     *
     * @param ColumnRepository $columnRepository
     */
    public function __construct(ColumnRepository $columnRepository)
    {
        $this->columnRepository = $columnRepository;
    }

    /**
     * Get all columnRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->columnRepository->all();
    }

    /**
     * Get columnRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->columnRepository->getById($id);
    }

    /**
     * Validate columnRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->columnRepository->save($data);
    }

    /**
     * Update columnRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $columnRepository = $this->columnRepository->update($data, $id);
            DB::commit();
            return $columnRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete columnRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $columnRepository = $this->columnRepository->delete($id);
            DB::commit();
            return $columnRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

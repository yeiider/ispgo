<?php
namespace App\Services\SupportTickets;

use App\Repositories\App\Models\SupportTickets\LabelRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class LabelService
{
	/**
     * @var LabelRepository $labelRepository
     */
    protected $labelRepository;

    /**
     * DummyClass constructor.
     *
     * @param LabelRepository $labelRepository
     */
    public function __construct(LabelRepository $labelRepository)
    {
        $this->labelRepository = $labelRepository;
    }

    /**
     * Get all labelRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->labelRepository->all();
    }

    /**
     * Get labelRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->labelRepository->getById($id);
    }

    /**
     * Validate labelRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->labelRepository->save($data);
    }

    /**
     * Update labelRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $labelRepository = $this->labelRepository->update($data, $id);
            DB::commit();
            return $labelRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete labelRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $labelRepository = $this->labelRepository->delete($id);
            DB::commit();
            return $labelRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

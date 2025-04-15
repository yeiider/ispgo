<?php
namespace App\Services;

use App\Models\DailyBox;
use App\Repositories\DailyBoxRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DailyBoxService
{
	/**
     * @var DailyBoxRepository $dailyBoxRepository
     */
    protected $dailyBoxRepository;

    /**
     * DummyClass constructor.
     *
     * @param DailyBoxRepository $dailyBoxRepository
     */
    public function __construct(DailyBoxRepository $dailyBoxRepository)
    {
        $this->dailyBoxRepository = $dailyBoxRepository;
    }

    /**
     * Get all dailyBoxRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->dailyBoxRepository->all();
    }

    /**
     * Get dailyBoxRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->dailyBoxRepository->getById($id);
    }

    /**
     * Validate dailyBoxRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->dailyBoxRepository->save($data);
    }

    /**
     * Update dailyBoxRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $dailyBoxRepository = $this->dailyBoxRepository->update($data, $id);
            DB::commit();
            return $dailyBoxRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete dailyBoxRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $dailyBoxRepository = $this->dailyBoxRepository->delete($id);
            DB::commit();
            return $dailyBoxRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

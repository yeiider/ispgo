<?php
namespace App\Services;

use App\Models\Box;
use App\Repositories\BoxRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BoxService
{
	/**
     * @var BoxRepository $boxRepository
     */
    protected $boxRepository;

    /**
     * DummyClass constructor.
     *
     * @param BoxRepository $boxRepository
     */
    public function __construct(BoxRepository $boxRepository)
    {
        $this->boxRepository = $boxRepository;
    }

    /**
     * Get all boxRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->boxRepository->all();
    }

    /**
     * Get boxRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->boxRepository->getById($id);
    }

    /**
     * Validate boxRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->boxRepository->save($data);
    }

    /**
     * Update boxRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $boxRepository = $this->boxRepository->update($data, $id);
            DB::commit();
            return $boxRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete boxRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $boxRepository = $this->boxRepository->delete($id);
            DB::commit();
            return $boxRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

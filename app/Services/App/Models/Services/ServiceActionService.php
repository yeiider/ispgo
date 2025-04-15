<?php
namespace App\Services\App\Models\Services;

use App\Models\Services\ServiceAction;
use App\Repositories\App\Models\Services\ServiceActionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ServiceActionService
{
	/**
     * @var ServiceActionRepository $serviceActionRepository
     */
    protected $serviceActionRepository;

    /**
     * DummyClass constructor.
     *
     * @param ServiceActionRepository $serviceActionRepository
     */
    public function __construct(ServiceActionRepository $serviceActionRepository)
    {
        $this->serviceActionRepository = $serviceActionRepository;
    }

    /**
     * Get all serviceActionRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->serviceActionRepository->all();
    }

    /**
     * Get serviceActionRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->serviceActionRepository->getById($id);
    }

    /**
     * Validate serviceActionRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->serviceActionRepository->save($data);
    }

    /**
     * Update serviceActionRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $serviceActionRepository = $this->serviceActionRepository->update($data, $id);
            DB::commit();
            return $serviceActionRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete serviceActionRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $serviceActionRepository = $this->serviceActionRepository->delete($id);
            DB::commit();
            return $serviceActionRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

<?php
namespace App\Services\Services;

use App\Repositories\App\Models\Services\ServiceRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ServiceService
{
	/**
     * @var ServiceRepository $serviceRepository
     */
    protected $serviceRepository;

    /**
     * DummyClass constructor.
     *
     * @param ServiceRepository $serviceRepository
     */
    public function __construct(ServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }

    /**
     * Get all serviceRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->serviceRepository->all();
    }

    /**
     * Get serviceRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->serviceRepository->getById($id);
    }

    /**
     * Validate serviceRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->serviceRepository->save($data);
    }

    /**
     * Update serviceRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $serviceRepository = $this->serviceRepository->update($data, $id);
            DB::commit();
            return $serviceRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete serviceRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $serviceRepository = $this->serviceRepository->delete($id);
            DB::commit();
            return $serviceRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

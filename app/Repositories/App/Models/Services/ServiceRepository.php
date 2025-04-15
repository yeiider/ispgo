<?php
namespace App\Repositories\App\Models\Services;

use App\Models\Services\Service;

class ServiceRepository
{
	 /**
     * @var Service
     */
    protected Service $service;

    /**
     * Service constructor.
     *
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Get all service.
     *
     * @return Service $service
     */
    public function all()
    {
        return $this->service->get();
    }

     /**
     * Get service by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->service->find($id);
    }

    /**
     * Save Service
     *
     * @param $data
     * @return Service
     */
     public function save(array $data)
    {
        return Service::create($data);
    }

     /**
     * Update Service
     *
     * @param $data
     * @return Service
     */
    public function update(array $data, int $id)
    {
        $service = $this->service->find($id);
        $service->update($data);
        return $service;
    }

    /**
     * Delete Service
     *
     * @param $data
     * @return Service
     */
   	 public function delete(int $id)
    {
        $service = $this->service->find($id);
        $service->delete();
        return $service;
    }
}

<?php
namespace App\Repositories\App\Models\Services;

use App\Models\Services\ServiceAction;

class ServiceActionRepository
{
	 /**
     * @var ServiceAction
     */
    protected ServiceAction $serviceAction;

    /**
     * ServiceAction constructor.
     *
     * @param ServiceAction $serviceAction
     */
    public function __construct(ServiceAction $serviceAction)
    {
        $this->serviceAction = $serviceAction;
    }

    /**
     * Get all serviceAction.
     *
     * @return ServiceAction $serviceAction
     */
    public function all()
    {
        return $this->serviceAction->get();
    }

     /**
     * Get serviceAction by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->serviceAction->find($id);
    }

    /**
     * Save ServiceAction
     *
     * @param $data
     * @return ServiceAction
     */
     public function save(array $data)
    {
        return ServiceAction::create($data);
    }

     /**
     * Update ServiceAction
     *
     * @param $data
     * @return ServiceAction
     */
    public function update(array $data, int $id)
    {
        $serviceAction = $this->serviceAction->find($id);
        $serviceAction->update($data);
        return $serviceAction;
    }

    /**
     * Delete ServiceAction
     *
     * @param $data
     * @return ServiceAction
     */
   	 public function delete(int $id)
    {
        $serviceAction = $this->serviceAction->find($id);
        $serviceAction->delete();
        return $serviceAction;
    }
}

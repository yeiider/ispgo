<?php
namespace App\Repositories\App\Models\SupportTickets;

use App\Models\SupportTickets\Label;

class LabelRepository
{
	 /**
     * @var Label
     */
    protected Label $label;

    /**
     * Label constructor.
     *
     * @param Label $label
     */
    public function __construct(Label $label)
    {
        $this->label = $label;
    }

    /**
     * Get all label.
     *
     * @return Label $label
     */
    public function all()
    {
        return $this->label->get();
    }

     /**
     * Get label by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->label->find($id);
    }

    /**
     * Save Label
     *
     * @param $data
     * @return Label
     */
     public function save(array $data)
    {
        return Label::create($data);
    }

     /**
     * Update Label
     *
     * @param $data
     * @return Label
     */
    public function update(array $data, int $id)
    {
        $label = $this->label->find($id);
        $label->update($data);
        return $label;
    }

    /**
     * Delete Label
     *
     * @param $data
     * @return Label
     */
   	 public function delete(int $id)
    {
        $label = $this->label->find($id);
        $label->delete();
        return $label;
    }
}

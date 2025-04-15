<?php
namespace App\Repositories\App\Models\SupportTickets;

use App\Models\SupportTickets\Column;

class ColumnRepository
{
	 /**
     * @var Column
     */
    protected Column $column;

    /**
     * Column constructor.
     *
     * @param Column $column
     */
    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    /**
     * Get all column.
     *
     * @return Column $column
     */
    public function all()
    {
        return $this->column->get();
    }

     /**
     * Get column by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->column->find($id);
    }

    /**
     * Save Column
     *
     * @param $data
     * @return Column
     */
     public function save(array $data)
    {
        return Column::create($data);
    }

     /**
     * Update Column
     *
     * @param $data
     * @return Column
     */
    public function update(array $data, int $id)
    {
        $column = $this->column->find($id);
        $column->update($data);
        return $column;
    }

    /**
     * Delete Column
     *
     * @param $data
     * @return Column
     */
   	 public function delete(int $id)
    {
        $column = $this->column->find($id);
        $column->delete();
        return $column;
    }
}

<?php
namespace App\Repositories\App\Models\SupportTickets;

use App\Models\SupportTickets\Board;

class BoardRepository
{
	 /**
     * @var Board
     */
    protected Board $board;

    /**
     * Board constructor.
     *
     * @param Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;
    }

    /**
     * Get all board.
     *
     * @return Board $board
     */
    public function all()
    {
        return $this->board->get();
    }

     /**
     * Get board by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->board->find($id);
    }

    /**
     * Save Board
     *
     * @param $data
     * @return Board
     */
     public function save(array $data)
    {
        return Board::create($data);
    }

     /**
     * Update Board
     *
     * @param $data
     * @return Board
     */
    public function update(array $data, int $id)
    {
        $board = $this->board->find($id);
        $board->update($data);
        return $board;
    }

    /**
     * Delete Board
     *
     * @param $data
     * @return Board
     */
   	 public function delete(int $id)
    {
        $board = $this->board->find($id);
        $board->delete();
        return $board;
    }
}

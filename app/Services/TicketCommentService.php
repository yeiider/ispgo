<?php
namespace App\Services;

use App\Models\TicketComment;
use App\Repositories\TicketCommentRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TicketCommentService
{
    /**
     * @var TicketCommentRepository $ticketCommentRepository
     */
    protected $ticketCommentRepository;

    /**
     * TicketCommentService constructor.
     *
     * @param TicketCommentRepository $ticketCommentRepository
     */
    public function __construct(TicketCommentRepository $ticketCommentRepository)
    {
        $this->ticketCommentRepository = $ticketCommentRepository;
    }

    /**
     * Get all comments for a ticket.
     *
     * @param int $ticketId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByTicketId(int $ticketId)
    {
        return $this->ticketCommentRepository->getByTicketId($ticketId);
    }

    /**
     * Get comment by id.
     *
     * @param int $id
     * @return TicketComment
     */
    public function getById(int $id)
    {
        return $this->ticketCommentRepository->getById($id);
    }

    /**
     * Validate comment data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return TicketComment
     */
    public function save(array $data)
    {
        return $this->ticketCommentRepository->save($data);
    }

    /**
     * Update comment data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @param int $id
     * @return TicketComment
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $comment = $this->ticketCommentRepository->update($data, $id);
            DB::commit();
            return $comment;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update comment data');
        }
    }

    /**
     * Delete comment by id.
     *
     * @param int $id
     * @return TicketComment
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $comment = $this->ticketCommentRepository->delete($id);
            DB::commit();
            return $comment;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete comment data');
        }
    }
}

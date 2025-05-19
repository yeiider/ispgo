<?php
namespace App\Repositories;

use App\Models\TicketComment;

class TicketCommentRepository
{
    /**
     * @var TicketComment
     */
    protected TicketComment $ticketComment;

    /**
     * TicketComment constructor.
     *
     * @param TicketComment $ticketComment
     */
    public function __construct(TicketComment $ticketComment)
    {
        $this->ticketComment = $ticketComment;
    }

    /**
     * Get all comments for a ticket.
     *
     * @param int $ticketId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByTicketId(int $ticketId)
    {
        return $this->ticketComment->where('ticket_id', $ticketId)->get();
    }

    /**
     * Get comment by id
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->ticketComment->find($id);
    }

    /**
     * Save TicketComment
     *
     * @param array $data
     * @return TicketComment
     */
    public function save(array $data)
    {
        return TicketComment::create($data);
    }

    /**
     * Update TicketComment
     *
     * @param array $data
     * @param int $id
     * @return TicketComment
     */
    public function update(array $data, int $id)
    {
        $comment = $this->ticketComment->find($id);
        $comment->update($data);
        return $comment;
    }

    /**
     * Delete TicketComment
     *
     * @param int $id
     * @return TicketComment
     */
    public function delete(int $id)
    {
        $comment = $this->ticketComment->find($id);
        $comment->delete();
        return $comment;
    }
}

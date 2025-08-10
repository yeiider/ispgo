<?php
namespace App\Repositories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository
{
	 /**
     * @var Ticket
     */
    protected Ticket $ticket;

    /**
     * Ticket constructor.
     *
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get all ticket.
     *
     * @return Ticket $ticket
     */
    public function all()
    {
        return $this->ticket->get();
    }



     /**
     * Get ticket by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->ticket->find($id);
    }

    /**
     * Save Ticket
     *
     * @param $data
     * @return Ticket
     */
     public function save(array $data)
    {
        return Ticket::create($data);
    }

     /**
     * Update Ticket
     *
     * @param $data
     * @return Ticket
     */
    public function update(array $data, int $id)
    {
        $ticket = $this->ticket->find($id);
        $ticket->update($data);
        return $ticket;
    }

    /**
     * Delete Ticket
     *
     * @param $data
     * @return Ticket
     */
   	 public function delete(int $id)
    {
        $ticket = $this->ticket->find($id);
        $ticket->delete();
        return $ticket;
    }

    /**
     * Get tickets for authenticated user
     *
     * @return Collection
     */
    public function forAuthenticatedUser()
    {
        return $this->ticket->forAuthenticatedUser();
    }
}

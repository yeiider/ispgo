<?php
namespace App\Services;

use App\Models\Ticket;
use App\Repositories\TicketRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class TicketService
{
	/**
     * @var TicketRepository $ticketRepository
     */
    protected $ticketRepository;

    /**
     * DummyClass constructor.
     *
     * @param TicketRepository $ticketRepository
     */
    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Get all ticketRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->ticketRepository->all();
    }

    /**
     * Get ticketRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->ticketRepository->getById($id);
    }

    /**
     * Validate ticketRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->ticketRepository->save($data);
    }

    /**
     * Update ticketRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $ticketRepository = $this->ticketRepository->update($data, $id);
            DB::commit();
            return $ticketRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete ticketRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $ticketRepository = $this->ticketRepository->delete($id);
            DB::commit();
            return $ticketRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

<?php
namespace App\Services\App\Models\Invoice;

use App\Models\Invoice\CreditNote;
use App\Repositories\App\Models\Invoice\CreditNoteRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CreditNoteService
{
	/**
     * @var CreditNoteRepository $creditNoteRepository
     */
    protected $creditNoteRepository;

    /**
     * DummyClass constructor.
     *
     * @param CreditNoteRepository $creditNoteRepository
     */
    public function __construct(CreditNoteRepository $creditNoteRepository)
    {
        $this->creditNoteRepository = $creditNoteRepository;
    }

    /**
     * Get all creditNoteRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->creditNoteRepository->all();
    }

    /**
     * Get creditNoteRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->creditNoteRepository->getById($id);
    }

    /**
     * Validate creditNoteRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->creditNoteRepository->save($data);
    }

    /**
     * Update creditNoteRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $creditNoteRepository = $this->creditNoteRepository->update($data, $id);
            DB::commit();
            return $creditNoteRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete creditNoteRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $creditNoteRepository = $this->creditNoteRepository->delete($id);
            DB::commit();
            return $creditNoteRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

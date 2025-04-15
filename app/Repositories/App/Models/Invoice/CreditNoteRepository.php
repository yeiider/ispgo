<?php
namespace App\Repositories\App\Models\Invoice;

use App\Models\Invoice\CreditNote;

class CreditNoteRepository
{
	 /**
     * @var CreditNote
     */
    protected CreditNote $creditNote;

    /**
     * CreditNote constructor.
     *
     * @param CreditNote $creditNote
     */
    public function __construct(CreditNote $creditNote)
    {
        $this->creditNote = $creditNote;
    }

    /**
     * Get all creditNote.
     *
     * @return CreditNote $creditNote
     */
    public function all()
    {
        return $this->creditNote->get();
    }

     /**
     * Get creditNote by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->creditNote->find($id);
    }

    /**
     * Save CreditNote
     *
     * @param $data
     * @return CreditNote
     */
     public function save(array $data)
    {
        return CreditNote::create($data);
    }

     /**
     * Update CreditNote
     *
     * @param $data
     * @return CreditNote
     */
    public function update(array $data, int $id)
    {
        $creditNote = $this->creditNote->find($id);
        $creditNote->update($data);
        return $creditNote;
    }

    /**
     * Delete CreditNote
     *
     * @param $data
     * @return CreditNote
     */
   	 public function delete(int $id)
    {
        $creditNote = $this->creditNote->find($id);
        $creditNote->delete();
        return $creditNote;
    }
}

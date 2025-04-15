<?php
namespace App\Repositories\App\Models\Customers;

use App\Models\Customers\DocumentType;

class DocumentTypeRepository
{
	 /**
     * @var DocumentType
     */
    protected DocumentType $documentType;

    /**
     * DocumentType constructor.
     *
     * @param DocumentType $documentType
     */
    public function __construct(DocumentType $documentType)
    {
        $this->documentType = $documentType;
    }

    /**
     * Get all documentType.
     *
     * @return DocumentType $documentType
     */
    public function all()
    {
        return $this->documentType->get();
    }

     /**
     * Get documentType by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->documentType->find($id);
    }

    /**
     * Save DocumentType
     *
     * @param $data
     * @return DocumentType
     */
     public function save(array $data)
    {
        return DocumentType::create($data);
    }

     /**
     * Update DocumentType
     *
     * @param $data
     * @return DocumentType
     */
    public function update(array $data, int $id)
    {
        $documentType = $this->documentType->find($id);
        $documentType->update($data);
        return $documentType;
    }

    /**
     * Delete DocumentType
     *
     * @param $data
     * @return DocumentType
     */
   	 public function delete(int $id)
    {
        $documentType = $this->documentType->find($id);
        $documentType->delete();
        return $documentType;
    }
}

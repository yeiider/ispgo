<?php
namespace App\Services\App\Models\Customers;

use App\Models\Customers\DocumentType;
use App\Repositories\App\Models\Customers\DocumentTypeRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class DocumentTypeService
{
	/**
     * @var DocumentTypeRepository $documentTypeRepository
     */
    protected $documentTypeRepository;

    /**
     * DummyClass constructor.
     *
     * @param DocumentTypeRepository $documentTypeRepository
     */
    public function __construct(DocumentTypeRepository $documentTypeRepository)
    {
        $this->documentTypeRepository = $documentTypeRepository;
    }

    /**
     * Get all documentTypeRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->documentTypeRepository->all();
    }

    /**
     * Get documentTypeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->documentTypeRepository->getById($id);
    }

    /**
     * Validate documentTypeRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->documentTypeRepository->save($data);
    }

    /**
     * Update documentTypeRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $documentTypeRepository = $this->documentTypeRepository->update($data, $id);
            DB::commit();
            return $documentTypeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete documentTypeRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $documentTypeRepository = $this->documentTypeRepository->delete($id);
            DB::commit();
            return $documentTypeRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

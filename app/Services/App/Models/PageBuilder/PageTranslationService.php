<?php
namespace App\Services\App\Models\PageBuilder;

use App\Models\PageBuilder\PageTranslation;
use App\Repositories\App\Models\PageBuilder\PageTranslationRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PageTranslationService
{
	/**
     * @var PageTranslationRepository $pageTranslationRepository
     */
    protected $pageTranslationRepository;

    /**
     * DummyClass constructor.
     *
     * @param PageTranslationRepository $pageTranslationRepository
     */
    public function __construct(PageTranslationRepository $pageTranslationRepository)
    {
        $this->pageTranslationRepository = $pageTranslationRepository;
    }

    /**
     * Get all pageTranslationRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->pageTranslationRepository->all();
    }

    /**
     * Get pageTranslationRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->pageTranslationRepository->getById($id);
    }

    /**
     * Validate pageTranslationRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->pageTranslationRepository->save($data);
    }

    /**
     * Update pageTranslationRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $pageTranslationRepository = $this->pageTranslationRepository->update($data, $id);
            DB::commit();
            return $pageTranslationRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete pageTranslationRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $pageTranslationRepository = $this->pageTranslationRepository->delete($id);
            DB::commit();
            return $pageTranslationRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

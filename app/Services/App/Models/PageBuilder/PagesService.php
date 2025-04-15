<?php
namespace App\Services\App\Models\PageBuilder;

use App\Models\PageBuilder\Pages;
use App\Repositories\App\Models\PageBuilder\PagesRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PagesService
{
	/**
     * @var PagesRepository $pagesRepository
     */
    protected $pagesRepository;

    /**
     * DummyClass constructor.
     *
     * @param PagesRepository $pagesRepository
     */
    public function __construct(PagesRepository $pagesRepository)
    {
        $this->pagesRepository = $pagesRepository;
    }

    /**
     * Get all pagesRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->pagesRepository->all();
    }

    /**
     * Get pagesRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->pagesRepository->getById($id);
    }

    /**
     * Validate pagesRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->pagesRepository->save($data);
    }

    /**
     * Update pagesRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $pagesRepository = $this->pagesRepository->update($data, $id);
            DB::commit();
            return $pagesRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete pagesRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $pagesRepository = $this->pagesRepository->delete($id);
            DB::commit();
            return $pagesRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

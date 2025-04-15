<?php
namespace App\Services;

use App\Models\HtmlTemplate;
use App\Repositories\HtmlTemplateRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class HtmlTemplateService
{
	/**
     * @var HtmlTemplateRepository $htmlTemplateRepository
     */
    protected $htmlTemplateRepository;

    /**
     * DummyClass constructor.
     *
     * @param HtmlTemplateRepository $htmlTemplateRepository
     */
    public function __construct(HtmlTemplateRepository $htmlTemplateRepository)
    {
        $this->htmlTemplateRepository = $htmlTemplateRepository;
    }

    /**
     * Get all htmlTemplateRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->htmlTemplateRepository->all();
    }

    /**
     * Get htmlTemplateRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->htmlTemplateRepository->getById($id);
    }

    /**
     * Validate htmlTemplateRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->htmlTemplateRepository->save($data);
    }

    /**
     * Update htmlTemplateRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $htmlTemplateRepository = $this->htmlTemplateRepository->update($data, $id);
            DB::commit();
            return $htmlTemplateRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete htmlTemplateRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $htmlTemplateRepository = $this->htmlTemplateRepository->delete($id);
            DB::commit();
            return $htmlTemplateRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

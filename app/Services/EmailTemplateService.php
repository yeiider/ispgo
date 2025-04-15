<?php
namespace App\Services;

use App\Models\EmailTemplate;
use App\Repositories\EmailTemplateRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class EmailTemplateService
{
	/**
     * @var EmailTemplateRepository $emailTemplateRepository
     */
    protected $emailTemplateRepository;

    /**
     * DummyClass constructor.
     *
     * @param EmailTemplateRepository $emailTemplateRepository
     */
    public function __construct(EmailTemplateRepository $emailTemplateRepository)
    {
        $this->emailTemplateRepository = $emailTemplateRepository;
    }

    /**
     * Get all emailTemplateRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->emailTemplateRepository->all();
    }

    /**
     * Get emailTemplateRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->emailTemplateRepository->getById($id);
    }

    /**
     * Validate emailTemplateRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->emailTemplateRepository->save($data);
    }

    /**
     * Update emailTemplateRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $emailTemplateRepository = $this->emailTemplateRepository->update($data, $id);
            DB::commit();
            return $emailTemplateRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete emailTemplateRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $emailTemplateRepository = $this->emailTemplateRepository->delete($id);
            DB::commit();
            return $emailTemplateRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

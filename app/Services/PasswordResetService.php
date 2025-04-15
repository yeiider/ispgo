<?php
namespace App\Services;

use App\Models\PasswordReset;
use App\Repositories\PasswordResetRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PasswordResetService
{
	/**
     * @var PasswordResetRepository $passwordResetRepository
     */
    protected $passwordResetRepository;

    /**
     * DummyClass constructor.
     *
     * @param PasswordResetRepository $passwordResetRepository
     */
    public function __construct(PasswordResetRepository $passwordResetRepository)
    {
        $this->passwordResetRepository = $passwordResetRepository;
    }

    /**
     * Get all passwordResetRepository.
     *
     * @return String
     */
    public function getAll()
    {
        return $this->passwordResetRepository->all();
    }

    /**
     * Get passwordResetRepository by id.
     *
     * @param $id
     * @return String
     */
    public function getById(int $id)
    {
        return $this->passwordResetRepository->getById($id);
    }

    /**
     * Validate passwordResetRepository data.
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function save(array $data)
    {
        return $this->passwordResetRepository->save($data);
    }

    /**
     * Update passwordResetRepository data
     * Store to DB if there are no errors.
     *
     * @param array $data
     * @return String
     */
    public function update(array $data, int $id)
    {
        DB::beginTransaction();
        try {
            $passwordResetRepository = $this->passwordResetRepository->update($data, $id);
            DB::commit();
            return $passwordResetRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to update post data');
        }
    }

    /**
     * Delete passwordResetRepository by id.
     *
     * @param $id
     * @return String
     */
    public function deleteById(int $id)
    {
        DB::beginTransaction();
        try {
            $passwordResetRepository = $this->passwordResetRepository->delete($id);
            DB::commit();
            return $passwordResetRepository;
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            throw new InvalidArgumentException('Unable to delete post data');
        }
    }

}

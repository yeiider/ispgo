<?php
namespace App\Repositories;

use App\Models\PasswordReset;

class PasswordResetRepository
{
	 /**
     * @var PasswordReset
     */
    protected PasswordReset $passwordReset;

    /**
     * PasswordReset constructor.
     *
     * @param PasswordReset $passwordReset
     */
    public function __construct(PasswordReset $passwordReset)
    {
        $this->passwordReset = $passwordReset;
    }

    /**
     * Get all passwordReset.
     *
     * @return PasswordReset $passwordReset
     */
    public function all()
    {
        return $this->passwordReset->get();
    }

     /**
     * Get passwordReset by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->passwordReset->find($id);
    }

    /**
     * Save PasswordReset
     *
     * @param $data
     * @return PasswordReset
     */
     public function save(array $data)
    {
        return PasswordReset::create($data);
    }

     /**
     * Update PasswordReset
     *
     * @param $data
     * @return PasswordReset
     */
    public function update(array $data, int $id)
    {
        $passwordReset = $this->passwordReset->find($id);
        $passwordReset->update($data);
        return $passwordReset;
    }

    /**
     * Delete PasswordReset
     *
     * @param $data
     * @return PasswordReset
     */
   	 public function delete(int $id)
    {
        $passwordReset = $this->passwordReset->find($id);
        $passwordReset->delete();
        return $passwordReset;
    }
}

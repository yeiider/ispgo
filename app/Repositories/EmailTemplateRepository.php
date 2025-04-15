<?php
namespace App\Repositories;

use App\Models\EmailTemplate;

class EmailTemplateRepository
{
	 /**
     * @var EmailTemplate
     */
    protected EmailTemplate $emailTemplate;

    /**
     * EmailTemplate constructor.
     *
     * @param EmailTemplate $emailTemplate
     */
    public function __construct(EmailTemplate $emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * Get all emailTemplate.
     *
     * @return EmailTemplate $emailTemplate
     */
    public function all()
    {
        return $this->emailTemplate->get();
    }

     /**
     * Get emailTemplate by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->emailTemplate->find($id);
    }

    /**
     * Save EmailTemplate
     *
     * @param $data
     * @return EmailTemplate
     */
     public function save(array $data)
    {
        return EmailTemplate::create($data);
    }

     /**
     * Update EmailTemplate
     *
     * @param $data
     * @return EmailTemplate
     */
    public function update(array $data, int $id)
    {
        $emailTemplate = $this->emailTemplate->find($id);
        $emailTemplate->update($data);
        return $emailTemplate;
    }

    /**
     * Delete EmailTemplate
     *
     * @param $data
     * @return EmailTemplate
     */
   	 public function delete(int $id)
    {
        $emailTemplate = $this->emailTemplate->find($id);
        $emailTemplate->delete();
        return $emailTemplate;
    }
}

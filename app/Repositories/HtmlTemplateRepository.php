<?php
namespace App\Repositories;

use App\Models\HtmlTemplate;

class HtmlTemplateRepository
{
	 /**
     * @var HtmlTemplate
     */
    protected HtmlTemplate $htmlTemplate;

    /**
     * HtmlTemplate constructor.
     *
     * @param HtmlTemplate $htmlTemplate
     */
    public function __construct(HtmlTemplate $htmlTemplate)
    {
        $this->htmlTemplate = $htmlTemplate;
    }

    /**
     * Get all htmlTemplate.
     *
     * @return HtmlTemplate $htmlTemplate
     */
    public function all()
    {
        return $this->htmlTemplate->get();
    }

     /**
     * Get htmlTemplate by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->htmlTemplate->find($id);
    }

    /**
     * Save HtmlTemplate
     *
     * @param $data
     * @return HtmlTemplate
     */
     public function save(array $data)
    {
        return HtmlTemplate::create($data);
    }

     /**
     * Update HtmlTemplate
     *
     * @param $data
     * @return HtmlTemplate
     */
    public function update(array $data, int $id)
    {
        $htmlTemplate = $this->htmlTemplate->find($id);
        $htmlTemplate->update($data);
        return $htmlTemplate;
    }

    /**
     * Delete HtmlTemplate
     *
     * @param $data
     * @return HtmlTemplate
     */
   	 public function delete(int $id)
    {
        $htmlTemplate = $this->htmlTemplate->find($id);
        $htmlTemplate->delete();
        return $htmlTemplate;
    }
}

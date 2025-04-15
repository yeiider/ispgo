<?php
namespace App\Repositories\App\Models\PageBuilder;

use App\Models\PageBuilder\PageTranslation;

class PageTranslationRepository
{
	 /**
     * @var PageTranslation
     */
    protected PageTranslation $pageTranslation;

    /**
     * PageTranslation constructor.
     *
     * @param PageTranslation $pageTranslation
     */
    public function __construct(PageTranslation $pageTranslation)
    {
        $this->pageTranslation = $pageTranslation;
    }

    /**
     * Get all pageTranslation.
     *
     * @return PageTranslation $pageTranslation
     */
    public function all()
    {
        return $this->pageTranslation->get();
    }

     /**
     * Get pageTranslation by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->pageTranslation->find($id);
    }

    /**
     * Save PageTranslation
     *
     * @param $data
     * @return PageTranslation
     */
     public function save(array $data)
    {
        return PageTranslation::create($data);
    }

     /**
     * Update PageTranslation
     *
     * @param $data
     * @return PageTranslation
     */
    public function update(array $data, int $id)
    {
        $pageTranslation = $this->pageTranslation->find($id);
        $pageTranslation->update($data);
        return $pageTranslation;
    }

    /**
     * Delete PageTranslation
     *
     * @param $data
     * @return PageTranslation
     */
   	 public function delete(int $id)
    {
        $pageTranslation = $this->pageTranslation->find($id);
        $pageTranslation->delete();
        return $pageTranslation;
    }
}

<?php
namespace App\Repositories\App\Models\PageBuilder;

use App\Models\PageBuilder\Pages;

class PagesRepository
{
	 /**
     * @var Pages
     */
    protected Pages $pages;

    /**
     * Pages constructor.
     *
     * @param Pages $pages
     */
    public function __construct(Pages $pages)
    {
        $this->pages = $pages;
    }

    /**
     * Get all pages.
     *
     * @return Pages $pages
     */
    public function all()
    {
        return $this->pages->get();
    }

     /**
     * Get pages by id
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->pages->find($id);
    }

    /**
     * Save Pages
     *
     * @param $data
     * @return Pages
     */
     public function save(array $data)
    {
        return Pages::create($data);
    }

     /**
     * Update Pages
     *
     * @param $data
     * @return Pages
     */
    public function update(array $data, int $id)
    {
        $pages = $this->pages->find($id);
        $pages->update($data);
        return $pages;
    }

    /**
     * Delete Pages
     *
     * @param $data
     * @return Pages
     */
   	 public function delete(int $id)
    {
        $pages = $this->pages->find($id);
        $pages->delete();
        return $pages;
    }
}

<?php

namespace App;

abstract class CustomerSidebar
{
    protected $title;
    protected $url;
    protected $code;
    public function getTitle()
    {
        return $this->title;
    }


}

<?php

namespace App\Http\Resources\PageBuilder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageTranslationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'page_id' => $this->page_id,
            'locale' => $this->locale,
            'title' => $this->title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'route' => $this->route,
        ];
    }
}

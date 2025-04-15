<?php

namespace App\Http\Resources\PageBuilder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PagesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'layout' => $this->layout,
            'data' => $this->data,
        ];
    }
}

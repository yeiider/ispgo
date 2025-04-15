<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyBoxResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'box_id' => $this->box_id,
            'date' => $this->date,
            'start_amount' => $this->start_amount,
            'end_amount' => $this->end_amount,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasswordResetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'token' => $this->token,
        ];
    }
}

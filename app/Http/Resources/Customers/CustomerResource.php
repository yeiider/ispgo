<?php

namespace App\Http\Resources\Customers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'phone_number' => $this->phone_number,
            'email_address' => $this->email_address,
            'document_type' => $this->document_type,
            'identity_document' => $this->identity_document,
            'customer_status' => $this->customer_status,
            'additional_notes' => $this->additional_notes,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'password' => $this->password,
            'password_reset_token' => $this->password_reset_token,
            'remember_token' => $this->remember_token,
            'password_reset_token_expiration' => $this->password_reset_token_expiration,
        ];
    }
}

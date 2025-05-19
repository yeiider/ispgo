<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization will be handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ticket_id' => 'required|integer|exists:tickets,id',
            'content' => 'required|string',
            'recipient_id' => 'nullable|integer|exists:users,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max file size
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'ticket_id.required' => 'The ticket ID is required.',
            'ticket_id.exists' => 'The selected ticket does not exist.',
            'content.required' => 'The comment content is required.',
            'recipient_id.exists' => 'The selected recipient does not exist.',
            'attachments.*.max' => 'Each file must not exceed 10MB.',
        ];
    }
}

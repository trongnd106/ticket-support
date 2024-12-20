<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'categories'  => ['nullable','array', 'exists:categories,id'],
            'labels'      => ['nullable','array', 'exists:labels,id'],
            'priority'    => ['nullable', 'string', 'in:low,medium,high'], 
            'status'      => ['nullable', 'string', 'in:pending,processing,resolved'], 
            'assigned_to' => ['required', 'integer', 'exists:users,id'],
            'attachments' => ['nullable', 'array']
        ];
    }
}

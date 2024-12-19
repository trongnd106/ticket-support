<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Coderflex\LaravelTicket\Enums\Priority;
use Coderflex\LaravelTicket\Enums\Status;
use Illuminate\Validation\Rules\Enum;

class TicketCreateRequest extends FormRequest
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
            'title'       => ['required', 'string'],
            'description'     => ['required', 'string'],
            'categories'  => ['array', 'exists:categories,id'],
            'labels'      => ['array', 'exists:labels,id'],
            'priority'    => ['required', 'string', 'in:low,medium,high'], 
            'status'      => ['nullable', 'string', 'in:pending,processing,resolved'], 
            'assigned_to' => ['nullable', 'integer', 'exists:users,id']
        ];
    }
}

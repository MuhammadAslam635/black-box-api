<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:Users,id'],
            'type' => ['required', 'in:art,film,music'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'file_path' => ['required', 'string'],
        ];
    }
}

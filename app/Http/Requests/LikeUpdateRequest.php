<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LikeUpdateRequest extends FormRequest
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
            'content_id' => ['required', 'integer', 'exists:,id'],
        ];
    }
}

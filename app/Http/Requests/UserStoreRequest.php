<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UserStoreRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'], // Assuming you want to check uniqueness in the 'users' table and limit the email length to 255 characters
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'type' => ['nullable', 'string', 'in:user,artist,admin'],
            'status' => ['nullable', 'string', 'in:active,pending,declined,blocked,deleted'],
        ];
    }
}

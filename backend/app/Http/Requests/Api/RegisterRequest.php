<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:255',
            'email' => 'required|string|email|min:4|max:255|unique:users,email',
            'password' => 'required|regex:/^\S{6,}$/|max:255',
            'language' => 'required|in:ru,en',
            'subscribe' => 'required|boolean',
            'two_fa' => 'boolean'
        ];

    }
}

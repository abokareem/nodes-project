<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
        $this['password_confirmation'] = $this->get('password');

        return [
            'token' => 'required|max:255|min:4',
            'email' => 'required|email|max:255|min:4',
            'password' => 'required|regex:/^\S{6,}$/|min:6|max:255',
        ];

    }
}

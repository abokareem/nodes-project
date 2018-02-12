<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateMasternodeRequest extends FormRequest
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
                'masternode.currency_id' => 'required|numeric',
                'masternode.name' => 'required|max:255|min:2',
                'masternode.description' => 'required|max:65535|min:2',
                'masternode.income' => 'max:255',
                'masternode.price' => 'required|max:255|min:1',
                'share.price' => 'required|max:255|min:1',
                'share.count' => 'required|max:255|min:1'
        ];
    }
}

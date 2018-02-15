<?php

namespace App\Http\Requests\Api;

use App\Masternode;
use App\Rules\NodePartyPrice;
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
            'currency_id' => 'required|numeric',
            'type' => 'required|in:' . Masternode::types(),
            'price' => ['numeric', new NodePartyPrice]
        ];
    }
}

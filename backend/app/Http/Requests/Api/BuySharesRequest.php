<?php

namespace App\Http\Requests\Api;

use App\Rules\SharesBuyRule;
use Illuminate\Foundation\Http\FormRequest;

class BuySharesRequest extends FormRequest
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
            'node_id' => 'required|integer',
            'count' => ['required', 'integer', new SharesBuyRule()]
        ];
    }
}

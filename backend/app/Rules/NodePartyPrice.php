<?php

namespace App\Rules;

use App\Currency;
use App\Services\Math\MathInterface;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class NodePartyPrice implements Rule
{
    private $currency;
    private $full;
    private $min;
    private $math;

    /**
     * Create a new rule instance.
     *
     */

    public function __construct()
    {
        $this->currency = Currency::find(Request::get('currency_id'));
        $this->math = app(MathInterface::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_null($this->currency) || is_null($this->currency->share)) {
            return true;
        }

        $share = $this->currency->share;
        $price = $this->math->multiply($share->share_price, $value);

        if ($price > $share->full_price || $price < $share->share_price) {

            $this->full = $share->full_price;
            $this->min = $share->share_price;

            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.node.partyPrice', ['max' => $this->full, 'min' => $this->min]);
    }
}

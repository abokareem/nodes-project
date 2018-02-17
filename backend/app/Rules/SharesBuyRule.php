<?php

namespace App\Rules;

use App\Masternode;
use App\Services\Math\MathInterface;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class SharesBuyRule implements Rule
{
    private $node;
    private $math;

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        $this->node = Masternode::find(Request::get('node_id'));
        $this->math = app(MathInterface::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->node && !$this->node->currency &&
            !$this->node->currency->share && $this->node->bill) {
            return true;
        }

        $share = $this->node->currency->share;
        $nodeBillAmount = $this->node->bill->amount;
        $nodePrice = $this->node->price;

        $freeShare = $this->math->sub($nodePrice, $nodeBillAmount);
        $requestedSharePrice = $this->math->multiply($value, $share->share_price);

        if ($freeShare < $requestedSharePrice || $share->share_price > $requestedSharePrice) {
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
        $share = $this->node->currency->share;

        return trans('validation.buyShares',
            ['max' => $share->full_price, 'min' => $share->min_price]);
    }
}

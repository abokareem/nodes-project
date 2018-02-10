<?php

namespace App\Rules;

use App\MasternodeShare;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Request;

class SharesBuyCount implements Rule
{
    private $share;

    /**
     * Create a new rule instance.
     *
     */
    public function __construct()
    {
        $this->share = MasternodeShare::find(Request::get('share_id'));
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
        if (!$this->share) {
            return true;
        }

        if ($this->share->count >= $value) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.buyShares');
    }
}

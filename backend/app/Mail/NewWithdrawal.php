<?php

namespace App\Mail;

use App\Currency;
use App\Withdrawals;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewWithdrawal extends Mailable
{
    use Queueable, SerializesModels;

    public $withdrawal;
    public $currency;

    /**
     * NewWithdrawal constructor.
     * @param Withdrawals $withdrawal
     * @param Currency $currency
     */
    public function __construct(Withdrawals $withdrawal, Currency $currency)
    {
        $this->withdrawal = $withdrawal;
        $this->currency = $currency;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.newWithdrawal');
    }
}

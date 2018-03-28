<?php

namespace App\Mail;

use App\Http\Requests\Api\System\ContactUsRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    public $fromEmail;
    public $message;
    public $subject;
    public $name;

    /**
     * Create a new message instance.
     *
     * @param ContactUsRequest $request
     */
    public function __construct(ContactUsRequest $request)
    {
        $this->fromEmail = $request->get('email');
        $this->message = $request->get('message');
        $this->subject = $request->get('subject');
        $this->name = $request->get('name');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.admin.contactus');
    }
}

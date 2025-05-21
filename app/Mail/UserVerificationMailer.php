<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserVerificationMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build(): UserVerificationMailer
    {
        return $this->subject(
            'DigiBank Template'
        )
            ->view('mails.otp-mail')
            ->with('data', $this->data);
    }
}

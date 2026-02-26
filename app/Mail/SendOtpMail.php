<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Hello Nb jit this is your otp')
                    ->html(
                        "<h2>Your OTP Code</h2>
                        <p>Use this code to verify your email:</p>
                        <h1>{$this->otp}</h1>
                        <p>This code expires in 5 minutes.</p>"
                    );
    }
}
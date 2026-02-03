<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $verificationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $verificationUrl)
    {
        $this->name = $name;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verify your email - Cod Intelligence')
                    ->view('emails.verify');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $code;

    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct($user,$code)
    {
        $this->user = $user;
        $this->to($user);
        $this->subject("Email Verification");
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.verificationEmail',['user'=>$this->user,'code'=>$this->code]);
    }
}

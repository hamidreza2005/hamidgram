<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class twoStepVerification extends Mailable
{
    use Queueable, SerializesModels;
    private $user;
    private $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$code)
    {
        //
        $this->user = $user;
        $this->code = $code;
        $this->subject("Two Step Verification");
        $this->to($user);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mails.twoStepVerification',['user'=>$this->user,'code'=>$this->code]);
    }
}

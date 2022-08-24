<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\View;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailingList extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public $emailText; 

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $emailText)
    {
        $this->email = $email;
        $this->emailText = $emailText;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( 'suvorova_ma@groupbwt.com', 'Laravel Conferences')
            ->to($this->email, 'you')
            ->subject('Alert: '. 'Laravel')
            ->view('mail')
            ->with([
                'text' => $this->emailText
            ]);;
    }
}

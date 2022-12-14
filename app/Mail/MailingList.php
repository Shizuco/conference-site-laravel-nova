<?php
declare (strict_types = 1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class MailingList extends Mailable
{
    use Queueable, SerializesModels;

    public string $email;

    public string $emailText;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, string $emailText)
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
        return $this->from('suvorova_ma@groupbwt.com', 'Laravel Conferences')
            ->to($this->email, 'you')
            ->subject('Alert: ' . 'Laravel')
            ->view('mail')
            ->with([
                'text' => $this->emailText,
            ]);
    }
}

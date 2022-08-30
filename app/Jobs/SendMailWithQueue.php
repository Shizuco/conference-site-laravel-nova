<?php
declare (strict_types = 1);

namespace App\Jobs;

use App\Mail\MailingList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailWithQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    public $emailText;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $emailText)
    {
        $this->email = $email;
        $this->emailText = $emailText;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::To($this->email)->send(new MailingList($this->email, $this->emailText));
    }
}

<?php

declare(strict_types=1);

namespace Exhum4n\Components\Jobs;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class SendEmailJob extends AbstractJob
{
    /**
     * @var string
     */
    protected $mailable;

    /**
     * @var string
     */
    protected $email;

    /**
     * @param string $email
     * @param Mailable $mailable
     */
    public function __construct(string $email, Mailable $mailable)
    {
        $this->email = $email;
        $this->mailable = $mailable;
    }

    public function handle(): void
    {
        Mail::to($this->email)->send($this->mailable);
    }
}

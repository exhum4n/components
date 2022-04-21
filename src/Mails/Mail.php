<?php

declare(strict_types=1);

namespace Exhum4n\Components\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class Mail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct()
    {
        $this->locale = app()->getLocale();
    }

    abstract public function build(): Mailable;
}

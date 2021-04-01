<?php

declare(strict_types=1);

namespace Exhum4n\Components\Mails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * BaseMail constructor.
     */
    public function __construct()
    {
        $this->locale = app()->getLocale();
    }
}

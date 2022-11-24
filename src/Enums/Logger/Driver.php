<?php

namespace Exhum4n\Components\Enums\Logger;

enum Driver
{
    case single;
    case daily;
    case slack;
    case monolog;
    case syslog;
    case errorlog;
}

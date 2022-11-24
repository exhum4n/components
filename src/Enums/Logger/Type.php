<?php

namespace Exhum4n\Components\Enums\Logger;

enum Type
{
    case alert;
    case critical;
    case debug;
    case emergency;
    case error;
    case warning;
    case notice;
    case info;
}

<?php

declare(strict_types=1);

namespace Exhum4n\Components\Enums\Database;

enum Driver
{
    case pgsql;
    case sqlite;
    case mysql;
    case sqlsrv;
}

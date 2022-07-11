<?php

namespace Exhum4n\Components\Tools;

use Exhum4n\Components\Exceptions\IncompatibleType;
use Exhum4n\Components\Tools\Logger\Type;
use Illuminate\Support\Facades\Log;

class Logger
{
    protected array $logData = [];
    protected array $types = [
        Type::NOTICE,
        Type::INFO
    ];

    public function log(array $data): void
    {
        $this->logData = array_merge($this->logData, $data);
    }

    /**
     * @throws IncompatibleType
     */
    public function writeLog(string $event, string $logName, string $type): void
    {
        if (in_array($type, $this->types) === false) {
            throw new IncompatibleType("Log type: $type is not supported");
        }

        $log = Log::build([
            'driver' => 'single',
            'path' => storage_path("logs/$logName.log"),
        ]);

        $log->$type($event, $this->logData);
    }
}

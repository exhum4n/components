<?php

/** @noinspection PhpUnused */

namespace Exhum4n\Components\Tools;

use Exhum4n\Components\Enums\Logger\Driver;
use Exhum4n\Components\Enums\Logger\Type;
use Illuminate\Support\Facades\Log;

class Logger
{
    protected array $logData = [];

    protected string $logName;

    protected string $driver = 'single';

    public function collect(array $data): self
    {
        if (isset($this->logName) === false) {
            $this->logName = static::class;
        }

        $this->logData = array_merge($this->logData, $data);

        return $this;
    }

    public function setDriver(Driver $driver): self
    {
        $this->driver = $driver->name;

        return $this;
    }

    public function save(string $event, Type $type, ?string $path = null, ?array $options = []): void
    {
        if (is_null($path)) {
            $path = $this->getCalledClassName();
        }

        $log = Log::build(array_merge([
            'driver' => $this->driver,
            'path' => storage_path("logs/$path.log"),
        ], $options));

        $log->{$type->name}($event, $this->logData);
    }

    protected function getCalledClassName(): string
    {
        return class_basename(debug_backtrace()[2]['class']);
    }
}

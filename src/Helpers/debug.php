<?php

declare(strict_types=1);

if (function_exists('getMemoryPeakUsage') === false) {
    function getMemoryPeakUsage(): string
    {
        return sprintf('%.2F MiB', memory_get_peak_usage() / 1024 / 1024);
    }
}
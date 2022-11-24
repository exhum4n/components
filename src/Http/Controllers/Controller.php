<?php

/** @noinspection PhpUndefinedMethodInspection */

declare(strict_types=1);

namespace Exhum4n\Components\Http\Controllers;

use Exhum4n\Components\Services\Service;
use Exhum4n\Components\Traits\HasService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    protected Service $service;

    public function __construct()
    {
        $traits = class_uses_recursive(static::class);

        if (in_array(HasService::class, $traits, true)) {
            $this->initializeService();
        }

        $this->applyMiddleware();
    }

    protected function applyMiddleware(): void
    {
    }

    private function initializeService(): void
    {
        $this->service = app(static::getService());
    }
}

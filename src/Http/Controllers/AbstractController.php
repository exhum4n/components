<?php

namespace Exhum4n\Components\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    protected $service;

    protected function getCurrentUser(): Authenticatable
    {
        return auth()->user();
    }
}

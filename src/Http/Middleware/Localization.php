<?php

namespace Exhum4n\Components\Http\Middleware;

use Closure;
use Exhum4n\Components\Exceptions\LocaleNotSupported;
use Illuminate\Http\Request;

class Localization
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     *
     * @throws LocaleNotSupported
     */
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = config('components.languages');

        $locale = $request->header('Content-Language');
        if (is_null($locale)) {
            $locale = config()->get('app.locale');
        }

        if (isset($supportedLocales[$locale]) === false) {
            throw new LocaleNotSupported($locale);
        }

        app()->setLocale($locale);

        return $next($request);
    }
}

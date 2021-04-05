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
     * @return Closure
     *
     * @throws LocaleNotSupported
     */
    public function handle(Request $request, Closure $next): Closure
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

        $response = $next($request);

        $response->headers->set('Content-Language', $locale);

        return $response;
    }
}

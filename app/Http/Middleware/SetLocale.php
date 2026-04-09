<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->getLocale($request);

        App::setLocale($locale);

        Session::put('locale', $locale);

        $response = $next($request);

        $response->headers->set('Content-Language', $locale);

        return $response;
    }

    protected function getLocale(Request $request): string
    {
        $availableLocales = config('app.available_locales', ['en', 'ar']);

        if ($request->session()->has('locale')) {
            $sessionLocale = $request->session()->get('locale');
            if (in_array($sessionLocale, $availableLocales)) {
                return $sessionLocale;
            }
        }

        $user = $request->user();
        if ($user && $user->locale && in_array($user->locale, $availableLocales)) {
            return $user->locale;
        }

        $browserLocale = $request->getPreferredLanguage($availableLocales);
        if ($browserLocale && in_array($browserLocale, $availableLocales)) {
            return $browserLocale;
        }

        return config('app.locale', 'en');
    }
}

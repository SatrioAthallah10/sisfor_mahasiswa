<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Prefer explicit session locale (set by controllers), then cookie, then fallback
        $locale = null;

        if ($request->hasSession() && $request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
        } elseif ($request->cookies->has('locale')) {
            $locale = $request->cookies->get('locale');
        }

        if ($locale && in_array($locale, ['en', 'id'])) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale', 'en'));
        }

        return $next($request);
    }
}

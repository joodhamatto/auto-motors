<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** @param  Closure(Request): (Response)  $next */
    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = SiteSetting::query()->where('key', 'default_language')->value('value')
            ?: config('app.locale', 'fr');
        $locale = $request->session()->get('locale', $defaultLocale);
        app()->setLocale(in_array($locale, ['fr', 'en'], true) ? $locale : 'fr');

        return $next($request);
    }
}

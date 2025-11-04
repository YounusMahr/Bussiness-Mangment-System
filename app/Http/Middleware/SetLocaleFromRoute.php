<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from route parameter first (if URL has locale)
        $locale = $request->route('locale');
        
        // If locale is not in route, try to get from session
        if (!$locale) {
            $locale = Session::get('locale');
        }
        
        // If still no locale, get from LaravelLocalization or use default
        if (!$locale) {
            $locale = LaravelLocalization::getCurrentLocale() ?: config('app.locale', 'en');
        }
        
        // Validate locale
        if ($locale && in_array($locale, ['en', 'ur', 'ps'])) {
            // Set locale in application
            App::setLocale($locale);
            
            // Store locale in session for persistence across navigation
            Session::put('locale', $locale);
        } else {
            // Fallback to default
            $locale = config('app.locale', 'en');
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
        
        return $next($request);
    }
}


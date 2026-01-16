<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Get the locale from the request or session
        $locale = $request->route('locale') 
            ?? session('locale') 
            ?? $request->segment(1) 
            ?? config('app.locale', 'en');
        
        // Ensure locale is valid
        if (!in_array($locale, ['en', 'ur', 'ps'])) {
            $locale = config('app.locale', 'en');
        }
        
        // Return localized login route
        return "/{$locale}/login";
    }
}


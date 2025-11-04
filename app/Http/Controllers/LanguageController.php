<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LanguageController extends Controller
{
    public function switchLanguage($locale)
    {
        // Validate locale
        if (!in_array($locale, ['en', 'ur', 'ps'])) {
            $locale = 'en';
        }

        // Store locale in session for persistence
        session(['locale' => $locale]);
        
        // Get referer URL or use current URL
        $referer = request()->header('referer');
        if (!$referer) {
            $referer = url()->previous();
        }
        if (!$referer) {
            // Default to index page if no referer
            $referer = url('/index');
        }

        // Get the current URL without locale prefix
        $urlWithoutLocale = LaravelLocalization::getNonLocalizedURL($referer);
        
        // If URL is just '/' or empty, use '/index' instead
        if ($urlWithoutLocale === '/' || empty(trim($urlWithoutLocale, '/'))) {
            $urlWithoutLocale = '/index';
        }
        
        // Get the localized URL for the new locale
        $localizedUrl = LaravelLocalization::getLocalizedURL($locale, $urlWithoutLocale);

        // Redirect to the localized URL
        return redirect($localizedUrl);
    }
}


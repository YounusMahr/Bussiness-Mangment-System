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
            return redirect("/{$locale}/index");
        }

        // Parse the referer URL
        $refererPath = parse_url($referer, PHP_URL_PATH);
        
        // List of paths that should NOT be localized (static assets, PWA files, etc.)
        $excludedPaths = [
            '/sw.js',
            '/manifest.json',
            '/favicon.ico',
            '/robots.txt',
        ];
        
        // Check if the referer is an excluded path
        foreach ($excludedPaths as $excludedPath) {
            if (strpos($refererPath, $excludedPath) !== false) {
                // If referer is a static asset, redirect to index page
                return redirect("/{$locale}/index");
            }
        }
        
        // Check if referer contains a locale prefix
        $refererLocale = null;
        if (preg_match('#^/(en|ur|ps)(/|$)#', $refererPath, $matches)) {
            $refererLocale = $matches[1];
            // Remove locale from path
            $refererPath = preg_replace('#^/(en|ur|ps)(/|$)#', '/', $refererPath);
        }
        
        // If URL is just '/' or empty, use '/index' instead
        if ($refererPath === '/' || empty(trim($refererPath, '/'))) {
            $refererPath = '/index';
        }
        
        // Ensure path starts with /
        if (!str_starts_with($refererPath, '/')) {
            $refererPath = '/' . $refererPath;
        }
        
        // Redirect to the localized URL
        return redirect("/{$locale}{$refererPath}");
    }
}


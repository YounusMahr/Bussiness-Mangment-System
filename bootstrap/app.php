<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register LaravelLocalization middleware aliases
        $middleware->alias([
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'setLocaleFromRoute' => \App\Http\Middleware\SetLocaleFromRoute::class,
        ]);
        
        // Use custom Authenticate middleware that preserves locale
        $middleware->redirectGuestsTo(function (Request $request) {
            $locale = $request->route('locale') 
                ?? session('locale') 
                ?? $request->segment(1) 
                ?? config('app.locale', 'en');
            
            if (!in_array($locale, ['en', 'ur', 'ps'])) {
                $locale = config('app.locale', 'en');
            }
            
            return "/{$locale}/login";
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

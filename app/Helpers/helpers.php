<?php

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Support\Facades\Route;

if (!function_exists('localized_route')) {
    /**
     * Generate a localized route URL
     *
     * @param string $name
     * @param mixed $parameters
     * @param bool $absolute
     * @return string
     */
    function localized_route($name, $parameters = [], $absolute = true)
    {
        $locale = app()->getLocale();
        
        // If parameters is empty, just add locale
        if (empty($parameters)) {
            return route($name, ['locale' => $locale], $absolute);
        }
        
        // If parameters is already an array, merge locale
        if (is_array($parameters)) {
            if (!isset($parameters['locale'])) {
                $parameters['locale'] = $locale;
            }
            return route($name, $parameters, $absolute);
        }
        
        // For models or single values, we need to get the route parameter name
        $route = Route::getRoutes()->getByName($name);
        if ($route) {
            // Get route parameter names (excluding locale)
            $parameterNames = $route->parameterNames();
            $nonLocaleParams = array_filter($parameterNames, fn($param) => $param !== 'locale');
            
            if (!empty($nonLocaleParams)) {
                // Use the first non-locale parameter name
                $paramName = reset($nonLocaleParams);
                $params = ['locale' => $locale, $paramName => $parameters];
                return route($name, $params, $absolute);
            }
        }
        
        // Fallback: try to infer parameter name from route name
        // e.g., 'udaar.edit' -> 'udaar', 'products.edit' -> 'product'
        $routeParts = explode('.', $name);
        if (count($routeParts) >= 2) {
            $lastPart = end($routeParts);
            $prefix = $routeParts[0];
            
            // Special cases - handle nested routes like car-rent.udaar
            $fullRoute = implode('.', array_slice($routeParts, 0, -1));
            $paramName = match($fullRoute) {
                'udaar' => 'udaar',
                'car-rent.udaar' => 'udaar',
                'products' => 'product',
                'sales' => 'sale',
                'vehicles' => 'vehicle',
                'bookings' => 'booking',
                'categories' => 'category',
                default => $prefix
            };
            
            $params = ['locale' => $locale, $paramName => $parameters];
            return route($name, $params, $absolute);
        }
        
        // Final fallback: just pass with locale
        return route($name, ['locale' => $locale, $parameters], $absolute);
    }
}


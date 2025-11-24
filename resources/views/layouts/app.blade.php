<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- PWA Meta Tags -->
        <meta name="application-name" content="Business MS">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Business MS">
        <meta name="description" content="Complete business management system for grocery and car installment tracking">
        <meta name="format-detection" content="telephone=no">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="msapplication-TileColor" content="#9333ea">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="theme-color" content="#9333ea">

        <!-- Apple Touch Icons -->
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/img/icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="96x96" href="{{ asset('assets/img/icon-96x96.png') }}">
        <link rel="apple-touch-icon" sizes="128x128" href="{{ asset('assets/img/icon-128x128.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/img/icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/img/icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="192x192" href="{{ asset('assets/img/icon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="384x384" href="{{ asset('assets/img/icon-384x384.png') }}">
        <link rel="apple-touch-icon" sizes="512x512" href="{{ asset('assets/img/icon-512x512.png') }}">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">

        <!-- Web App Manifest -->
        <link rel="manifest" href="{{ asset('manifest.json') }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Font Awesome CDN -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <x-header /> 
    </head>
    <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
      <!-- Loading Component -->
      <livewire:components.loading />
      
      <!-- sidenav  -->
      <x-sidebar />
      <!-- end sidenav -->

      <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
        <!-- Navbar -->
       <livewire:components.navbar />
        <!-- end Navbar -->

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
         <!-- javascript -->
            <x-javascript />
         <!-- end javascript -->
         
         <!-- PWA Install Button -->
         <x-pwa-install-button />
         
         <!-- PWA Registration Script -->
         <script src="{{ asset('pwa.js') }}"></script>
    </body>
</html>

   
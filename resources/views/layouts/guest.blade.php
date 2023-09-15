<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>{{ config('app.name', 'Laravel') }} - {{ $title ?? 'Home' }}</title>

    <!-- Meta Tags -->
    @stack('meta-tags')

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="antialiased text-zinc-900 font-mona" x-cloak x-data="{ dark: $persist(false) }" :class="{ 'dark': dark === true }">

    <!-- Background Image -->
    <img src="{{ url('assets/hero.jpeg') }}" alt="Hero Image" class="absolute object-cover w-screen h-screen -z-50" />
    <div class="absolute w-screen h-screen bg-black/70 -z-40"></div>

    <!-- Main Section -->
    <main class="container flex flex-col items-center justify-center min-h-screen mx-auto">
        {{ $slot }}
    </main>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>

</html>

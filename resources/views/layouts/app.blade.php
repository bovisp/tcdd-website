<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @include('layouts.partials._trans')

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.js" defer></script> 

    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@5.8.55/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-swatches/dist/vue-swatches.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        window.urlBase = '{{ env('APP_URL') }}';
        window.currentLang = '{{ app()->getLocale() }}';
    </script>
</head>
<body>
    <div id="app" class="relative">
        <header class=" border-b border-gray-300">
            <nav class="flex container mx-auto items-center">
                @include('layouts.partials._lang-switcher')

                @auth
                    <div class="ml-auto">
                        <nav-dropdown />
                    </div>
                @endauth
            </nav>
        </header>

        <main class="container">
            @yield('content')
        </main>
    </div>

    @yield('jsbottom')
</body>
</html>
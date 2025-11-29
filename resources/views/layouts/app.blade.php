<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Tailwind dengan Color Palette Kamu -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#4B352A',
                            secondary: '#CA7842', 
                            accent: '#B2CD9C',
                            background: '#F0F2BD',
                            danger: '#e3342f',
                            success: '#38c172',
                            warning: '#ffed4a',
                            info: '#6cb2eb',
                        }
                    }
                }
            }
        </script>
        
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>
    <body class="bg-background min-h-screen font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-background">
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-primary shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>

        <!-- AlpineJS untuk dropdown -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    </body>
</html>
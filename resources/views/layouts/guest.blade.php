<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name', 'Laravel'))</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                             primary: '#1B3C53',      
                            secondary: '#234C6A',    
                            accent: '#456882',       
                            background: '#E3E3E3',   
                            danger: '#DC2626',       
                            success: '#16A34A',      
                            warning: '#F59E0B',      
                            info: '#3B82F6',         
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
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-background">
           

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg overflow-hidden sm:rounded-lg border border-secondary">
                @yield('content')
            </div>

           
        </div>
    </body>
</html>
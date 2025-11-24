<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <script src="https://cdn.tailwindcss.com"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

        @auth
            {{-- Layout untuk User Login (Dashboard) --}}
            <div class="flex h-screen bg-gray-50 overflow-hidden">

                {{-- Sidebar --}}
                @include('layouts.navigation')

                {{-- Main Content Wrapper --}}
                <div class="flex-1 flex flex-col h-full relative w-full">

                    {{-- Header --}}
                    @isset($header)
                        <header class="flex-shrink-0 bg-white border-b border-gray-200 z-30">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center h-16">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    {{-- Scrollable Content Area --}}
                    <main class="flex-1 overflow-y-auto bg-gray-100 p-6 scroll-smooth">
                        {{-- Slot Konten --}}
                        <div class="max-w-7xl mx-auto">
                            {{ $slot }}
                        </div>
                    </main>

                </div>
            </div>
        @else
            {{-- Layout untuk Tamu (Belum Login) --}}
           <div class="flex h-screen bg-gray-100 overflow-hidden">

                {{-- Sidebar Guest --}}
                @include('layouts.navigation-guest')

                {{-- Wrapper Konten Utama (Kanan) --}}
                <div class="flex-1 flex flex-col h-full relative w-full overflow-y-auto">

                    @isset($header)
                        <header class="flex-shrink-0 bg-white border-b border-gray-200 z-30">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center h-16">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="flex-1 p-6">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @endauth

    </body>
</html>

<!DOCTYPE html>
<html lang="en" x-data x-cloak>

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - @yield('page_title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Load Tailwind & Alpine --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


    {{-- Custom Alpine Store --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: window.innerWidth >= 768,
                toggle() {
                    this.open = !this.open;
                }
            });

            // Auto-close sidebar on small screens when resizing
            window.addEventListener('resize', () => {
                Alpine.store('sidebar').open = window.innerWidth >= 768;
            });
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800 h-screen" x-data>
    <div class="flex h-full">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Overlay for mobile --}}
        <div x-show="$store.sidebar.open" x-transition.opacity class="fixed inset-0 bg-black bg-opacity-25 z-20 md:hidden"
            @click="$store.sidebar.toggle()" x-cloak>
        </div>

        {{-- Content --}}
        <div class="flex flex-col flex-1 overflow-hidden">
            {{-- Navbar --}}
            @include('layouts.navbar')

            {{-- Main Content --}}
            <main
                class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200 p-4 md:p-6">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="bg-white border-t p-4 text-sm text-gray-500 text-center shadow">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </footer>
        </div>
    </div>
</body>

</html>

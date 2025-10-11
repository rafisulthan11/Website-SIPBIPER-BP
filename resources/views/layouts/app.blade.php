<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Additional Styles from Child Views -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100" x-data="{ sidebarShown: true, mobileDrawer: false }" @keydown.window.escape="mobileDrawer=false">
            <!-- Mobile overlay and drawer -->
            <div class="md:hidden">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-black/40 transition-opacity" x-show="mobileDrawer" x-transition.opacity @click="mobileDrawer=false"></div>
                <!-- Drawer Panel -->
                <div class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 shadow-lg transform transition-transform" x-show="mobileDrawer" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    @include('layouts.sidebar')
                </div>
            </div>

            <div class="flex">
                <!-- Sidebar (desktop) -->
                <aside class="hidden md:block w-72 bg-white" x-show="sidebarShown" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-ml-72 opacity-0" x-transition:enter-end="ml-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="ml-0 opacity-100" x-transition:leave-end="-ml-72 opacity-0">
                    @include('layouts.sidebar')
                </aside>

                <!-- Main content -->
                <div class="flex-1 min-w-0">
                    <!-- Page Content -->
                    <main>
                        <!-- Header bar: only hamburger, search, notif -->
                        <div class="bg-white shadow-sm">
                            <div class="py-3 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <!-- Hamburger (mobile & desktop) -->
                                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-slate-700 hover:bg-gray-100 focus:outline-none" @click="(window.matchMedia('(min-width: 768px)').matches) ? sidebarShown = !sidebarShown : mobileDrawer = !mobileDrawer" aria-label="Toggle sidebar">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
                                    </button>
                                </div>
                                <div class="hidden sm:flex items-center gap-3">
                                    <!-- Search -->
                                    <div class="relative">
                                        <input type="text" placeholder="Cari" class="border rounded-full ps-10 pe-4 py-2 text-sm placeholder:text-gray-400 focus:ring-indigo-500 focus:border-indigo-500" />
                                        <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17.25 10.5a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z"/></svg>
                                    </div>
                                    <!-- Notification icon -->
                                    <button class="p-2 rounded-full hover:bg-gray-100" title="Notifikasi">
                                        <svg class="w-5 h-5 text-slate-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V8.25a6 6 0 10-12 0v1.5a8.967 8.967 0 01-2.312 6.022c1.766.68 3.56 1.13 5.454 1.31m5.715 0a24.255 24.255 0 01-5.715 0m5.715 0a3 3 0 11-5.715 0"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Page title moved into body top -->
                        @isset($header)
                            <div class="px-4 sm:px-6 lg:px-8 py-4">
                                {{ $header }}
                            </div>
                        @endisset

                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
        
        <!-- Additional Scripts from Child Views -->
        @stack('scripts')
    </body>
</html>

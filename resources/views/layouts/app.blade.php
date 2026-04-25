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
    <div class="min-h-screen bg-gray-100" x-data="{ sidebarShown: true, mobileDrawer: false }" x-init="$watch('sidebarShown', () => window.dispatchEvent(new CustomEvent('sincan.sidebarToggled'))); $watch('mobileDrawer', () => window.dispatchEvent(new CustomEvent('sincan.sidebarToggled')));" @keydown.window.escape="mobileDrawer=false">
            <!-- Mobile overlay and drawer -->
            <div class="md:hidden">
                <!-- Overlay -->
                <div class="fixed inset-0 bg-black/40 transition-opacity z-40" x-show="mobileDrawer" x-transition.opacity @click="mobileDrawer=false"></div>
                <!-- Drawer Panel -->
                <div class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 shadow-lg transform transition-transform z-50" x-show="mobileDrawer" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    @include('layouts.sidebar')
                </div>
            </div>

            <div class="flex">
                <!-- Sidebar (desktop) -->
                <aside class="hidden md:block w-72 bg-white z-50" x-show="sidebarShown" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-ml-72 opacity-0" x-transition:enter-end="ml-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="ml-0 opacity-100" x-transition:leave-end="-ml-72 opacity-0">
                    @include('layouts.sidebar')
                </aside>

                <!-- Main content -->
                <div class="flex-1 min-w-0 z-0">
                    <!-- Page Content -->
                    <main>
                        <!-- Header bar: only hamburger, search, notif -->
                        <div class="bg-white shadow-sm z-30 sticky top-0 relative">
                            <div class="h-14 sm:h-16 px-3 sm:px-6 lg:px-8 flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <!-- Hamburger (mobile & desktop) -->
                                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-slate-700 hover:bg-gray-100 focus:outline-none" @click="(window.matchMedia('(min-width: 768px)').matches) ? sidebarShown = !sidebarShown : mobileDrawer = !mobileDrawer" aria-label="Toggle sidebar">
                                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
                                    </button>
                                </div>
                                <div class="flex items-center gap-1 sm:gap-2">
                                    <!-- Notification Dropdown -->
                                    <div x-data="{ 
                                        open: false, 
                                        notifications: [],
                                        unreadCount: 0,
                                        loading: false,
                                        async fetchNotifications() {
                                            this.loading = true;
                                            try {
                                                const response = await fetch('{{ route('notifications.unread') }}');
                                                const data = await response.json();
                                                this.notifications = data.notifications;
                                                this.unreadCount = data.unread_count;
                                            } catch (error) {
                                                console.error('Error fetching notifications:', error);
                                            }
                                            this.loading = false;
                                        },
                                        async markAsRead(id) {
                                            try {
                                                await fetch(`/notifications/${id}/read`, {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Content-Type': 'application/json'
                                                    }
                                                });
                                                this.fetchNotifications();
                                            } catch (error) {
                                                console.error('Error marking as read:', error);
                                            }
                                        },
                                        async markAllAsRead() {
                                            try {
                                                await fetch('{{ route('notifications.read-all') }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Content-Type': 'application/json'
                                                    }
                                                });
                                                this.fetchNotifications();
                                            } catch (error) {
                                                console.error('Error marking all as read:', error);
                                            }
                                        }
                                    }" 
                                    x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)"
                                    @click.away="open = false" 
                                    class="relative">
                                        <button @click="open = !open; if(open) fetchNotifications()" class="relative p-2 rounded-full hover:bg-gray-100" title="Notifikasi" aria-label="Notifikasi">
                                            <svg class="w-5 h-5 text-slate-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V8.25a6 6 0 10-12 0v1.5a8.967 8.967 0 01-2.312 6.022c1.766.68 3.56 1.13 5.454 1.31m5.715 0a24.255 24.255 0 01-5.715 0m5.715 0a3 3 0 11-5.715 0"/></svg>
                                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 min-w-[20px] flex items-center justify-center px-1"></span>
                                        </button>

                                        <!-- Dropdown -->
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="absolute right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 z-50"
                                             style="display: none; width: min(92vw, 22rem); max-width: 22rem; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15); overflow: hidden;">
                                            <div class="p-3 sm:p-4 border-b border-gray-200 flex items-center justify-between gap-2" style="padding: 0.875rem 1rem; border-bottom: 1px solid #e5e7eb;">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate">Notifikasi</h3>
                                                <button @click="markAllAsRead()" x-show="unreadCount > 0" class="text-xs text-blue-600 hover:text-blue-800 whitespace-nowrap">
                                                    Tandai semua dibaca
                                                </button>
                                            </div>
                                            
                                            <div class="max-h-[60vh] sm:max-h-96 overflow-y-auto overscroll-contain" style="max-height: min(60vh, 24rem); overflow-y: auto; overscroll-behavior: contain;">
                                                <template x-if="loading">
                                                    <div class="p-4 text-center text-gray-500">
                                                        <svg class="animate-spin h-6 w-6 mx-auto text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                    </div>
                                                </template>

                                                <template x-if="!loading && notifications.length === 0">
                                                    <div class="p-8 text-center text-gray-500">
                                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V8.25a6 6 0 10-12 0v1.5a8.967 8.967 0 01-2.312 6.022c1.766.68 3.56 1.13 5.454 1.31m5.715 0a24.255 24.255 0 01-5.715 0m5.715 0a3 3 0 11-5.715 0"/>
                                                        </svg>
                                                        <p class="text-sm">Tidak ada notifikasi</p>
                                                    </div>
                                                </template>

                                                <template x-if="!loading && notifications.length > 0">
                                                    <div>
                                                        <template x-for="notif in notifications" :key="notif.id">
                                                            <a :href="notif.url" @click="markAsRead(notif.id)" class="block px-3 sm:px-4 py-3 hover:bg-gray-50 border-b border-gray-100 transition">
                                                                <div class="flex items-start gap-3">
                                                                    <div class="flex-shrink-0 mt-1">
                                                                        <div class="w-2 h-2 bg-blue-500 rounded-full" x-show="!notif.is_read"></div>
                                                                        <div class="w-2 h-2" x-show="notif.is_read"></div>
                                                                    </div>
                                                                    <div class="flex-1 min-w-0">
                                                                        <p class="text-sm font-medium text-gray-900 break-words" x-text="notif.title"></p>
                                                                        <p class="text-xs text-gray-600 mt-0.5 break-words" x-text="notif.message"></p>
                                                                        <p class="text-xs text-gray-400 mt-1 break-words" x-text="new Date(notif.created_at).toLocaleString('id-ID')"></p>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>

                                            <div class="p-3 bg-gray-50 border-t border-gray-200 rounded-b-lg" style="padding: 0.75rem 1rem; background-color: #f9fafb; border-top: 1px solid #e5e7eb;">
                                                <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                    Lihat Semua Notifikasi
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Profile Dropdown -->
                                    @if(auth()->check())
                                    <div x-data="{ open: false, focused: false }" @click.away="open = false" class="relative">
                                        <button @click="open = !open" @focus="focused = true" @blur="focused = false" class="inline-flex items-center justify-center rounded-full transition" title="Profil Pengguna" aria-label="Profil Pengguna" style="padding: 0; border: 0; background: transparent; outline: none; border-radius: 9999px;" :style="(open || focused) ? 'box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #3b82f6;' : ''">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold text-sm leading-none" style="width: 2.25rem; height: 2.25rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.15); background: linear-gradient(135deg, #60a5fa, #2563eb);">
                                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                            </div>
                                        </button>

                                        <!-- Profile Dropdown Menu -->
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="absolute right-0 mt-3 w-[92vw] max-w-[20rem] sm:w-72 bg-white rounded-xl shadow-2xl border border-gray-100 z-50"
                                            style="display: none; width: min(92vw, 20rem); max-width: 20rem; background-color: #ffffff; border: 1px solid #f3f4f6; border-radius: 0.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden;">
                                            
                                            <!-- Profile Header with Avatar -->
                                            <div class="px-6 py-8 border-b border-gray-100" style="padding: 2rem 1.5rem; border-bottom: 1px solid #f3f4f6; background: linear-gradient(135deg, #f8fafc, #ffffff);">
                                                <!-- Avatar -->
                                                <div class="flex justify-center mb-4">
                                                    <div class="w-20 h-20 rounded-full flex items-center justify-center text-white font-bold text-3xl shadow-lg ring-4 ring-white" style="width: 5rem; height: 5rem; border-radius: 9999px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1); border: 4px solid #ffffff; background: linear-gradient(135deg, #60a5fa, #2563eb);">
                                                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                                    </div>
                                                </div>
                                                
                                                <!-- User Name - CRITICAL -->
                                                <div class="text-center mb-3">
                                                    <h3 class="text-xl font-bold text-gray-900 leading-tight">
                                                        {{ auth()->user()->name ?? 'User' }}
                                                    </h3>
                                                </div>
                                                
                                                <!-- User Email -->
                                                <div class="text-center mb-4">
                                                    <p class="text-xs text-gray-600 break-all">
                                                        {{ auth()->user()->email ?? '-' }}
                                                    </p>
                                                </div>

                                                <!-- Role Badge -->
                                                @if(auth()->user()->role)
                                                    <div class="flex justify-center">
                                                        @if(auth()->user()->role->nama_role == 'super admin')
                                                            <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold border" style="background-color: #fef2f2; color: #b91c1c; border-color: #fecaca;">
                                                                {{ auth()->user()->role->nama_role }}
                                                            </span>
                                                        @elseif(auth()->user()->role->nama_role == 'admin')
                                                            <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold border" style="background-color: #fefce8; color: #a16207; border-color: #fde68a;">
                                                                {{ auth()->user()->role->nama_role }}
                                                            </span>
                                                        @elseif(auth()->user()->role->nama_role == 'staff')
                                                            <span class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold border" style="background-color: #eff6ff; color: #1d4ed8; border-color: #bfdbfe;">
                                                                {{ auth()->user()->role->nama_role }}
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700 border border-gray-200">
                                                                {{ auth()->user()->role->nama_role }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Menu Items -->
                                            <div class="py-2" style="padding-top: 0.5rem; padding-bottom: 0.5rem;">
                                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 active:bg-gray-100 transition-colors duration-100 flex items-center gap-3">
                                                    <svg class="w-5 h-5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                                    <span>Edit Profil</span>
                                                </a>
                                            </div>

                                            <!-- Logout Section -->
                                            <div class="border-t border-gray-100 px-2 py-2" style="border-top: 1px solid #f3f4f6; padding: 0.5rem;">
                                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 active:bg-red-100 transition-colors duration-100 rounded-lg flex items-center gap-3 font-medium">
                                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                                                        <span>Keluar</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Page title moved into body top -->
                        @isset($header)
                            <div class="px-4 sm:px-6 lg:px-8 py-4">
                                {{ $header }}
                            </div>
                        @endisset

                        <!-- Flash Messages / Notifications -->
                        @if(session('success') || session('error') || session('warning'))
                            <div class="px-4 sm:px-6 lg:px-8 py-4">
                                @if(session('success'))
                                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-md">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                            </div>
                                            <div class="ml-auto pl-3">
                                                <button @click="show = false" class="inline-flex text-green-400 hover:text-green-600 focus:outline-none">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(session('error'))
                                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-md">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                            </div>
                                            <div class="ml-auto pl-3">
                                                <button @click="show = false" class="inline-flex text-red-400 hover:text-red-600 focus:outline-none">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(session('warning'))
                                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md shadow-md">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                                            </div>
                                            <div class="ml-auto pl-3">
                                                <button @click="show = false" class="inline-flex text-yellow-400 hover:text-yellow-600 focus:outline-none">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        <div id="reject-note-modal" class="fixed inset-0 z-[9999] hidden" aria-hidden="true">
            <div class="absolute inset-0 bg-slate-900/50" data-role="overlay"></div>
            <div class="relative z-10 min-h-full flex items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white shadow-2xl border border-slate-200">
                    <div class="px-6 py-5 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800">Tolak Data</h3>
                        <p class="mt-1 text-sm text-slate-600" id="reject-note-description">
                            Tambahkan catatan perbaikan agar staff memahami revisi yang dibutuhkan.
                        </p>
                    </div>

                    <div class="px-6 py-5">
                        <label for="reject-note-input" class="block text-sm font-semibold text-slate-700 mb-2">Catatan Perbaikan</label>
                        <textarea id="reject-note-input" rows="5" maxlength="2000" class="w-full rounded-lg border border-slate-300 focus:border-orange-500 focus:ring-orange-500 text-sm" placeholder="Contoh: Mohon lengkapi data komoditas dan perbaiki format NIK."></textarea>
                        <div class="mt-2 flex items-center justify-between">
                            <p id="reject-note-error" class="text-sm text-red-600 hidden">Catatan perbaikan wajib diisi.</p>
                            <p class="text-xs text-slate-500 ml-auto"><span id="reject-note-count">0</span>/2000</p>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 rounded-b-2xl flex items-center justify-end gap-2">
                        <button type="button" id="reject-note-cancel" class="inline-flex items-center rounded-lg bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300">Batal</button>
                        <button type="button" id="reject-note-confirm" class="inline-flex items-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-700">Tolak & Kirim Catatan</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Scripts from Child Views -->
        <script>
            (function () {
                const modal = document.getElementById('reject-note-modal');
                if (!modal) return;

                const overlay = modal.querySelector('[data-role="overlay"]');
                const desc = document.getElementById('reject-note-description');
                const textarea = document.getElementById('reject-note-input');
                const error = document.getElementById('reject-note-error');
                const counter = document.getElementById('reject-note-count');
                const cancelButton = document.getElementById('reject-note-cancel');
                const confirmButton = document.getElementById('reject-note-confirm');

                let activeForm = null;

                function updateCounter() {
                    counter.textContent = textarea.value.length;
                }

                function closeModal() {
                    modal.classList.add('hidden');
                    modal.setAttribute('aria-hidden', 'true');
                    error.classList.add('hidden');
                    textarea.value = '';
                    updateCounter();
                    activeForm = null;
                }

                function openModal(form) {
                    activeForm = form;
                    const entityLabel = form.dataset.entity || 'data ini';
                    desc.textContent = 'Tambahkan catatan perbaikan agar staff memahami revisi yang dibutuhkan untuk ' + entityLabel + '.';
                    modal.classList.remove('hidden');
                    modal.setAttribute('aria-hidden', 'false');
                    error.classList.add('hidden');
                    textarea.value = '';
                    updateCounter();
                    setTimeout(() => textarea.focus(), 50);
                }

                function bindRejectForms() {
                    document.querySelectorAll('form.form-reject-catatan').forEach(function (form) {
                        if (form.dataset.rejectBound === '1') return;
                        form.dataset.rejectBound = '1';
                        form.removeAttribute('onsubmit');
                        form.addEventListener('submit', function (event) {
                            event.preventDefault();
                            openModal(form);
                        });
                    });
                }

                confirmButton.addEventListener('click', function () {
                    if (!activeForm) return;
                    const note = textarea.value.trim();
                    if (!note) {
                        error.classList.remove('hidden');
                        textarea.focus();
                        return;
                    }

                    const noteInput = activeForm.querySelector('input[name="catatan_perbaikan"]');
                    if (noteInput) {
                        noteInput.value = note;
                    }

                    const formToSubmit = activeForm;
                    closeModal();
                    formToSubmit.submit();
                });

                cancelButton.addEventListener('click', closeModal);
                overlay.addEventListener('click', closeModal);
                textarea.addEventListener('input', function () {
                    if (!error.classList.contains('hidden') && textarea.value.trim()) {
                        error.classList.add('hidden');
                    }
                    updateCounter();
                });

                document.addEventListener('keydown', function (event) {
                    if (modal.classList.contains('hidden')) return;
                    if (event.key === 'Escape') {
                        closeModal();
                    }
                });

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', bindRejectForms);
                } else {
                    bindRejectForms();
                }
            })();

            (function () {
                function findStepIndex(element) {
                    const stepContainer = element.closest('[x-show*="step==="]');
                    if (!stepContainer) return null;

                    const expression = stepContainer.getAttribute('x-show') || '';
                    const match = expression.match(/step\s*===\s*(\d+)/);
                    return match ? Number(match[1]) : null;
                }

                function moveToInvalidStep(form, invalidField) {
                    const stepIndex = findStepIndex(invalidField);
                    if (stepIndex === null) return;

                    const alpineRoot = form.closest('[x-data]');
                    if (alpineRoot && alpineRoot.__x && alpineRoot.__x.$data && typeof alpineRoot.__x.$data.step !== 'undefined') {
                        alpineRoot.__x.$data.step = stepIndex;
                    }
                }

                function bindMultiStepValidation() {
                    document.querySelectorAll('form').forEach(function (form) {
                        if (form.dataset.multistepValidationBound === '1') return;

                        const hasStepPanels = form.querySelector('[x-show*="step==="]');
                        if (!hasStepPanels) return;

                        form.dataset.multistepValidationBound = '1';

                        form.addEventListener('submit', function (event) {
                            const invalidField = form.querySelector(':invalid');
                            if (!invalidField) return;

                            moveToInvalidStep(form, invalidField);

                            event.preventDefault();
                            setTimeout(function () {
                                invalidField.reportValidity();
                                invalidField.focus({ preventScroll: false });
                            }, 50);
                        });
                    });
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', bindMultiStepValidation);
                } else {
                    bindMultiStepValidation();
                }
            })();
        </script>
        @stack('scripts')
    </body>
</html>

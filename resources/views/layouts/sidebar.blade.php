<aside class="h-screen sticky top-0 bg-white border-r border-slate-200 flex flex-col">
    <!-- Logo and Dashboard link area (compact) -->
    <div class="px-3 py-4 border-b border-slate-200">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center">
            <x-application-logo class="h-14 w-auto" />
            <span class="sr-only">Dashboard</span>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto">
        <ul class="py-3">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-5 py-3 text-base font-semibold rounded-r-full
                          {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9l7.5-6 7.5 6v9.75A2.25 2.25 0 0116.5 21h-9A2.25 2.25 0 015 18.75V9z"/></svg>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider & Caption -->
            <li class="mt-4 px-4 text-xs font-semibold text-slate-500">Menu</li>

            <!-- Akun & Keamanan -->
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75M4.5 10.5h15a2.25 2.25 0 012.25 2.25v6.75A2.25 2.25 0 0119.5 21h-15A2.25 2.25 0 012.25 19.5v-6.75A2.25 2.25 0 014.5 10.5z"/></svg>
                        Akun & Keamanan
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('profile.edit') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Manajemen profil pengguna</a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('users.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Manajemen akun</a>
                    </li>
                </ul>
            </li>

            <!-- Master Data -->
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
                        Master Data
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="#" class="block px-4 py-2 text-base rounded text-slate-700 hover:bg-blue-50">Komoditas</a>
                    </li>
                    <li>
                        <a href="#" class="block px-4 py-2 text-base rounded text-slate-700 hover:bg-blue-50">Sarana/Prasarana</a>
                    </li>
                </ul>
            </li>

            <!-- Pendataan -->
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h16.5m-12 4.5h12M3.75 13.5h16.5m-12 4.5h12"/></svg>
                        Pendataan
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('pembudidaya.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('pembudidaya.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Data Pembudidaya</a>
                    </li>
                </ul>
            </li>

            <!-- Peta Lokasi -->
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 10.5-7.5 10.5S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                        Peta Lokasi
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="#" class="block px-4 py-2 text-base rounded text-slate-700 hover:bg-blue-50">Sebaran Lokasi</a>
                    </li>
                </ul>
            </li>

            <!-- Laporan -->
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h13.5m-13.5 6H21M3 21h18M3 15h10.5"/></svg>
                        Laporan
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="#" class="block px-4 py-2 text-base rounded text-slate-700 hover:bg-blue-50">Rekapitulasi</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- User footer -->
    <div class="px-4 py-3 border-t border-slate-200 flex items-center gap-3">
        <div class="h-8 w-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs">
            {{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'U',0,1)) }}
        </div>
        <div class="min-w-0">
            <div class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</div>
            <div class="text-xs text-slate-500 truncate">{{ optional(Auth::user()->role)->nama_role ?? 'User' }}</div>
        </div>
    </div>
</aside>

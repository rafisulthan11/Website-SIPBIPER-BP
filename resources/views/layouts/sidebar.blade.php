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
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider & Caption -->
            <li class="mt-4 px-4 text-xs font-semibold text-slate-500">Menu</li>

            <!-- Akun & Keamanan -->
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Akun & Keamanan
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('profile.edit') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">
                            {{ auth()->user()->isSuperAdmin() ? 'Informasi profil pengguna' : 'Manajemen profil pengguna' }}
                        </a>
                    </li>
                    @if(auth()->user()->isSuperAdmin())
                    <li>
                        <a href="{{ route('users.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('users.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Manajemen akun</a>
                    </li>
                    @endif
                </ul>
            </li>

            <!-- Master Data -->
            @if(auth()->user()->isAdminOrSuperAdmin())
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                        Master Data
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('komoditas.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('komoditas.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Kelola Komoditas</a>
                    </li>
                    <li>
                        <a href="{{ route('pasar.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('pasar.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Kelola Data Pasar</a>
                    </li>
                </ul>
            </li>
            @endif

            <!-- Pendataan / Verifikasi Data -->
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                        {{ auth()->user()->isAdmin() ? 'Verifikasi Data' : 'Pendataan' }}
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('pembudidaya.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('pembudidaya.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Data Pembudidaya</a>
                    </li>
                    <li>
                        <a href="{{ route('pengolah.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('pengolah.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Data Pengolah</a>
                    </li>
                    <li>
                        <a href="{{ route('pemasar.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('pemasar.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Data Pemasar</a>
                    </li>
                    <li>
                        <a href="{{ route('harga-ikan-segar.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('harga-ikan-segar.*') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Data Harga Ikan</a>
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
                        <a href="{{ route('peta-lokasi.index') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('peta-lokasi.index') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Peta Interaktif Pelaku Usaha</a>
                    </li>
                </ul>
            </li>

            <!-- Laporan -->
            @if(auth()->user()->isAdminOrSuperAdmin())
            <li class="mt-1" x-data="{open:true}">
                <button @click="open=!open" class="w-full flex items-center justify-between px-5 py-3 text-base font-semibold text-slate-700 hover:bg-blue-50">
                    <span class="flex items-center gap-3">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        Laporan
                    </span>
                    <svg class="w-5 h-5 text-slate-500" :class="{'rotate-180':open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <ul x-show="open" x-transition.opacity class="ms-8 mt-1 space-y-1">
                    <li>
                        <a href="{{ route('laporan.rekapitulasi.pembudidaya') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('laporan.rekapitulasi.pembudidaya') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Rekapitulasi Pembudidaya</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.rekapitulasi.pengolah') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('laporan.rekapitulasi.pengolah') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Rekapitulasi Pengolah</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.rekapitulasi.pemasar') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('laporan.rekapitulasi.pemasar') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Rekapitulasi Pemasar</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.harga.ikan.segar') }}" class="block px-4 py-2 text-base rounded {{ request()->routeIs('laporan.harga.ikan.segar') ? 'bg-blue-100 text-slate-900' : 'text-slate-700 hover:bg-blue-50' }}">Rekapitulasi Harga Ikan</a>
                    </li>
                    <!-- Grafik link removed per user request -->
                </ul>
            </li>
            @endif
        </ul>
    </nav>

    <!-- Logout button footer -->
    <div class="px-4 py-3 border-t border-slate-200">
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-700 hover:bg-red-100 transition-colors duration-150 font-medium">
                <svg class="w-5 h-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>

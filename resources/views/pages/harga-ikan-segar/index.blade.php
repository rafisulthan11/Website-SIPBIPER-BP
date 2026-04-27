<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ auth()->user()->isAdmin() ? __('Verifikasi Data') : __('Pendataan') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg overflow-hidden shadow-md">
                <!-- Blue Header Container -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h3 class="text-2xl font-bold">Data Harga Ikan</h3>
                </div>
                
                <!-- Card container -->
                <div class="bg-white border-x border-b border-slate-200">
                    <!-- Top controls row -->
                    <div class="p-5">
                        <!-- Title -->
                        <div class="mb-4">
                            <h4 class="text-slate-800 font-semibold text-lg">Daftar Harga Ikan</h4>
                        </div>
                    
                    <!-- Show entries, Search and Add Button -->
                    <form method="GET" action="{{ route('harga-ikan-segar.index') }}" class="space-y-3">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3"
                             x-data="{ isMobile: window.innerWidth < 768 }"
                             x-init="window.addEventListener('resize', () => { isMobile = window.innerWidth < 768 })"
                             :style="isMobile
                            ? 'display:flex; flex-direction:column; align-items:stretch; justify-content:flex-start; row-gap:0.75rem; column-gap:1rem;'
                            : 'display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; justify-content:flex-start; row-gap:0.75rem; column-gap:1rem;'">
                            <!-- Left side: Show entries -->
                            <div class="flex items-center gap-2 text-sm text-slate-700" style="gap: 0.5rem; white-space: nowrap;">
                                <span class="font-medium">Show</span>
                                <select name="per_page" class="h-9 border border-gray-300 rounded-md px-3 pr-8 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm" onchange="this.form.submit()">
                                    @foreach($allowedPerPage as $n)
                                        <option value="{{ $n }}" {{ ($perPage ?? 10) == $n ? 'selected' : '' }}>{{ $n }}</option>
                                    @endforeach
                                </select>
                                <span class="font-medium">entries</span>
                            </div>

                            <!-- Right side: Filters and Add button -->
                                <div class="flex items-center gap-3"
                                 :style="isMobile
                                    ? 'display:flex; flex-direction:column; align-items:stretch; gap:0.75rem; margin-left:0; width:100%;'
                                    : 'display:flex; flex-direction:row; flex-wrap:wrap; align-items:center; gap:0.75rem; margin-left:auto; width:auto;'">
                                <!-- Tahun Filter -->
                                <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <label class="text-sm font-medium text-slate-700 whitespace-nowrap">Tahun:</label>
                                    <select name="tahun" class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm hover:border-gray-400 transition min-w-[140px]" onchange="this.form.submit()">
                                        @php $currentYear = date('Y'); @endphp
                                        <option value="" {{ $tahun === '' ? 'selected' : '' }}>Semua Tahun</option>
                                        @foreach(range(2026, $currentYear + 5) as $year)
                                            <option value="{{ $year }}" {{ $tahun == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status Filter -->
                                <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <label class="text-sm font-medium text-slate-700 whitespace-nowrap">Status:</label>
                                    <select name="status" class="border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm hover:border-gray-400 transition min-w-[140px]" onchange="this.form.submit()">
                                        <option value="" {{ $status === '' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="verified" {{ $status === 'verified' ? 'selected' : '' }}>Verified</option>
                                        <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>

                                <!-- Search -->
                                <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                                    <label class="text-sm font-medium text-slate-700 whitespace-nowrap">Search:</label>
                                        <div class="relative"
                                         :style="isMobile
                                            ? 'position:relative; width:100%; max-width:100%;'
                                            : 'position:relative; width:16rem; max-width:16rem;'">
                                        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari data..." class="border border-gray-300 rounded-md pl-10 pr-4 py-1.5 text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm hover:border-gray-400 transition w-full sm:w-64" style="width: 100%;" />
                                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                    </div>
                                </div>

                                <!-- Add Button - Only for Staff -->
                                @if(auth()->user()->role->nama_role === 'staff')
                                    <a href="{{ route('harga-ikan-segar.create') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 text-white font-semibold text-sm rounded-lg px-5 py-2 shadow-md hover:shadow-lg transition-all duration-200 whitespace-nowrap">
                                        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        Tambah Data
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Data list -->
                <div class="px-5 pb-5">
                    <!-- Mobile cards -->
                    <div class="md:hidden space-y-3">
                        @forelse ($hargaIkanSegars as $harga)
                            <div class="rounded-lg border border-slate-200 p-4 bg-white shadow-sm">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $harga->jenis_ikan ?? '-' }}</p>
                                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($harga->tanggal_input)->format('d/m/Y') }} | Tahun: {{ $harga->tahun_pendataan }}</p>
                                    </div>
                                    <div>
                                        @if($harga->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold" style="display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #fef3c7; color: #92400e;">Pending</span>
                                        @elseif($harga->status === 'verified')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold" style="display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #dcfce7; color: #166534;">Verified</span>
                                        @elseif($harga->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold" style="display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #fee2e2; color: #b91c1c;">Rejected</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-sm text-slate-700 space-y-1">
                                    <p><span class="font-medium">Ukuran:</span> {{ $harga->ukuran ?? '-' }}</p>
                                    <p><span class="font-medium">Harga Produsen:</span> {{ $harga->harga_produsen ? 'Rp ' . number_format($harga->harga_produsen, 0, ',', '.') : '-' }}</p>
                                    <p><span class="font-medium">Harga Konsumen:</span> {{ $harga->harga_konsumen ? 'Rp ' . number_format($harga->harga_konsumen, 0, ',', '.') : '-' }}</p>
                                    <p><span class="font-medium">Satuan:</span> {{ $harga->satuan ?? '-' }}</p>
                                    <p><span class="font-medium">Lokasi:</span> {{ $harga->desa->nama_desa ?? '-' }}, {{ $harga->kecamatan->nama_kecamatan ?? '-' }}</p>
                                    @if($harga->status === 'rejected' && $harga->catatan_perbaikan)
                                        <p class="text-xs text-red-700" style="margin-top: 0.25rem; font-size: 0.75rem; line-height: 1.35; color: #b91c1c;"><span class="font-semibold">Catatan:</span> {{ $harga->catatan_perbaikan }}</p>
                                    @endif
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2">
                                    <a href="{{ route('harga-ikan-segar.show', $harga->id_harga) }}" class="inline-flex items-center rounded bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700">Lihat</a>

                                    @if(auth()->user()->role->nama_role === 'staff')
                                        <a href="{{ route('harga-ikan-segar.edit', $harga->id_harga) }}" class="inline-flex items-center rounded bg-yellow-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-yellow-600">Edit</a>
                                    @endif

                                    @if(auth()->user()->isAdmin() && $harga->status === 'pending')
                                        <form action="{{ route('harga-ikan-segar.verify', $harga->id_harga) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Verifikasi</button>
                                        </form>
                                        <form action="{{ route('harga-ikan-segar.reject', $harga->id_harga) }}" method="POST" class="inline form-reject-catatan" data-entity="data harga ikan ini">
                                            @csrf
                                            <input type="hidden" name="catatan_perbaikan" value="">
                                            <button type="submit" class="inline-flex items-center rounded bg-orange-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-orange-700">Tolak</button>
                                        </form>
                                    @endif

                                    @if(auth()->user()->isAdminOrSuperAdmin())
                                        <form action="{{ route('harga-ikan-segar.destroy', $harga->id_harga) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center rounded bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="rounded-lg border border-slate-200 p-4 text-center text-slate-500">Belum ada data harga ikan.</div>
                        @endforelse
                    </div>

                    <!-- Desktop table -->
                    <div class="hidden md:block overflow-x-auto">
                    <div class="rounded-md border border-slate-300 overflow-hidden">
                        <table class="min-w-full text-base">
                            <thead class="bg-slate-100 text-slate-800">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Tanggal</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Tahun Pendataan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Status</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Jenis Ikan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Ukuran</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Harga Produsen</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Harga Konsumen</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Satuan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Lokasi</th>
                                    <th class="px-4 py-3 text-left font-semibold text-[15px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($hargaIkanSegars as $harga)
                                <tr class="border-t border-slate-200">
                                    <td class="px-4 py-3 align-top text-slate-700">{{ \Carbon\Carbon::parse($harga->tanggal_input)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700 font-semibold text-blue-600">{{ $harga->tahun_pendataan }}</td>
                                    <td class="px-4 py-3 align-top">
                                        @if($harga->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold" style="display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #fef3c7; color: #92400e;">Pending</span>
                                        @elseif($harga->status === 'verified')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold" style="display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #dcfce7; color: #166534;">Verified</span>
                                        @elseif($harga->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold" style="display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background-color: #fee2e2; color: #b91c1c;">Rejected</span>
                                            @if($harga->catatan_perbaikan)
                                                <div class="mt-1 text-xs text-red-700 max-w-xs break-words" style="margin-top: 0.3rem; font-size: 0.75rem; line-height: 1.35; color: #b91c1c; max-width: 20rem; word-break: break-word;">
                                                    <span class="font-semibold">Catatan:</span> {{ $harga->catatan_perbaikan }}
                                                </div>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->jenis_ikan ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->ukuran ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->harga_produsen ? 'Rp ' . number_format($harga->harga_produsen, 0, ',', '.') : '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->harga_konsumen ? 'Rp ' . number_format($harga->harga_konsumen, 0, ',', '.') : '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->satuan ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top text-slate-700">{{ $harga->desa->nama_desa ?? '-' }}, {{ $harga->kecamatan->nama_kecamatan ?? '-' }}</td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('harga-ikan-segar.show', $harga->id_harga) }}" class="inline-flex items-center rounded bg-green-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-green-700">
                                                Lihat
                                            </a>
                                            
                                            @if(auth()->user()->role->nama_role === 'staff')
                                                <a href="{{ route('harga-ikan-segar.edit', $harga->id_harga) }}" class="inline-flex items-center rounded bg-yellow-500 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-yellow-600">
                                                    Edit
                                                </a>
                                            @endif

                                            @if(auth()->user()->isAdmin() && $harga->status === 'pending')
                                                <form action="{{ route('harga-ikan-segar.verify', $harga->id_harga) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center rounded bg-blue-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-blue-700">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                                <form action="{{ route('harga-ikan-segar.reject', $harga->id_harga) }}" method="POST" class="inline form-reject-catatan" data-entity="data harga ikan ini">
                                                    @csrf
                                                    <input type="hidden" name="catatan_perbaikan" value="">
                                                    <button type="submit" class="inline-flex items-center rounded bg-orange-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-orange-700">
                                                        Tolak
                                                    </button>
                                                </form>
                                            @endif

                                            @if(auth()->user()->isAdminOrSuperAdmin())
                                                <form action="{{ route('harga-ikan-segar.destroy', $harga->id_harga) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center rounded bg-red-600 px-3.5 py-1.5 text-sm font-semibold text-white hover:bg-red-700">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-6 text-center text-slate-500">Belum ada data harga ikan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $hargaIkanSegars->onEachSide(1)->links('components.pagination.custom') }}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</x-app-layout>

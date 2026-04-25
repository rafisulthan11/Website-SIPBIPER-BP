<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl sm:text-3xl text-slate-800 leading-tight">
            {{ __('Semua Notifikasi') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg overflow-hidden shadow-md">
                <!-- Blue Header Container -->
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h3 class="text-2xl font-bold">Riwayat Notifikasi</h3>
                </div>
                
                <!-- Card container -->
                <div class="bg-white border-x border-b border-slate-200">
                    <div class="p-5">
                        @if($notifications->count() > 0)
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                @foreach($notifications as $notif)
                                    <a href="{{ $notif->url }}"
                                       class="block"
                                       style="display: block; padding: 1rem; border-radius: 0.5rem; border: 1px solid {{ $notif->is_read ? '#e5e7eb' : '#bfdbfe' }}; background-color: {{ $notif->is_read ? '#ffffff' : '#eff6ff' }}; box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06); transition: box-shadow 0.2s ease, transform 0.2s ease;"
                                       onmouseover="this.style.boxShadow='0 8px 18px rgba(15, 23, 42, 0.12)'; this.style.transform='translateY(-1px)'"
                                       onmouseout="this.style.boxShadow='0 1px 2px rgba(15, 23, 42, 0.06)'; this.style.transform='translateY(0)'">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-1" style="flex: 0 0 auto; margin-top: 0.25rem; width: 0.75rem; display: flex; justify-content: center;">
                                                @if(!$notif->is_read)
                                                    <div style="width: 0.75rem; height: 0.75rem; background-color: #3b82f6; border-radius: 9999px;"></div>
                                                @else
                                                    <div style="width: 0.75rem; height: 0.75rem; background-color: #c0c0c0; border-radius: 9999px;"></div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900">{{ $notif->title }}</h4>
                                                        <p class="text-sm text-gray-600 mt-1">{{ $notif->message }}</p>
                                                        <p class="text-xs text-gray-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                                                    </div>
                                                    <div>
                                                        @if($notif->type === 'create')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Baru</span>
                                                        @elseif($notif->type === 'update')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Update</span>
                                                        @elseif($notif->type === 'verified')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Verified</span>
                                                        @elseif($notif->type === 'rejected')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $notifications->links('components.pagination.custom') }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V8.25a6 6 0 10-12 0v1.5a8.967 8.967 0 01-2.312 6.022c1.766.68 3.56 1.13 5.454 1.31m5.715 0a24.255 24.255 0 01-5.715 0m5.715 0a3 3 0 11-5.715 0"/>
                                </svg>
                                <p class="text-gray-500 text-lg">Tidak ada notifikasi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

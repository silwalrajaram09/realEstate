<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Properties Control</h2>
            <p class="text-sm text-gray-500 mt-1">Manage, approve and verify platform listings.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Search title, location..." 
                       class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64 text-sm">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
             @if(count($selected) > 0)
                <div class="flex items-center gap-2">
                    @if($hasPendingSelected)
                        <button wire:click="bulkApprove" class="bg-green-600 text-white px-4 py-2 rounded-lg text-[11px] font-bold shadow-sm hover:bg-green-700 transition uppercase tracking-wider">
                            Approve Selected
                        </button>
                    @endif
                    <button wire:click="bulkDelete" onclick="confirm('Delete all selected properties?') || event.stopImmediatePropagation()" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg text-[11px] font-bold shadow-sm hover:bg-red-700 transition uppercase tracking-wider">
                        Delete Selected
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-gray-200 mb-6">
        @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label)
            <button wire:click="setStatus('{{ $key }}')" 
                    class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $status === $key ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
                @if($key === 'pending' && $pendingCount > 0)
                    <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded-full text-[10px]">{{ $pendingCount }}</span>
                @endif
            </button>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
        <!-- Loading Overlay -->
        <div wire:loading.flex class="absolute inset-0 bg-white/50 z-10 items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-blue-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left w-10">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   id="selectAll" onclick="toggleAll(this)">
                        </th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Property</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Seller</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Economics</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($properties as $property)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" wire:model.live="selected" value="{{ $property->id }}"
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-100">
                                        <img src="{{ $property->image_url }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $property->title }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $property->location }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $property->seller->name ?? 'Private' }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $property->seller->email ?? 'no-email' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-blue-600">Rs {{ number_format($property->price) }}</div>
                                <div class="text-[10px] text-gray-400 capitalize">{{ $property->purpose }} · {{ $property->type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider
                                            {{ $property->status === 'approved' ? 'bg-green-50 text-green-700 border border-green-100' : 
                                               ($property->status === 'pending' ? 'bg-yellow-50 text-yellow-700 border border-yellow-100' : 'bg-red-50 text-red-700 border border-red-100') }}">
                                    {{ $property->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if($property->status === 'pending')
                                        <button wire:click="approve({{ $property->id }})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Approve">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button wire:click="reject({{ $property->id }})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Reject">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                        <button wire:click="deleteProperty({{ $property->id }})" 
                                                onclick="confirm('Are you sure you want to PERMANENTLY delete this property?') || event.stopImmediatePropagation()"
                                                class="p-2 text-red-700 hover:bg-red-50 rounded-lg transition" title="Delete Permanently">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @elseif($property->status === 'rejected')
                                        <button wire:click="approve({{ $property->id }})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition" title="Restore & Approve">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button wire:click="deleteProperty({{ $property->id }})" 
                                                onclick="confirm('Are you sure you want to PERMANENTLY delete this property?') || event.stopImmediatePropagation()"
                                                class="p-2 text-red-700 hover:bg-red-50 rounded-lg transition" title="Delete Permanently">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                    <a href="{{ route('buyer.properties.show', $property->id) }}" target="_blank" class="p-2 text-gray-400 hover:bg-gray-50 rounded-lg transition" title="Preview Listing">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-gray-400 font-medium font-serif italic text-lg">No listings found matching your criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($properties->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $properties->links() }}
            </div>
        @endif
    </div>
</div>

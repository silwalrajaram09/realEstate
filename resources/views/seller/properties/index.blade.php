<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header with Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h1 class="text-2xl font-bold text-gray-900">My Properties</h1>

                <div class="flex items-center gap-3">
                    <!-- Rejected Properties Button (NEW) -->
                    <a href="{{ route('seller.properties.rejected') }}"
                        class="bg-red-100 text-red-700 px-4 py-2 rounded-lg font-medium hover:bg-red-200 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Rejected Properties
                    </a>

                    <!-- Add Property Button -->
                    <a href="{{ route('seller.properties.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Property
                    </a>
                </div>
            </div>

            <!-- Status Filter Tabs (Optional Enhancement) -->
            <div class="flex gap-2 mb-6">
                <a href="{{ route('seller.properties.index') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All Properties
                </a>
                <!-- <a href="{{ route('seller.properties.index', ['status' => 'approved']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'approved' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Approved
                </a>
                <a href="{{ route('seller.properties.index', ['status' => 'pending']) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pending
                </a>
                <a href="{{ route('seller.properties.rejected') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'rejected' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Rejected
                </a> -->
            </div>

            <!-- Properties Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Listed</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($properties as $property)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                                                @if($property->image)
                                                    <img src="{{ asset('images/' . $property->image) }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $property->title }}</div>
                                                <div class="text-sm text-gray-500">{{ $property->location }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Rs
                                        {{ number_format($property->price) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                                    @if($property->status === 'approved') bg-green-100 text-green-800 
                                                                    @elseif($property->status === 'pending') bg-yellow-100 text-yellow-800 
                                                                    @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                        @if($property->status === 'rejected' && $property->rejection_reason)
                                            <span class="ml-2 text-xs text-gray-500 cursor-help"
                                                title="{{ $property->rejection_reason }}">
                                                ⓘ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $property->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('seller.properties.show', $property->id) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>

                                        @if($property->status !== 'rejected')
                                            <a href="{{ route('seller.properties.edit', $property->id) }}"
                                                class="text-gray-600 hover:text-gray-900 mr-3">Edit</a>
                                        @endif

                                        <form action="{{ route('seller.properties.destroy', $property->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        @if(request('status') == 'rejected')
                                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <p class="text-gray-500 mb-2">No rejected properties found.</p>
                                            <p class="text-sm text-gray-400 mb-4">Properties you submit will appear here if
                                                rejected by admin.</p>
                                            <a href="{{ route('seller.properties.create') }}"
                                                class="text-blue-600 hover:text-blue-700 font-medium">
                                                + Add New Property
                                            </a>
                                        @else
                                            <p class="text-gray-500 mb-4">No properties Rejected yet.</p>
                                            <a href="{{ route('seller.properties.create') }}"
                                                class="text-blue-600 hover:text-blue-700 font-medium">+ Add Your First
                                                Property</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($properties, 'hasPages') && $properties->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $properties->links() }}
                    </div>
                @endif
            </div>

            <!-- Quick Stats Summary (Optional) -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <p class="text-sm text-gray-500">Total Properties</p>
                    <p class="text-xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <p class="text-sm text-gray-500">Approved</p>
                    <p class="text-xl font-bold text-green-600">{{ $stats['approved'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <p class="text-sm text-gray-500">Pending</p>
                    <p class="text-xl font-bold text-yellow-600">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                    <p class="text-sm text-gray-500">Rejected</p>
                    <p class="text-xl font-bold text-red-600">{{ $stats['rejected'] ?? 0 }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
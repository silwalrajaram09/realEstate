<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Seller Dashboard</h1>
                    <p class="text-gray-500 text-sm mt-1">
                        Manage your property listings
                    </p>
                </div>

                <a href="{{ route('seller.properties.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                    + Add Property
                </a>
            </div>

            {{-- STATS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

                {{-- TOTAL --}}
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>

                {{-- APPROVED --}}
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" d="M9 12l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Approved</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] }}</p>
                    </div>
                </div>

                {{-- PENDING --}}
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" d="M12 8v4l3 3" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                    </div>
                </div>

                {{-- REJECTED --}}
                <div class="bg-white rounded-xl shadow-sm p-6 flex items-center gap-4">
                    <div class="p-3 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" d="M10 14l4-4m0 4l-4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rejected</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] }}</p>
                    </div>
                </div>

            </div>

            {{-- PROPERTIES TABLE --}}
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">

                <div class="px-6 py-4 border-b flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Your Properties</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Purpose</th>
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
                                        <div class="font-medium text-gray-900">{{ $property->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $property->location }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-sm font-medium">
                                        ₹ {{ number_format($property->price) }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full
                                                {{ $property->purpose === 'buy' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($property->purpose) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full
                                                @if($property->status === 'approved') bg-green-100 text-green-800
                                                @elseif($property->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $property->created_at->format('M d, Y') }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm">
                                        <a href="{{ route('seller.properties.show', $property) }}"
                                            class="text-blue-600 hover:underline mr-3">View</a>

                                        <a href="{{ route('seller.properties.edit', $property) }}"
                                            class="text-gray-600 hover:underline mr-3">Edit</a>

                                        <form action="{{ route('seller.properties.destroy', $property) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Delete this property?')"
                                                class="text-red-600 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No properties listed yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="px-6 py-4 border-t">
                    {{ $properties->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
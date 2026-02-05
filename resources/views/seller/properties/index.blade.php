<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">My Properties</h1>
                <a href="{{ route('seller.properties.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                    + Add Property
                </a>
            </div>

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
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                                                @if($property->image)
                                                    <img src="{{ asset('images/' . $property->image) }}"
                                                        class="h-full w-full object-cover">
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $property->title }}</div>
                                                <div class="text-sm text-gray-500">{{ $property->location }}</div>
                                            </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Rs
                                        {{ number_format($property->price) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full @if($property->status === 'approved') bg-green-100 text-green-800 @elseif($property->status === 'pending') bg-yellow-100 text-yellow-800 @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $property->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('seller.properties.show', $property->id) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        <a href="{{ route('seller.properties.edit', $property->id) }}"
                                            class="text-gray-600 hover:text-gray-900 mr-3">Edit</a>
                                        <form action="{{ route('seller.properties.destroy', $property->id) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Delete this property?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <p class="text-gray-500 mb-4">No properties listed yet.</p>
                                        <a href="{{ route('seller.properties.create') }}"
                                            class="text-blue-600 hover:text-blue-700 font-medium">+ Add Your First
                                            Property</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if(method_exists($properties, 'hasPages') && $properties->hasPages())
                    {{ $properties->links() }}
                @endif

            </div>
        </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        My Properties
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Success message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('seller.properties.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add New Property
            </a>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Purpose</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($properties as $property)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ $property->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ ucfirst($property->purpose) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ ucfirst($property->type) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ ucfirst($property->category) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">${{ number_format($property->price, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-200">{{ $property->location }}</td>
                            <td class="px-6 py-4 text-center text-sm font-medium space-x-2">
                                <a href="{{ route('seller.properties.edit', $property->id) }}"
                                   class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                <form action="{{ route('seller.properties.destroy', $property->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                You have not added any properties yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        Add New Property
    </x-slot>

    <div class="max-w-3xl mx-auto p-4 bg-white dark:bg-gray-800 rounded shadow">
        <form action="{{ route('seller.properties.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-gray-700 dark:text-gray-300">Title</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700 dark:text-gray-300">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label>Purpose</label>
                    <select name="purpose" class="w-full border rounded px-3 py-2">
                        <option value="buy">Buy</option>
                        <option value="sell">Sell</option>
                    </select>
                </div>

                <div>
                    <label>Type</label>
                    <select name="type" class="w-full border rounded px-3 py-2">
                        <option value="flat">Flat</option>
                        <option value="house">House</option>
                        <option value="land">Land</option>
                    </select>
                </div>

                <div>
                    <label>Category</label>
                    <select name="category" class="w-full border rounded px-3 py-2">
                        <option value="residential">Residential</option>
                        <option value="commercial">Commercial</option>
                    </select>
                </div>

                <div>
                    <label>Price</label>
                    <input type="number" name="price" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label>Location</label>
                    <input type="text" name="location" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label>Bedrooms</label>
                    <input type="number" name="bedrooms" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label>Bathrooms</label>
                    <input type="number" name="bathrooms" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label>Area (sq ft)</label>
                    <input type="number" name="area" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="parking" value="1">
                    <label>Parking</label>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="water" value="1" checked>
                    <label>Water</label>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Add Property</button>
        </form>
    </div>
</x-app-layout>

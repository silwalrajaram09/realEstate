<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Add New Property</h1>
                <a href="{{ route('seller.properties.index') }}" class="text-gray-600 hover:text-gray-900">
                    ← Back to Properties
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <form action="{{ route('seller.properties.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Property Title
                                *</label>
                            <input type="text" name="title" id="title" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., Beautiful 3 BHK Apartment in Thamel">
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Describe your property..."></textarea>
                        </div>

                        <!-- Purpose -->
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">Purpose *</label>
                            <select name="purpose" id="purpose" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="buy">For Sale</option>
                                <option value="sell">Sell</option>
                            </select>
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Property Type
                                *</label>
                            <select name="type" id="type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="flat">Flat</option>
                                <option value="house">House</option>
                                <option value="land">Land</option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category
                                *</label>
                            <select name="category" id="category" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="residential">Residential</option>
                                <option value="commercial">Commercial</option>
                            </select>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (NPR)
                                *</label>
                            <input type="number" name="price" id="price" required min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., 5000000">
                        </div>

                        <!-- Location -->
                        <div class="md:col-span-2">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location
                                *</label>
                            <input type="text" name="location" id="location" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., Thamel, Kathmandu">
                        </div>

                        <!-- Bedrooms -->
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                            <input type="number" name="bedrooms" id="bedrooms" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., 3">
                        </div>

                        <!-- Bathrooms -->
                        <div>
                            <label for="bathrooms"
                                class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                            <input type="number" name="bathrooms" id="bathrooms" min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., 2">
                        </div>

                        <!-- Area -->
                        <div>
                            <label for="area" class="block text-sm font-medium text-gray-700 mb-2">Area (sq.ft)
                                *</label>
                            <input type="number" name="area" id="area" required min="0"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="e.g., 1500">
                        </div>

                        <!-- Features -->
                        <div class="flex items-center gap-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="parking" value="1"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Parking</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="water" value="1" checked
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Water Supply</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('seller.properties.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                            List Property
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
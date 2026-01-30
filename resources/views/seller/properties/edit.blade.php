<x-app-layout>
    <x-slot name="header">
        Edit Property
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

            <form method="POST"
                  action="{{ route('seller.properties.update', $property->id) }}">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Title</label>
                    <input type="text" name="title"
                           value="{{ old('title', $property->title) }}"
                           class="w-full mt-1 rounded border-gray-300">
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Description</label>
                    <textarea name="description"
                              class="w-full mt-1 rounded border-gray-300">{{ old('description', $property->description) }}</textarea>
                </div>

                <!-- Purpose -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Purpose</label>
                    <select name="purpose" class="w-full mt-1 rounded border-gray-300">
                        <option value="buy" {{ $property->purpose === 'buy' ? 'selected' : '' }}>Buy</option>
                        <option value="sell" {{ $property->purpose === 'sell' ? 'selected' : '' }}>Sell</option>
                    </select>
                </div>

                <!-- Type -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Type</label>
                    <select name="type" class="w-full mt-1 rounded border-gray-300">
                        @foreach(['flat','house','land'] as $type)
                            <option value="{{ $type }}" {{ $property->type === $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Category</label>
                    <select name="category" class="w-full mt-1 rounded border-gray-300">
                        <option value="residential" {{ $property->category === 'residential' ? 'selected' : '' }}>
                            Residential
                        </option>
                        <option value="commercial" {{ $property->category === 'commercial' ? 'selected' : '' }}>
                            Commercial
                        </option>
                    </select>
                </div>

                <!-- Price -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Price</label>
                    <input type="number" name="price"
                           value="{{ old('price', $property->price) }}"
                           class="w-full mt-1 rounded border-gray-300">
                </div>

                <!-- Location -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Location</label>
                    <input type="text" name="location"
                           value="{{ old('location', $property->location) }}"
                           class="w-full mt-1 rounded border-gray-300">
                </div>

                <!-- Bedrooms / Bathrooms / Area -->
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <input type="number" name="bedrooms" placeholder="Bedrooms"
                           value="{{ old('bedrooms', $property->bedrooms) }}"
                           class="rounded border-gray-300">

                    <input type="number" name="bathrooms" placeholder="Bathrooms"
                           value="{{ old('bathrooms', $property->bathrooms) }}"
                           class="rounded border-gray-300">

                    <input type="number" name="area" placeholder="Area (sq ft)"
                           value="{{ old('area', $property->area) }}"
                           class="rounded border-gray-300">
                </div>

                <!-- Features -->
                <div class="flex gap-6 mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="parking" value="1"
                               {{ $property->parking ? 'checked' : '' }}>
                        <span class="ml-2">Parking</span>
                    </label>

                    <label class="flex items-center">
                        <input type="checkbox" name="water" value="1"
                               {{ $property->water ? 'checked' : '' }}>
                        <span class="ml-2">Water</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('seller.properties.index') }}"
                       class="px-4 py-2 border rounded">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Update Property
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>

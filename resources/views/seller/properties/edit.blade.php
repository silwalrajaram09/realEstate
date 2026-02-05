<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Edit Property</h1>
                <a href="{{ route('seller.properties.index') }}" class="text-gray-600 hover:text-gray-900">
                    ← Back to List
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <form action="{{ route('seller.properties.update', $property->id) }}" method="POST"
                    class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $property->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $property->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Purpose -->
                        <div>
                            <x-input-label for="purpose" :value="__('Purpose')" />
                            <select id="purpose" name="purpose"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="buy" {{ old('purpose', $property->purpose) === 'buy' ? 'selected' : '' }}>
                                    Buy</option>
                                <option value="sell" {{ old('purpose', $property->purpose) === 'sell' ? 'selected' : '' }}>Sell</option>
                            </select>
                        </div>

                        <!-- Type -->
                        <div>
                            <x-input-label for="type" :value="__('Type')" />
                            <select id="type" name="type"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="flat" {{ old('type', $property->type) === 'flat' ? 'selected' : '' }}>Flat
                                </option>
                                <option value="house" {{ old('type', $property->type) === 'house' ? 'selected' : '' }}>
                                    House</option>
                                <option value="land" {{ old('type', $property->type) === 'land' ? 'selected' : '' }}>Land
                                </option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <x-input-label for="category" :value="__('Category')" />
                            <select id="category" name="category"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="residential" {{ old('category', $property->category) === 'residential' ? 'selected' : '' }}>Residential</option>
                                <option value="commercial" {{ old('category', $property->category) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                            </select>
                        </div>
                    </div>

                    <!-- Price -->
                    <div>
                        <x-input-label for="price" :value="__('Price (NPR)')" />
                        <x-text-input id="price" name="price" type="number" class="mt-1 block w-full"
                            :value="old('price', $property->price)" required />
                    </div>

                    <!-- Location -->
                    <div>
                        <x-input-label for="location" :value="__('Location')" />
                        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
                            :value="old('location', $property->location)" required />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Bedrooms -->
                        <div>
                            <x-input-label for="bedrooms" :value="__('Bedrooms')" />
                            <x-text-input id="bedrooms" name="bedrooms" type="number" class="mt-1 block w-full"
                                :value="old('bedrooms', $property->bedrooms)" />
                        </div>

                        <!-- Bathrooms -->
                        <div>
                            <x-input-label for="bathrooms" :value="__('Bathrooms')" />
                            <x-text-input id="bathrooms" name="bathrooms" type="number" class="mt-1 block w-full"
                                :value="old('bathrooms', $property->bathrooms)" />
                        </div>

                        <!-- Area -->
                        <div>
                            <x-input-label for="area" :value="__('Area (sq.ft)')" />
                            <x-text-input id="area" name="area" type="number" class="mt-1 block w-full"
                                :value="old('area', $property->area)" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Parking -->
                        <div class="flex items-center">
                            <input type="checkbox" name="parking" id="parking" value="1" {{ old('parking', $property->parking) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <label for="parking" class="ml-2 text-sm text-gray-700">Has Parking</label>
                        </div>

                        <!-- Water -->
                        <div class="flex items-center">
                            <input type="checkbox" name="water" id="water" value="1" {{ old('water', $property->water) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <label for="water" class="ml-2 text-sm text-gray-700">Has Water Supply</label>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-4">
                        <x-primary-button>
                            {{ __('Update Property') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
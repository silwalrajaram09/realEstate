<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Edit Property</h1>
                <a href="{{ route('seller.properties.index') }}" class="text-gray-600 hover:text-gray-900">
                    ← Back to List
                </a>
            </div>

            <div class="bg-white shadow rounded-lg">
                <form x-data="propertyForm()" x-init="initMap()" method="POST"
                    action="{{ route('seller.properties.update', $property->id) }}" enctype="multipart/form-data"
                    class="p-6 space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- BASIC INFO -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Basic Information</h2>

                        <div>
                            <label class="block text-sm font-medium mb-2">Property Title *</label>
                            <input type="text" name="title" required
                                class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Beautiful 3BHK Apartment in Thamel" maxlength="255"
                                value="{{ old('title', $property->title) }}">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Description</label>
                            <textarea name="description" rows="4"
                                class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Describe your property in detail...">{{ old('description', $property->description) }}</textarea>
                        </div>

                        <!-- PURPOSE, TYPE & CATEGORY -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Purpose *</label>
                                <select name="purpose" x-model="purpose"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="buy" {{ old('purpose', $property->purpose) === 'buy' ? 'selected' : '' }}>For Sale</option>
                                    <option value="rent" {{ old('purpose', $property->purpose) === 'rent' ? 'selected' : '' }}>For Rent</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Property Type *</label>
                                <select name="type" x-model="type"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="flat" {{ old('type', $property->type) === 'flat' ? 'selected' : '' }}>
                                        Flat/Apartment</option>
                                    <option value="house" {{ old('type', $property->type) === 'house' ? 'selected' : '' }}>House</option>
                                    <option value="land" {{ old('type', $property->type) === 'land' ? 'selected' : '' }}>
                                        Land/Plot</option>
                                    <option value="commercial" {{ old('type', $property->type) === 'commercial' ? 'selected' : '' }}>Commercial Space</option>
                                    <option value="office" {{ old('type', $property->type) === 'office' ? 'selected' : '' }}>Office</option>
                                    <option value="warehouse" {{ old('type', $property->type) === 'warehouse' ? 'selected' : '' }}>Warehouse</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Category *</label>
                                <select name="category" required x-model="category"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="residential" {{ old('category', $property->category) === 'residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="commercial" {{ old('category', $property->category) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="industrial" {{ old('category', $property->category) === 'industrial' ? 'selected' : '' }}>Industrial</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- PRICE & AREA -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Pricing & Size</h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2"
                                    x-text="purpose === 'rent' ? 'Monthly Rent (NPR) *' : 'Total Price (NPR) *'"></label>
                                <input type="number" name="price" required min="1" step="0.01"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 5000000" value="{{ old('price', $property->price) }}">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Area (sq. ft) *</label>
                                <input type="number" name="area" required min="1"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 1500" value="{{ old('area', $property->area) }}">
                                @error('area')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="purpose === 'rent'">
                                <label class="block text-sm font-medium mb-2">Minimum Lease (months)</label>
                                <select name="min_lease_months"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="1" {{ old('min_lease_months', $property->min_lease_months) == '1' ? 'selected' : '' }}>1 Month</option>
                                    <option value="3" {{ old('min_lease_months', $property->min_lease_months) == '3' ? 'selected' : '' }}>3 Months</option>
                                    <option value="6" {{ old('min_lease_months', $property->min_lease_months) == '6' ? 'selected' : '' }}>6 Months</option>
                                    <option value="12" {{ old('min_lease_months', $property->min_lease_months) == '12' ? 'selected' : '' }}>1 Year</option>
                                    <option value="24" {{ old('min_lease_months', $property->min_lease_months) == '24' ? 'selected' : '' }}>2 Years</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- LOCATION -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Location</h2>

                        <!-- Search Box -->
                        <div class="relative">
                            <label class="block text-sm font-medium mb-2">Search Location *</label>
                            <div class="relative">
                                <input type="text" id="locationSearch"
                                    class="w-full border rounded-lg px-4 py-2.5 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search for location in Nepal (e.g., Thamel, Kathmandu)">
                                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm mt-1">Search or click on the map to set location</p>
                        </div>

                        <!-- Map Container -->
                        <div id="map" class="w-full h-96 rounded-lg border" style="z-index: 1;"></div>

                        <!-- Selected Coordinates Display -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2">Location/Address *</label>
                                <input type="text" name="location" required
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    x-model="locationAddress" placeholder="Enter or select location"
                                    value="{{ old('location', $property->location) }}">
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Latitude</label>
                                <input type="number" name="latitude" step="any" readonly
                                    class="w-full border rounded-lg px-4 py-2.5 bg-gray-50" x-model="lat"
                                    placeholder="Click map to select"
                                    value="{{ old('latitude', $property->latitude) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Longitude</label>
                                <input type="number" name="longitude" step="any" readonly
                                    class="w-full border rounded-lg px-4 py-2.5 bg-gray-50" x-model="lng"
                                    placeholder="Click map to select"
                                    value="{{ old('longitude', $property->longitude) }}">
                            </div>
                        </div>
                    </div>

                    <!-- IMAGE UPLOAD -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Images</h2>

                        @if($property->image)
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Current Image</label>
                                <img src="{{ asset('images/' . $property->image) }}"
                                    alt="Current Property Image"
                                    class="w-48 h-32 object-cover rounded-lg border">
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium mb-2">Change Image</label>
                            <input type="file" name="image" accept="image/*"
                                class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-gray-500 text-sm mt-1">Upload a new image (JPG, PNG, max 2MB)</p>
                        </div>
                    </div>

                    <!-- RESIDENTIAL - FLAT/HOUSE -->
                    <div x-show="(type === 'flat' || type === 'house') && category === 'residential'"
                        x-transition class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Property Details</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Bedrooms</label>
                                <input type="number" name="bedrooms" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 3"
                                    value="{{ old('bedrooms', $property->bedrooms) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Bathrooms</label>
                                <input type="number" name="bathrooms" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 2"
                                    value="{{ old('bathrooms', $property->bathrooms) }}">
                            </div>

                            <div x-show="type === 'flat'">
                                <label class="block text-sm font-medium mb-2">Floor No</label>
                                <input type="number" name="floor_no" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 2"
                                    value="{{ old('floor_no', $property->floor_no) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Year Built</label>
                                <input type="number" name="year_built" min="1900"
                                    :max="new Date().getFullYear()"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :placeholder="'e.g., ' + new Date().getFullYear()"
                                    value="{{ old('year_built', $property->year_built) }}">
                            </div>
                        </div>
                    </div>

                    <!-- LAND/PLOT -->
                    <div x-show="type === 'land'" x-transition class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Land Details</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Road Access (ft)</label>
                                <input type="number" name="road_access"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 20"
                                    value="{{ old('road_access', $property->road_access) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Facing</label>
                                <select name="facing"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="east" {{ old('facing', $property->facing) === 'east' ? 'selected' : '' }}>East</option>
                                    <option value="west" {{ old('facing', $property->facing) === 'west' ? 'selected' : '' }}>West</option>
                                    <option value="north" {{ old('facing', $property->facing) === 'north' ? 'selected' : '' }}>North</option>
                                    <option value="south" {{ old('facing', $property->facing) === 'south' ? 'selected' : '' }}>South</option>
                                    <option value="northeast" {{ old('facing', $property->facing) === 'northeast' ? 'selected' : '' }}>Northeast</option>
                                    <option value="northwest" {{ old('facing', $property->facing) === 'northwest' ? 'selected' : '' }}>Northwest</option>
                                    <option value="southeast" {{ old('facing', $property->facing) === 'southeast' ? 'selected' : '' }}>Southeast</option>
                                    <option value="southwest" {{ old('facing', $property->facing) === 'southwest' ? 'selected' : '' }}>Southwest</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Land Shape</label>
                                <select name="land_shape"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="rectangle" {{ old('land_shape', $property->land_shape) === 'rectangle' ? 'selected' : '' }}>Rectangle</option>
                                    <option value="square" {{ old('land_shape', $property->land_shape) === 'square' ? 'selected' : '' }}>Square</option>
                                    <option value="irregular" {{ old('land_shape', $property->land_shape) === 'irregular' ? 'selected' : '' }}>Irregular</option>
                                    <option value="triangular" {{ old('land_shape', $property->land_shape) === 'triangular' ? 'selected' : '' }}>Triangular</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Plot Number</label>
                                <input type="text" name="plot_number"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., Kitta 12"
                                    value="{{ old('plot_number', $property->plot_number) }}">
                            </div>
                        </div>
                    </div>

                    <!-- COMMERCIAL - OFFICE/SHOP -->
                    <div x-show="(type === 'commercial' || type === 'office') && category === 'commercial'"
                        x-transition class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Commercial Details</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Floor Level</label>
                                <input type="number" name="floor_no" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 1"
                                    value="{{ old('floor_no', $property->floor_no) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Total Building Floors</label>
                                <input type="number" name="total_floors" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 5"
                                    value="{{ old('total_floors', $property->total_floors) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Parking Spaces</label>
                                <input type="number" name="parking_spaces" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 2"
                                    value="{{ old('parking_spaces', $property->parking_spaces) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Year Built</label>
                                <input type="number" name="year_built" min="1900"
                                    :max="new Date().getFullYear()"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :placeholder="'e.g., ' + new Date().getFullYear()"
                                    value="{{ old('year_built', $property->year_built) }}">
                            </div>
                        </div>
                    </div>

                    <!-- WAREHOUSE/INDUSTRIAL -->
                    <div x-show="type === 'warehouse' && category === 'industrial'"
                        x-transition class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Industrial Details</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Clear Height (ft)</label>
                                <input type="number" name="clear_height"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 20"
                                    value="{{ old('clear_height', $property->clear_height) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Loading Docks</label>
                                <input type="number" name="loading_docks" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 2"
                                    value="{{ old('loading_docks', $property->loading_docks) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Power Supply (kVA)</label>
                                <input type="number" name="power_supply"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 100"
                                    value="{{ old('power_supply', $property->power_supply) }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Year Built</label>
                                <input type="number" name="year_built" min="1900"
                                    :max="new Date().getFullYear()"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :placeholder="'e.g., ' + new Date().getFullYear()"
                                    value="{{ old('year_built', $property->year_built) }}">
                            </div>
                        </div>
                    </div>

                    <!-- FEATURES & AMENITIES -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Features & Amenities</h2>

                        <!-- General Features -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="parking" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('parking', $property->parking) ? 'checked' : '' }}>
                                <div>
                                    <span class="block font-medium">Parking</span>
                                    <span class="text-xs text-gray-500">Available</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="water" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('water', $property->water) ? 'checked' : '' }}>
                                <div>
                                    <span class="block font-medium">Water Supply</span>
                                    <span class="text-xs text-gray-500">24/7 Available</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="electricity" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('electricity', $property->electricity) ? 'checked' : '' }}>
                                <div>
                                    <span class="block font-medium">Electricity</span>
                                    <span class="text-xs text-gray-500">Grid/Backup</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="security" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('security', $property->security) ? 'checked' : '' }}>
                                <div>
                                    <span class="block font-medium">Security</span>
                                    <span class="text-xs text-gray-500">Guarded</span>
                                </div>
                            </label>
                        </div>

                        <!-- Residential Specific -->
                        <div x-show="category === 'residential'" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="garden" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('garden', $property->garden) ? 'checked' : '' }}>
                                <span>Garden</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="balcony" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('balcony', $property->balcony) ? 'checked' : '' }}>
                                <span>Balcony</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="gym" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('gym', $property->gym) ? 'checked' : '' }}>
                                <span>Gym</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="lift" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('lift', $property->lift) ? 'checked' : '' }}>
                                <span>Lift/Elevator</span>
                            </label>
                        </div>

                        <!-- Commercial/Industrial Specific -->
                        <div x-show="category !== 'residential'" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="ac" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('ac', $property->ac) ? 'checked' : '' }}>
                                <span>Air Conditioning</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="fire_safety" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('fire_safety', $property->fire_safety) ? 'checked' : '' }}>
                                <span>Fire Safety</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="internet" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('internet', $property->internet) ? 'checked' : '' }}>
                                <span>Internet Ready</span>
                            </label>

                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="loading_area" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500"
                                    {{ old('loading_area', $property->loading_area) ? 'checked' : '' }}>
                                <span>Loading Area</span>
                            </label>
                        </div>
                    </div>

                    <!-- AVAILABILITY -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Availability</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Available From</label>
                                <input type="date" name="available_from"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('available_from', $property->available_from) }}">
                            </div>

                            <!-- <div>
                                <label class="block text-sm font-medium mb-2">Property Status</label>
                                <select name="status"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending" {{ old('status', $property->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $property->status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $property->status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div> -->
                        </div>
                    </div>

                    <!-- OWNERSHIP -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Ownership Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Ownership Type</label>
                                <select name="ownership_type"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="freehold" {{ old('ownership_type', $property->ownership_type) === 'freehold' ? 'selected' : '' }}>Freehold</option>
                                    <option value="leasehold" {{ old('ownership_type', $property->ownership_type) === 'leasehold' ? 'selected' : '' }}>Leasehold</option>
                                    <option value="cooperative" {{ old('ownership_type', $property->ownership_type) === 'cooperative' ? 'selected' : '' }}>Cooperative</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Contact Number *</label>
                                <input type="tel" name="contact_number" required
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 9841234567"
                                    value="{{ old('contact_number', $property->contact_number) }}">
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT -->
                    <div class="flex justify-end gap-4 pt-6 border-t">
                        <a href="{{ route('seller.properties.index') }}"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>

                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Update Property
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- LEAFLET CSS & JS (Free OpenSource) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- ALPINE LOGIC -->
    <script>
        function propertyForm() {
            return {
                type: '{{ old('type', $property->type) }}' || 'flat',
                purpose: '{{ old('purpose', $property->purpose) }}' || 'buy',
                category: '{{ old('category', $property->category) }}' || 'residential',
                lat: '{{ old('latitude', $property->latitude) }}' || '',
                lng: '{{ old('longitude', $property->longitude) }}' || '',
                locationAddress: '{{ old('location', $property->location) }}' || '',
                map: null,
                marker: null,
                searchMarker: null,

                initMap() {
                    const hasCoords = this.lat && this.lng;
                    const defaultLat = hasCoords ? parseFloat(this.lat) : 27.7172;
                    const defaultLng = hasCoords ? parseFloat(this.lng) : 85.3240;
                    const defaultZoom = hasCoords ? 15 : 12;

                    this.map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors',
                        maxZoom: 19
                    }).addTo(this.map);

                    this.map.on('click', (e) => {
                        this.setLocation(e.latlng.lat, e.latlng.lng);
                    });

                    if (hasCoords) {
                        this.marker = L.marker([defaultLat, defaultLng], { draggable: true })
                            .addTo(this.map);

                        this.marker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            this.setLocation(pos.lat, pos.lng);
                        });
                    }

                    this.initSearch();
                },

                initSearch() {
                    const searchInput = document.getElementById('locationSearch');
                    if (!searchInput) return;

                    let timeout = null;

                    searchInput.addEventListener('input', (e) => {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            this.searchLocation(e.target.value);
                        }, 600);
                    });
                },

                async searchLocation(query) {
                    if (!query || query.length < 3) return;

                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=np&limit=5`
                        );

                        const data = await response.json();

                        if (data.length > 0) {
                            const lat = parseFloat(data[0].lat);
                            const lon = parseFloat(data[0].lon);
                            this.setLocation(lat, lon);
                            this.locationAddress = data[0].display_name;
                            
                            // Clear previous search marker
                            if (this.searchMarker) {
                                this.map.removeLayer(this.searchMarker);
                            }
                            
                            // Add search result marker
                            this.searchMarker = L.marker([lat, lon], {
                                icon: L.icon({
                                    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                                    iconSize: [25, 41],
                                    iconAnchor: [12, 41]
                                })
                            }).addTo(this.map);
                        }

                    } catch (error) {
                        console.error('Search error:', error);
                    }
                },

                async reverseGeocode(lat, lng) {
                    try {
                        const res = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`
                        );
                        const data = await res.json();

                        if (data && data.display_name) {
                            this.locationAddress = data.display_name;
                        }

                    } catch (e) {
                        console.log('Reverse geocode failed:', e);
                    }
                },

                setLocation(lat, lng) {
                    this.lat = lat;
                    this.lng = lng;

                    if (!this.marker) {
                        this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);

                        this.marker.on('dragend', (e) => {
                            const pos = e.target.getLatLng();
                            this.setLocation(pos.lat, pos.lng);
                        });
                    } else {
                        this.marker.setLatLng([lat, lng]);
                    }

                    this.map.setView([lat, lng], 15);
                    this.reverseGeocode(lat, lng);
                }
            }
        }
    </script>
</x-app-layout>
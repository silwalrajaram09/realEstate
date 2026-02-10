<x-app-layout>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <h1 class="text-2xl font-bold mb-6">Add New Property</h1>

            <div class="bg-white shadow rounded-lg">
                <form x-data="propertyForm()" x-init="initMap()" method="POST"
                    action="{{ route('seller.properties.store') }}" enctype="multipart/form-data" class="p-6 space-y-8">
                    @csrf

                    <!-- BASIC INFO -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Basic Information</h2>

                        <div>
                            <label class="block text-sm font-medium mb-2">Property Title *</label>
                            <input type="text" name="title" required
                                class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Beautiful 3BHK Apartment in Thamel" maxlength="255">
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Description</label>
                            <textarea name="description" x-model="description" rows="4"
                                class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Describe your property in detail...">{{ old('description') }}</textarea>
                        </div>

                        <!-- PURPOSE, TYPE & CATEGORY -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Purpose *</label>
                                <select name="purpose" x-model="purpose"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="buy">For Sale</option>
                                    <option value="rent">For Rent</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Property Type *</label>
                                <select name="type" x-model="type"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="flat">Flat/Apartment</option>
                                    <option value="house">House</option>
                                    <option value="land">Land/Plot</option>
                                    <option value="commercial">Commercial Space</option>
                                    <option value="office">Office</option>
                                    <option value="warehouse">Warehouse</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Category *</label>
                                <select name="category" required
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="residential">Residential</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="industrial">Industrial</option>
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
                                    placeholder="e.g., 5000000">
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Area (sq. ft) *</label>
                                <input type="number" name="area" required min="1"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 1500">
                                @error('area')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-show="purpose === 'rent'">
                                <label class="block text-sm font-medium mb-2">Minimum Lease (months)</label>
                                <select name="min_lease_months"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="1">1 Month</option>
                                    <option value="3">3 Months</option>
                                    <option value="6" selected>6 Months</option>
                                    <option value="12">1 Year</option>
                                    <option value="24">2 Years</option>
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
                                    x-model="locationAddress" placeholder="Enter or select location">
                                @error('location')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Latitude</label>
                                <input type="text" name="latitude" readonly
                                    class="w-full border rounded-lg px-4 py-2.5 bg-gray-50" x-model="lat"
                                    placeholder="Click map to select">
                                @error('latitude')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2">Longitude</label>
                                <input type="text" name="longitude" readonly
                                    class="w-full border rounded-lg px-4 py-2.5 bg-gray-50" x-model="lng"
                                    placeholder="Click map to select">
                                @error('longitude')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- IMAGE UPLOAD -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Images</h2>

                        <div>
                            <label class="block text-sm font-medium mb-2">Main Property Image</label>
                            <input type="file" name="image" accept="image/*"
                                class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-gray-500 text-sm mt-1">Upload a main image for your property (JPG, PNG, max
                                2MB)</p>
                        </div>
                    </div>

                    <!-- RESIDENTIAL - FLAT/HOUSE -->
                    <div x-show="(type === 'flat' || type === 'house') && category === 'residential'" x-transition
                        class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Property Details</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Bedrooms</label>
                                <input type="number" name="bedrooms" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 3">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Bathrooms</label>
                                <input type="number" name="bathrooms" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 2">
                            </div>

                            <div x-show="type === 'flat'">
                                <label class="block text-sm font-medium mb-2">Floor No</label>
                                <input type="number" name="floor_no" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :placeholder="'e.g., ' + new Date().getFullYear()">
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
                                    placeholder="e.g., 20">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Facing</label>
                                <select name="facing"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="east">East</option>
                                    <option value="west">West</option>
                                    <option value="north">North</option>
                                    <option value="south">South</option>
                                    <option value="northeast">Northeast</option>
                                    <option value="northwest">Northwest</option>
                                    <option value="southeast">Southeast</option>
                                    <option value="southwest">Southwest</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Land Shape</label>
                                <select name="land_shape"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select</option>
                                    <option value="rectangle">Rectangle</option>
                                    <option value="square">Square</option>
                                    <option value="irregular">Irregular</option>
                                    <option value="triangular">Triangular</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Plot Number</label>
                                <input type="text" name="plot_number"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., Kitta 12">
                            </div>
                        </div>
                    </div>

                    <!-- COMMERCIAL - OFFICE/SHOP -->
                    <div x-show="(type === 'commercial' || type === 'office') && category === 'commercial'" x-transition
                        class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Commercial Details</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Floor Level</label>
                                <input type="number" name="commercial_floor_level" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Total Building Floors</label>
                                <input type="number" name="total_floors" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 5">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Parking Spaces</label>
                                <input type="number" name="parking_spaces" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :placeholder="'e.g., ' + new Date().getFullYear()">
                            </div>
                        </div>
                    </div>

                    <!-- WAREHOUSE/INDUSTRIAL -->
                    <div x-show="type === 'warehouse' && category === 'industrial'" x-transition class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Industrial Details</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Clear Height (ft)</label>
                                <input type="number" name="clear_height"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 20">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Loading Docks</label>
                                <input type="number" name="loading_docks" min="0"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Power Supply (kVA)</label>
                                <input type="number" name="power_supply"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :placeholder="'e.g., ' + new Date().getFullYear()">
                            </div>
                        </div>
                    </div>

                    <!-- FEATURES & AMENITIES -->
                    <div class="space-y-4">
                        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2">Features & Amenities</h2>

                        <!-- General Features -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="parking" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <div>
                                    <span class="block font-medium">Parking</span>
                                    <span class="text-xs text-gray-500">Available</span>
                                </div>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="water" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <div>
                                    <span class="block font-medium">Water Supply</span>
                                    <span class="text-xs text-gray-500">24/7 Available</span>
                                </div>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="electricity" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <div>
                                    <span class="block font-medium">Electricity</span>
                                    <span class="text-xs text-gray-500">Grid/Backup</span>
                                </div>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="security" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <div>
                                    <span class="block font-medium">Security</span>
                                    <span class="text-xs text-gray-500">Guarded</span>
                                </div>
                            </label>
                        </div>

                        <!-- Residential Specific -->
                        <div x-show="category === 'residential'" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="garden" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span>Garden</span>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="balcony" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span>Balcony</span>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="gym" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span>Gym</span>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="lift" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span>Lift/Elevator</span>
                            </label>
                        </div>

                        <!-- Commercial/Industrial Specific -->
                        <div x-show="category !== 'residential'" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="ac" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span>Air Conditioning</span>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="fire_safety" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span>Fire Safety</span>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="internet" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                <span>Internet Ready</span>
                            </label>

                            <label
                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="loading_area" value="1"
                                    class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
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
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- <div>
                                <label class="block text-sm font-medium mb-2">Property Status</label>
                                <select name="status"
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
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
                                    <option value="freehold">Freehold</option>
                                    <option value="leasehold">Leasehold</option>
                                    <option value="cooperative">Cooperative</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2">Contact Number *</label>
                                <input type="tel" name="contact_number" required
                                    class="w-full border rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="e.g., 9841234567">
                            </div>
                        </div>
                    </div>

                    <!-- SUBMIT -->
                    <div class="flex justify-end gap-4 pt-6 border-t">
                        <a href="{{ route('seller.properties.index') }}"
                            class="px-6 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</a>

                        <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            List Property
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
                type: 'flat',
                purpose: 'buy',
                category: 'residential',
                description: '',
                lat: '{{ old('latitude', '') }}',
                lng: '{{ old('longitude', '') }}',
                locationAddress: '{{ old('location', '') }}',
                map: null,
                marker: null,

                initMap() {
                    // Default to Kathmandu, Nepal
                    const defaultLat = 27.7172;
                    const defaultLng = 85.3240;

                    // Initialize map centered on Nepal
                    this.map = L.map('map').setView([defaultLat, defaultLng], 12);

                    // Add OpenStreetMap tiles (FREE!)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 19
                    }).addTo(this.map);

                    // Add click handler to map
                    this.map.on('click', (e) => {
                        this.setLocation(e.latlng.lat, e.latlng.lng);
                    });

                    // Initialize search functionality
                    this.initSearch();

                    // Check if we have existing coordinates (form validation error)
                    @if(old('latitude') && old('longitude'))
                        this.setLocation({{ old('latitude') }}, {{ old('longitude') }});
                    @endif
                },

                initSearch() {
                    const searchInput = document.getElementById('locationSearch');
                    if (!searchInput) return;

                    let timeout = null;

                    searchInput.addEventListener('input', (e) => {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            this.searchLocation(e.target.value);
                        }, 500);
                    });
                },

                async searchLocation(query) {
                    if (!query || query.length < 3) return;

                    try {
                        // Use Nominatim (OpenStreetMap's free geocoding service)
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=np&limit=1`
                        );
                        const data = await response.json();

                        if (data && data.length > 0) {
                            const result = data[0];
                            const lat = parseFloat(result.lat);
                            const lon = parseFloat(result.lon);

                            this.setLocation(lat, lon);
                            this.map.setView([lat, lon], 15);
                            this.locationAddress = result.display_name;
                        }
                    } catch (error) {
                        console.error('Search error:', error);
                    }
                },

                setLocation(lat, lng) {
                    this.lat = lat;
                    this.lng = lng;

                    // Remove existing marker
                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }

                    // Add new marker
                    this.marker = L.marker([lat, lng])
                        .addTo(this.map)
                        .bindPopup('Property Location')
                        .openPopup();

                    // Center map on marker
                    this.map.setView([lat, lng], 15);
                }
            }
        }
    </script>
</x-app-layout>
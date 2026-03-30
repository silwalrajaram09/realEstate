<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap');

        .form-root { font-family: 'DM Sans', sans-serif; background: var(--mist, #f4f0e8); min-height: 100%; }

        /* ── Page header ── */
        .form-page-header { background: var(--cream, #faf7f2); border-bottom: 1px solid #ede8df; padding: 1.75rem 0; }
        .form-eyebrow { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.35rem; }
        .form-eyebrow-line { width: 1.5rem; height: 1px; background: #b5813a; }
        .form-eyebrow-text { font-size: 0.65rem; letter-spacing: 0.14em; text-transform: uppercase; color: #b5813a; font-weight: 600; }
        .form-page-title { font-family: 'Playfair Display', serif; font-size: clamp(1.35rem, 2.5vw, 1.9rem); font-weight: 600; color: #1a1a2e; }

        /* ── Card ── */
        .form-card { background: var(--cream, #faf7f2); border: 1px solid #ede8df; border-radius: 0.75rem; overflow: hidden; }

        /* ── Section heading ── */
        .form-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 600; color: #1a1a2e;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #ede8df;
            margin-bottom: 0;
            display: flex; align-items: center; gap: 0.625rem;
        }
        .form-section-title::before { content: ''; width: 0.25rem; height: 1rem; background: #b5813a; border-radius: 2px; display: inline-block; }

        /* ── Labels / inputs ── */
        .form-label { display: block; font-size: 0.78rem; font-weight: 600; color: #4a4a5a; margin-bottom: 0.45rem; letter-spacing: 0.03em; text-transform: uppercase; }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1px solid #ddd8ce;
            border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            color: #1a1a2e;
            background: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #b5813a;
            box-shadow: 0 0 0 3px rgba(181,129,58,0.12);
        }
        .form-input-readonly { background: #f4f0e8; color: #8c8070; cursor: not-allowed; }
        .form-input-file { padding: 0.55rem 1rem; }
        .form-textarea { resize: vertical; min-height: 6rem; }
        .form-hint { font-size: 0.75rem; color: #8c8070; margin-top: 0.35rem; }
        .form-error { font-size: 0.75rem; color: #dc2626; margin-top: 0.35rem; }

        /* ── Search input with icon ── */
        .form-input-icon-wrap { position: relative; }
        .form-input-icon-wrap .form-input { padding-left: 2.5rem; }
        .form-input-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #8c8070; pointer-events: none; }

        /* ── Checkbox tiles ── */
        .check-tile {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd8ce; border-radius: 0.5rem;
            cursor: pointer; background: #fff;
            transition: border-color 0.15s, background 0.15s;
        }
        .check-tile:hover { border-color: #b5813a; background: #fdf8f2; }
        .check-tile input[type="checkbox"] {
            width: 1.1rem; height: 1.1rem;
            accent-color: #b5813a; flex-shrink: 0;
        }
        .check-tile-label { font-size: 0.875rem; font-weight: 500; color: #1a1a2e; }
        .check-tile-sub { font-size: 0.72rem; color: #8c8070; }

        /* ── Buttons ── */
        .btn-gold {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            background: #b5813a; color: #fff;
            border: none; border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif; font-size: 0.8125rem; font-weight: 600;
            letter-spacing: 0.06em; text-transform: uppercase; cursor: pointer;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-gold:hover { background: #9a6e2f; box-shadow: 0 4px 14px rgba(181,129,58,0.3); }
        .btn-ghost {
            display: inline-flex; align-items: center;
            padding: 0.65rem 1.5rem;
            background: transparent; color: #8c8070;
            border: 1px solid #ddd8ce; border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif; font-size: 0.8125rem; font-weight: 500;
            letter-spacing: 0.04em; text-decoration: none; cursor: pointer;
            transition: border-color 0.15s, color 0.15s;
        }
        .btn-ghost:hover { border-color: #b5813a; color: #b5813a; }
    </style>

    <div class="form-root">

        {{-- Page header --}}
        <div class="form-page-header">
            <div class="max-w-6xl mx-auto px-5 sm:px-8 lg:px-10">
                <div class="form-eyebrow">
                    <span class="form-eyebrow-line"></span>
                    <span class="form-eyebrow-text">Seller Portal</span>
                </div>
                <h1 class="form-page-title">Add New Property</h1>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-5 sm:px-8 lg:px-10 py-8">
            <div class="form-card reveal">
                <form x-data="propertyForm()" x-init="initMap()" method="POST"
                    action="{{ route('seller.properties.store') }}" enctype="multipart/form-data"
                    style="padding:2rem;display:flex;flex-direction:column;gap:2.25rem">
                    @csrf

                    {{-- ── BASIC INFO ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Basic Information</h2>

                        <div>
                            <label class="form-label">Property Title *</label>
                            <input type="text" name="title" required maxlength="255"
                                class="form-input"
                                placeholder="e.g. Beautiful 3BHK Apartment in Thamel">
                            @error('title')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" x-model="description" rows="4"
                                class="form-textarea"
                                placeholder="Describe your property in detail...">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="form-label">Purpose *</label>
                                <select name="purpose" x-model="purpose" class="form-select">
                                    <option value="buy">For Sale</option>
                                    <option value="rent">For Rent</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Property Type *</label>
                                <select name="type" x-model="type" class="form-select">
                                    <option value="flat">Flat / Apartment</option>
                                    <option value="house">House</option>
                                    <option value="land">Land / Plot</option>
                                    <option value="commercial">Commercial Space</option>
                                    <option value="office">Office</option>
                                    <option value="warehouse">Warehouse</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Category *</label>
                                <select name="category" required class="form-select">
                                    <option value="residential">Residential</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="industrial">Industrial</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ── PRICING & SIZE ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Pricing &amp; Size</h2>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="form-label" x-text="purpose === 'rent' ? 'Monthly Rent (NPR) *' : 'Total Price (NPR) *'"></label>
                                <input type="number" name="price" required min="1" step="0.01"
                                    class="form-input" placeholder="e.g. 5000000">
                                @error('price')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Area (sq. ft) *</label>
                                <input type="number" name="area" required min="1"
                                    class="form-input" placeholder="e.g. 1500">
                                @error('area')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div x-show="purpose === 'rent'">
                                <label class="form-label">Minimum Lease (months)</label>
                                <select name="min_lease_months" class="form-select">
                                    <option value="1">1 Month</option>
                                    <option value="3">3 Months</option>
                                    <option value="6" selected>6 Months</option>
                                    <option value="12">1 Year</option>
                                    <option value="24">2 Years</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- ── LOCATION ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Location</h2>

                        <div>
                            <label class="form-label">Search Location *</label>
                            <div class="form-input-icon-wrap">
                                <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text" id="locationSearch" class="form-input"
                                    placeholder="Search for location in Nepal (e.g. Thamel, Kathmandu)">
                            </div>
                            <p class="form-hint">Search or click on the map to set location</p>
                        </div>

                        <div id="map" style="width:100%;height:24rem;border-radius:0.5rem;border:1px solid #ddd8ce;z-index:1"></div>

                        <div class="grid grid-cols-3 gap-5">
                            <div>
                                <label class="form-label">Location / Address *</label>
                                <input type="text" name="location" required class="form-input"
                                    x-model="locationAddress" placeholder="Enter or select location">
                                @error('location')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" readonly class="form-input form-input-readonly"
                                    x-model="lat" placeholder="Click map to select">
                                @error('latitude')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" readonly class="form-input form-input-readonly"
                                    x-model="lng" placeholder="Click map to select">
                                @error('longitude')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── IMAGES ── --}}
                    <div style="display:flex;flex-direction:column;gap:1rem">
                        <h2 class="form-section-title">Images</h2>
                        <div>
                            <label class="form-label">Main Property Image</label>
                            <input type="file" name="image" accept="image/*" class="form-input form-input-file">
                            <p class="form-hint">Upload a main image for your property (JPG, PNG, max 2 MB)</p>
                        </div>
                    </div>

                    {{-- ── RESIDENTIAL DETAILS ── --}}
                    <div x-show="(type === 'flat' || type === 'house') && category === 'residential'" x-transition
                        style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Property Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div>
                                <label class="form-label">Bedrooms</label>
                                <input type="number" name="bedrooms" min="0" class="form-input" placeholder="e.g. 3">
                            </div>
                            <div>
                                <label class="form-label">Bathrooms</label>
                                <input type="number" name="bathrooms" min="0" class="form-input" placeholder="e.g. 2">
                            </div>
                            <div x-show="type === 'flat'">
                                <label class="form-label">Floor No</label>
                                <input type="number" name="floor_no" min="0" class="form-input" placeholder="e.g. 2">
                            </div>
                            <div>
                                <label class="form-label">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="form-input" :placeholder="'e.g. ' + new Date().getFullYear()">
                            </div>
                        </div>
                    </div>

                    {{-- ── LAND DETAILS ── --}}
                    <div x-show="type === 'land'" x-transition
                        style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Land Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div>
                                <label class="form-label">Road Access (ft)</label>
                                <input type="number" name="road_access" class="form-input" placeholder="e.g. 20">
                            </div>
                            <div>
                                <label class="form-label">Facing</label>
                                <select name="facing" class="form-select">
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
                                <label class="form-label">Land Shape</label>
                                <select name="land_shape" class="form-select">
                                    <option value="">Select</option>
                                    <option value="rectangle">Rectangle</option>
                                    <option value="square">Square</option>
                                    <option value="irregular">Irregular</option>
                                    <option value="triangular">Triangular</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Plot Number</label>
                                <input type="text" name="plot_number" class="form-input" placeholder="e.g. Kitta 12">
                            </div>
                        </div>
                    </div>

                    {{-- ── COMMERCIAL DETAILS ── --}}
                    <div x-show="(type === 'commercial' || type === 'office') && category === 'commercial'" x-transition
                        style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Commercial Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div>
                                <label class="form-label">Floor Level</label>
                                <input type="number" name="commercial_floor_level" min="0" class="form-input" placeholder="e.g. 1">
                            </div>
                            <div>
                                <label class="form-label">Total Floors</label>
                                <input type="number" name="total_floors" min="0" class="form-input" placeholder="e.g. 5">
                            </div>
                            <div>
                                <label class="form-label">Parking Spaces</label>
                                <input type="number" name="parking_spaces" min="0" class="form-input" placeholder="e.g. 2">
                            </div>
                            <div>
                                <label class="form-label">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="form-input" :placeholder="'e.g. ' + new Date().getFullYear()">
                            </div>
                        </div>
                    </div>

                    {{-- ── WAREHOUSE / INDUSTRIAL ── --}}
                    <div x-show="type === 'warehouse' && category === 'industrial'" x-transition
                        style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Industrial Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div>
                                <label class="form-label">Clear Height (ft)</label>
                                <input type="number" name="clear_height" class="form-input" placeholder="e.g. 20">
                            </div>
                            <div>
                                <label class="form-label">Loading Docks</label>
                                <input type="number" name="loading_docks" min="0" class="form-input" placeholder="e.g. 2">
                            </div>
                            <div>
                                <label class="form-label">Power Supply (kVA)</label>
                                <input type="number" name="power_supply" class="form-input" placeholder="e.g. 100">
                            </div>
                            <div>
                                <label class="form-label">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="form-input" :placeholder="'e.g. ' + new Date().getFullYear()">
                            </div>
                        </div>
                    </div>

                    {{-- ── FEATURES & AMENITIES ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Features &amp; Amenities</h2>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="check-tile">
                                <input type="checkbox" name="parking" value="1">
                                <div><span class="check-tile-label">Parking</span><span class="check-tile-sub" style="display:block">Available</span></div>
                            </label>
                            <label class="check-tile">
                                <input type="checkbox" name="water" value="1">
                                <div><span class="check-tile-label">Water Supply</span><span class="check-tile-sub" style="display:block">24/7 Available</span></div>
                            </label>
                            <label class="check-tile">
                                <input type="checkbox" name="electricity" value="1">
                                <div><span class="check-tile-label">Electricity</span><span class="check-tile-sub" style="display:block">Grid / Backup</span></div>
                            </label>
                            <label class="check-tile">
                                <input type="checkbox" name="security" value="1">
                                <div><span class="check-tile-label">Security</span><span class="check-tile-sub" style="display:block">Guarded</span></div>
                            </label>
                        </div>

                        {{-- Residential specific --}}
                        <div x-show="category === 'residential'" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="check-tile"><input type="checkbox" name="garden" value="1"><span class="check-tile-label">Garden</span></label>
                            <label class="check-tile"><input type="checkbox" name="balcony" value="1"><span class="check-tile-label">Balcony</span></label>
                            <label class="check-tile"><input type="checkbox" name="gym" value="1"><span class="check-tile-label">Gym</span></label>
                            <label class="check-tile"><input type="checkbox" name="lift" value="1"><span class="check-tile-label">Lift / Elevator</span></label>
                        </div>

                        {{-- Commercial/Industrial specific --}}
                        <div x-show="category !== 'residential'" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="check-tile"><input type="checkbox" name="ac" value="1"><span class="check-tile-label">Air Conditioning</span></label>
                            <label class="check-tile"><input type="checkbox" name="fire_safety" value="1"><span class="check-tile-label">Fire Safety</span></label>
                            <label class="check-tile"><input type="checkbox" name="internet" value="1"><span class="check-tile-label">Internet Ready</span></label>
                            <label class="check-tile"><input type="checkbox" name="loading_area" value="1"><span class="check-tile-label">Loading Area</span></label>
                        </div>
                    </div>

                    {{-- ── AVAILABILITY ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Availability</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Available From</label>
                                <input type="date" name="available_from" class="form-input">
                            </div>
                        </div>
                    </div>

                    {{-- ── OWNERSHIP ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Ownership Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Ownership Type</label>
                                <select name="ownership_type" class="form-select">
                                    <option value="">Select</option>
                                    <option value="freehold">Freehold</option>
                                    <option value="leasehold">Leasehold</option>
                                    <option value="cooperative">Cooperative</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Contact Number *</label>
                                <input type="tel" name="contact_number" required class="form-input" placeholder="e.g. 9841234567">
                            </div>
                        </div>
                    </div>

                    {{-- ── SUBMIT ── --}}
                    <div style="display:flex;justify-content:flex-end;gap:1rem;padding-top:1.25rem;border-top:1px solid #ede8df">
                        <a href="{{ route('seller.properties.index') }}" class="btn-ghost">Cancel</a>
                        <button type="submit" class="btn-gold">List Property</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

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
                    const defaultLat = 27.7172, defaultLng = 85.3240;
                    this.map = L.map('map').setView([defaultLat, defaultLng], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 19
                    }).addTo(this.map);
                    this.map.on('click', (e) => { this.setLocation(e.latlng.lat, e.latlng.lng); });
                    this.initSearch();
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
                        timeout = setTimeout(() => { this.searchLocation(e.target.value); }, 500);
                    });
                },

                async searchLocation(query) {
                    if (!query || query.length < 3) return;
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=np&limit=1`);
                        const data = await res.json();
                        if (data && data.length > 0) {
                            const lat = parseFloat(data[0].lat), lon = parseFloat(data[0].lon);
                            this.setLocation(lat, lon);
                            this.map.setView([lat, lon], 15);
                            this.locationAddress = data[0].display_name;
                        }
                    } catch (e) { console.error('Search error:', e); }
                },

                setLocation(lat, lng) {
                    this.lat = lat; this.lng = lng;
                    if (this.marker) this.map.removeLayer(this.marker);
                    this.marker = L.marker([lat, lng]).addTo(this.map).bindPopup('Property Location').openPopup();
                    this.map.setView([lat, lng], 15);
                }
            }
        }
    </script>
</x-app-layout>
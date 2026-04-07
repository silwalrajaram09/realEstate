<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap');

        .form-root {
            font-family: 'DM Sans', sans-serif;
            background: var(--mist, #f4f0e8);
            min-height: 100%;
        }

        /* Header */
        .form-page-header {
            background: var(--cream, #faf7f2);
            border-bottom: 1px solid #ede8df;
            padding: 1.75rem 0;
        }

        .form-eyebrow {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 0.35rem;
        }

        .form-eyebrow-line {
            width: 1.5rem;
            height: 1px;
            background: #b5813a;
        }

        .form-eyebrow-text {
            font-size: 0.65rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #b5813a;
            font-weight: 600;
        }

        .form-page-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.35rem, 2.5vw, 1.9rem);
            font-weight: 600;
            color: #1a1a2e;
        }

        /* Card & Layout */
        .form-card {
            background: var(--cream, #faf7f2);
            border: 1px solid #ede8df;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .form-body {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 2.25rem;
        }

        .form-section {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .form-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a2e;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #ede8df;
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .form-section-title::before {
            content: '';
            width: 0.25rem;
            height: 1rem;
            background: #b5813a;
            border-radius: 2px;
            display: inline-block;
        }

        /* Fields */
        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #4a4a5a;
            margin-bottom: 0.45rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1px solid #ddd8ce;
            border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            color: #1a1a2e;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #b5813a;
            box-shadow: 0 0 0 3px rgba(181, 129, 58, 0.12);
        }

        .form-input-readonly {
            background: #f4f0e8;
            color: #8c8070;
            cursor: not-allowed;
        }

        .form-input-file {
            padding: 0.55rem 1rem;
        }

        .form-textarea {
            resize: vertical;
            min-height: 6rem;
        }

        .form-hint {
            font-size: 0.75rem;
            color: #8c8070;
            margin-top: 0.35rem;
        }

        .form-error {
            font-size: 0.75rem;
            color: #dc2626;
            margin-top: 0.35rem;
        }

        /* Search with icon */
        .form-input-icon-wrap {
            position: relative;
        }

        .form-input-icon-wrap .form-input {
            padding-left: 2.5rem;
        }

        .form-input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #8c8070;
            pointer-events: none;
        }

        /* Buttons */
        .btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            background: #b5813a;
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8125rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
        }

        .btn-gold:hover {
            background: #9a6e2f;
            box-shadow: 0 4px 14px rgba(181, 129, 58, 0.3);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            padding: 0.65rem 1.5rem;
            background: transparent;
            color: #8c8070;
            border: 1px solid #ddd8ce;
            border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            text-decoration: none;
            cursor: pointer;
            transition: border-color 0.15s, color 0.15s;
        }

        .btn-ghost:hover {
            border-color: #b5813a;
            color: #b5813a;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            padding-top: 1.25rem;
            border-top: 1px solid #ede8df;
        }
    </style>

    <div class="form-root">

        {{-- Page Header --}}
        <div class="form-page-header">
            <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10">
                <div class="form-eyebrow">
                    <span class="form-eyebrow-line"></span>
                    <span class="form-eyebrow-text">Seller Portal</span>
                </div>
                <h1 class="form-page-title">Add New Property</h1>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-5 sm:px-8 lg:px-10 py-8">
            <div class="form-card">
                <form x-data="propertyForm()" x-init="initMap()" method="POST"
                    action="{{ route('seller.properties.store') }}" enctype="multipart/form-data" class="form-body">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"
                            role="alert">
                            <p class="font-semibold mb-2">Please fix the following:</p>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- 1. BASIC INFO --}}
                    <div class="form-section">
                        <h2 class="form-section-title">Basic Information</h2>

                        <div>
                            <label class="form-label">Property Title *</label>
                            <input type="text" name="title" required maxlength="255" class="form-input"
                                placeholder="e.g. Beautiful 3BHK Apartment in Thamel">
                            @error('title')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-textarea"
                                placeholder="Brief description of the property...">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="form-label">Purpose *</label>
                                <select name="purpose" x-model="purpose" class="form-select">
                                    <option value="buy">For Sale</option>
                                    <option value="rent">For Rent</option>
                                </select>
                                @error('purpose')<p class="form-error">{{ $message }}</p>@enderror
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
                                @error('type')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Category *</label>
                                <select name="category" x-model="category" required class="form-select">
                                    <option value="residential">Residential</option>
                                    <option value="commercial">Commercial</option>
                                    <option value="industrial">Industrial</option>
                                </select>
                                @error('category')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- 2. PRICING & SIZE --}}
                    <div class="form-section">
                        <h2 class="form-section-title">Pricing &amp; Size</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="form-label"
                                    x-text="purpose === 'rent' ? 'Monthly Rent (NPR) *' : 'Total Price (NPR) *'"></label>
                                <input type="number" name="price" required min="1" step="0.01" class="form-input"
                                    placeholder="e.g. 5000000">
                                @error('price')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Area (sq. ft) *</label>
                                <input type="number" name="area" required min="1" class="form-input"
                                    placeholder="e.g. 1500">
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
                                @error('min_lease_months')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-5">
                            <label class="form-label">Contact Number (buyer enquiries) *</label>
                            <input type="text" name="contact_number" required maxlength="25" class="form-input"
                                value="{{ old('contact_number', auth()->user()->phone ?? '') }}"
                                placeholder="e.g. 98XXXXXXXX or +977-98XXXXXXXX">
                            @error('contact_number')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- 3. PROPERTY DETAILS (type-specific) --}}

                    {{-- Residential: Flat / House --}}
                    <div x-show="(type === 'flat' || type === 'house') && category === 'residential'" x-transition
                        class="form-section">
                        <h2 class="form-section-title">Property Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div>
                                <label class="form-label">Bedrooms *</label>
                                <input type="number" name="bedrooms" min="0" class="form-input" placeholder="e.g. 3">
                            </div>
                            <div>
                                <label class="form-label">Bathrooms *</label>
                                <input type="number" name="bathrooms" min="0" class="form-input" placeholder="e.g. 2">
                            </div>
                            <div x-show="type === 'flat'">
                                <label class="form-label">Floor No</label>
                                <input type="number" name="floor_no" min="0" class="form-input" placeholder="e.g. 2">
                            </div>
                            <div>
                                <label class="form-label">Total Floors</label>
                                <input type="number" name="total_floors" min="1" class="form-input"
                                    placeholder="e.g. 4">
                            </div>
                            <div>
                                <label class="form-label">Facing</label>
                                <select name="facing" class="form-select">
                                    <option value="">Select</option>
                                    @foreach(['east', 'west', 'north', 'south', 'northeast', 'northwest', 'southeast', 'southwest'] as $dir)
                                        <option value="{{ $dir }}">{{ ucfirst($dir) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Parking Spaces</label>
                                <input type="number" name="parking_spaces" min="0" class="form-input"
                                    placeholder="e.g. 1">
                            </div>
                            <div>
                                <label class="form-label">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="form-input" :placeholder="'e.g. ' + new Date().getFullYear()">
                            </div>
                            <div>
                                <label class="form-label">Furnishing</label>
                                <select name="furnishing" class="form-select">
                                    <option value="">Select</option>
                                    <option value="unfurnished">Unfurnished</option>
                                    <option value="semi_furnished">Semi-Furnished</option>
                                    <option value="fully_furnished">Fully Furnished</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Land --}}
                    <div x-show="type === 'land'" x-transition class="form-section">
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
                                    @foreach(['east', 'west', 'north', 'south', 'northeast', 'northwest', 'southeast', 'southwest'] as $dir)
                                        <option value="{{ $dir }}">{{ ucfirst($dir) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Land Shape</label>
                                <select name="land_shape" class="form-select">
                                    <option value="">Select</option>
                                    @foreach(['rectangle', 'square', 'irregular', 'triangular'] as $shape)
                                        <option value="{{ $shape }}">{{ ucfirst($shape) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Plot Number</label>
                                <input type="text" name="plot_number" class="form-input" placeholder="e.g. Kitta 12">
                            </div>
                        </div>
                    </div>

                    {{-- Commercial / Office --}}
                    <div x-show="(type === 'commercial' || type === 'office') && category === 'commercial'" x-transition
                        class="form-section">
                        <h2 class="form-section-title">Commercial Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div>
                                <label class="form-label">Floor Level</label>
                                <input type="number" name="commercial_floor_level" min="0" class="form-input"
                                    placeholder="e.g. 1">
                            </div>
                            <div>
                                <label class="form-label">Total Floors</label>
                                <input type="number" name="total_floors" min="0" class="form-input"
                                    placeholder="e.g. 5">
                            </div>
                            <div>
                                <label class="form-label">Parking Spaces</label>
                                <input type="number" name="parking_spaces" min="0" class="form-input"
                                    placeholder="e.g. 2">
                            </div>
                            <div>
                                <label class="form-label">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="form-input" :placeholder="'e.g. ' + new Date().getFullYear()">
                            </div>
                        </div>
                    </div>

                    {{-- Warehouse / Industrial --}}
                    <div x-show="type === 'warehouse' && category === 'industrial'" x-transition class="form-section">
                        <h2 class="form-section-title">Industrial Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div>
                                <label class="form-label">Clear Height (ft)</label>
                                <input type="number" name="clear_height" class="form-input" placeholder="e.g. 20">
                            </div>
                            <div>
                                <label class="form-label">Loading Docks</label>
                                <input type="number" name="loading_docks" min="0" class="form-input"
                                    placeholder="e.g. 2">
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

                    {{-- 4. LOCATION --}}
                    <div class="form-section">
                        <h2 class="form-section-title">Location</h2>

                        <div>
                            <label class="form-label">Search Location *</label>
                            <div class="form-input-icon-wrap">
                                <svg class="form-input-icon w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" id="locationSearch" class="form-input"
                                    placeholder="Search for location in Nepal (e.g. Thamel, Kathmandu)">
                            </div>
                            <p class="form-hint">Search or click on the map to pin the exact location</p>
                        </div>

                        <div id="map"
                            style="width:100%;height:22rem;border-radius:0.5rem;border:1px solid #ddd8ce;z-index:1">
                        </div>

                        <div class="grid grid-cols-3 gap-5">
                            <div>
                                <label class="form-label">Address *</label>
                                <input type="text" name="location" required class="form-input" x-model="locationAddress"
                                    placeholder="Enter or select location">
                                @error('location')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" readonly class="form-input form-input-readonly"
                                    x-model="lat" placeholder="Click map to select">
                            </div>
                            <div>
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" readonly class="form-input form-input-readonly"
                                    x-model="lng" placeholder="Click map to select">
                            </div>
                        </div>
                    </div>

                    {{-- 5. IMAGES --}}
                    <div class="form-section">
                        <h2 class="form-section-title">Images</h2>
                        <div>
                            <label class="form-label">Main Property Image *</label>
                            <input type="file" name="image" accept="image/*" required class="form-input form-input-file">
                            <p class="form-hint">JPG or PNG, max 2 MB (required)</p>
                            @error('image')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div x-data="galleryUpload()">
                            <label class="form-label">Additional Images</label>
                            <input type="file" name="gallery[]" accept="image/*" multiple x-ref="galleryInput"
                                @change="onFilesChange($event)"
                                class="form-input form-input-file">
                            <input type="hidden" name="gallery_order" :value="JSON.stringify(order)">
                            <p class="form-hint">Upload up to 10 photos to showcase your property</p>
                            <div class="grid grid-cols-4 gap-3 mt-3">
                                <template x-for="(p, index) in previews" :key="p.id">
                                    <div class="relative group border rounded p-1 bg-white"
                                        draggable="true"
                                        @dragstart="dragStart(index)"
                                        @dragover.prevent
                                        @drop="dropAt(index)">
                                        <img :src="p.url" class="w-full h-20 object-cover rounded" />
                                        <div class="text-[10px] text-center mt-1 text-[#8c8070]">Drag to reorder</div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="form-footer">
                        <a href="{{ route('seller.properties.index') }}" class="btn-ghost">Cancel</a>
                        <button type="submit" class="btn-gold">List Property</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function propertyForm() {
            return {
                type: 'flat',
                purpose: 'buy',
                category: 'residential',
                lat: '{{ old("latitude", "") }}',
                lng: '{{ old("longitude", "") }}',
                locationAddress: '{{ old("location", "") }}',
                map: null,
                marker: null,

                initMap() {
                    this.map = L.map('map').setView([27.7172, 85.3240], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                        maxZoom: 19
                    }).addTo(this.map);
                    this.map.on('click', (e) => this.setLocation(e.latlng.lat, e.latlng.lng));
                    this.initSearch();
                },

                initSearch() {
                    const input = document.getElementById('locationSearch');
                    if (!input) return;
                    let timer;
                    input.addEventListener('input', (e) => {
                        clearTimeout(timer);
                        timer = setTimeout(() => this.searchLocation(e.target.value), 500);
                    });
                },

                async searchLocation(query) {
                    if (!query || query.length < 3) return;
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=np&limit=1`);
                        const [place] = await res.json();
                        if (place) {
                            const lat = parseFloat(place.lat), lon = parseFloat(place.lon);
                            this.setLocation(lat, lon);
                            this.map.setView([lat, lon], 15);
                            this.locationAddress = place.display_name;
                        }
                    } catch (e) { console.error('Search error:', e); }
                },

                setLocation(lat, lng) {
                    this.lat = lat; this.lng = lng;
                    if (this.marker) this.map.removeLayer(this.marker);
                    this.marker = L.marker([lat, lng]).addTo(this.map).bindPopup('Property Location').openPopup();
                    this.map.setView([lat, lng], 15);
                }
            };
        }

        function galleryUpload() {
            return {
                files: [],
                previews: [],
                order: [],
                dragging: null,
                onFilesChange(event) {
                    this.files = Array.from(event.target.files);
                    this.previews = this.files.map((f, i) => ({ id: i, url: URL.createObjectURL(f) }));
                    this.order = this.files.map((_, i) => i);
                },
                dragStart(index) {
                    this.dragging = index;
                },
                dropAt(index) {
                    if (this.dragging === null || this.dragging === index) return;
                    const movedPreview = this.previews.splice(this.dragging, 1)[0];
                    this.previews.splice(index, 0, movedPreview);
                    const movedOrder = this.order.splice(this.dragging, 1)[0];
                    this.order.splice(index, 0, movedOrder);
                    this.dragging = null;
                    this.rebuildFileList();
                },
                rebuildFileList() {
                    const dt = new DataTransfer();
                    this.order.forEach((fileIndex) => {
                        if (this.files[fileIndex]) dt.items.add(this.files[fileIndex]);
                    });
                    this.$refs.galleryInput.files = dt.files;
                }
            }
        }
    </script>
</x-app-layout>
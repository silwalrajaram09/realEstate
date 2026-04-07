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

        /* ── Back link ── */
        .back-link {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.78rem; font-weight: 500; color: #8c8070; text-decoration: none;
            letter-spacing: 0.04em; text-transform: uppercase; transition: color 0.15s;
        }
        .back-link:hover { color: #b5813a; }

        /* ── Card ── */
        .form-card { background: var(--cream, #faf7f2); border: 1px solid #ede8df; border-radius: 0.75rem; overflow: hidden; }

        /* ── Section heading ── */
        .form-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1rem; font-weight: 600; color: #1a1a2e;
            padding-bottom: 0.75rem; border-bottom: 1px solid #ede8df; margin-bottom: 0;
            display: flex; align-items: center; gap: 0.625rem;
        }
        .form-section-title::before { content: ''; width: 0.25rem; height: 1rem; background: #b5813a; border-radius: 2px; display: inline-block; }

        /* ── Labels / inputs ── */
        .form-label { display: block; font-size: 0.78rem; font-weight: 600; color: #4a4a5a; margin-bottom: 0.45rem; letter-spacing: 0.03em; text-transform: uppercase; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 0.65rem 1rem;
            border: 1px solid #ddd8ce; border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: #1a1a2e; background: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease; outline: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #b5813a; box-shadow: 0 0 0 3px rgba(181,129,58,0.12);
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
            padding: 0.75rem 1rem; border: 1px solid #ddd8ce; border-radius: 0.5rem;
            cursor: pointer; background: #fff;
            transition: border-color 0.15s, background 0.15s;
        }
        .check-tile:hover { border-color: #b5813a; background: #fdf8f2; }
        .check-tile input[type="checkbox"] { width: 1.1rem; height: 1.1rem; accent-color: #b5813a; flex-shrink: 0; }
        .check-tile-label { font-size: 0.875rem; font-weight: 500; color: #1a1a2e; }
        .check-tile-sub { font-size: 0.72rem; color: #8c8070; }

        /* ── Current image preview ── */
        .img-preview { width: 12rem; height: 8rem; object-fit: cover; border-radius: 0.5rem; border: 1px solid #ddd8ce; }

        /* ── Buttons ── */
        .btn-gold {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.65rem 1.5rem; background: #b5813a; color: #fff;
            border: none; border-radius: 0.5rem;
            font-family: 'DM Sans', sans-serif; font-size: 0.8125rem; font-weight: 600;
            letter-spacing: 0.06em; text-transform: uppercase; cursor: pointer;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-gold:hover { background: #9a6e2f; box-shadow: 0 4px 14px rgba(181,129,58,0.3); }
        .btn-ghost {
            display: inline-flex; align-items: center;
            padding: 0.65rem 1.5rem; background: transparent; color: #8c8070;
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
                <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:1rem">
                    <div>
                        <div class="form-eyebrow">
                            <span class="form-eyebrow-line"></span>
                            <span class="form-eyebrow-text">Seller Portal</span>
                        </div>
                        <h1 class="form-page-title">Edit Property</h1>
                    </div>
                    <a href="{{ route('seller.properties.index') }}" class="back-link">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-5 sm:px-8 lg:px-10 py-8">
            <div class="form-card reveal">
                <form x-data="propertyForm()" x-init="initMap()" method="POST"
                    action="{{ route('seller.properties.update', $property->id) }}" enctype="multipart/form-data"
                    style="padding:2rem;display:flex;flex-direction:column;gap:2.25rem">
                    @csrf
                    @method('PUT')

                    {{-- ── BASIC INFO ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Basic Information</h2>

                        <div>
                            <label class="form-label">Property Title *</label>
                            <input type="text" name="title" required maxlength="255"
                                class="form-input"
                                placeholder="e.g. Beautiful 3BHK Apartment in Thamel"
                                value="{{ old('title', $property->title) }}">
                            @error('title')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-textarea"
                                placeholder="Describe your property in detail...">{{ old('description', $property->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="form-label">Purpose *</label>
                                <select name="purpose" x-model="purpose" class="form-select">
                                    <option value="buy" {{ old('purpose', $property->purpose) === 'buy' ? 'selected' : '' }}>For Sale</option>
                                    <option value="rent" {{ old('purpose', $property->purpose) === 'rent' ? 'selected' : '' }}>For Rent</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Property Type *</label>
                                <select name="type" x-model="type" class="form-select">
                                    <option value="flat" {{ old('type', $property->type) === 'flat' ? 'selected' : '' }}>Flat / Apartment</option>
                                    <option value="house" {{ old('type', $property->type) === 'house' ? 'selected' : '' }}>House</option>
                                    <option value="land" {{ old('type', $property->type) === 'land' ? 'selected' : '' }}>Land / Plot</option>
                                    <option value="commercial" {{ old('type', $property->type) === 'commercial' ? 'selected' : '' }}>Commercial Space</option>
                                    <option value="office" {{ old('type', $property->type) === 'office' ? 'selected' : '' }}>Office</option>
                                    <option value="warehouse" {{ old('type', $property->type) === 'warehouse' ? 'selected' : '' }}>Warehouse</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Category *</label>
                                <select name="category" required x-model="category" class="form-select">
                                    <option value="residential" {{ old('category', $property->category) === 'residential' ? 'selected' : '' }}>Residential</option>
                                    <option value="commercial" {{ old('category', $property->category) === 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="industrial" {{ old('category', $property->category) === 'industrial' ? 'selected' : '' }}>Industrial</option>
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
                                    class="form-input" placeholder="e.g. 5000000"
                                    value="{{ old('price', $property->price) }}">
                                @error('price')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Area (sq. ft) *</label>
                                <input type="number" name="area" required min="1"
                                    class="form-input" placeholder="e.g. 1500"
                                    value="{{ old('area', $property->area) }}">
                                @error('area')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div x-show="purpose === 'rent'">
                                <label class="form-label">Minimum Lease (months)</label>
                                <select name="min_lease_months" class="form-select">
                                    <option value="1" {{ old('min_lease_months', $property->min_lease_months) == '1' ? 'selected' : '' }}>1 Month</option>
                                    <option value="3" {{ old('min_lease_months', $property->min_lease_months) == '3' ? 'selected' : '' }}>3 Months</option>
                                    <option value="6" {{ old('min_lease_months', $property->min_lease_months) == '6' ? 'selected' : '' }}>6 Months</option>
                                    <option value="12" {{ old('min_lease_months', $property->min_lease_months) == '12' ? 'selected' : '' }}>1 Year</option>
                                    <option value="24" {{ old('min_lease_months', $property->min_lease_months) == '24' ? 'selected' : '' }}>2 Years</option>
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
                                    x-model="locationAddress" placeholder="Enter or select location"
                                    value="{{ old('location', $property->location) }}">
                                @error('location')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="form-label">Latitude</label>
                                <input type="number" name="latitude" step="any" readonly class="form-input form-input-readonly"
                                    x-model="lat" placeholder="Click map to select"
                                    value="{{ old('latitude', $property->latitude) }}">
                            </div>
                            <div>
                                <label class="form-label">Longitude</label>
                                <input type="number" name="longitude" step="any" readonly class="form-input form-input-readonly"
                                    x-model="lng" placeholder="Click map to select"
                                    value="{{ old('longitude', $property->longitude) }}">
                            </div>
                        </div>
                    </div>

                    {{-- ── IMAGES ── --}}
                    <div style="display:flex;flex-direction:column;gap:1rem">
                        <h2 class="form-section-title">Images</h2>

                        @if($property->image)
                            <div>
                                <label class="form-label">Current Image</label>
                                <img src="{{ asset('images/' . $property->image) }}" alt="Current Property Image" class="img-preview">
                            </div>
                        @endif

                        <div>
                            <label class="form-label">{{ $property->image ? 'Change Image' : 'Main Property Image' }}</label>
                            <input type="file" name="image" accept="image/*" class="form-input form-input-file">
                            <p class="form-hint">Upload a new image (JPG, PNG, max 2 MB)</p>
                        </div>
                        <div x-data="existingGalleryOrder(@js($property->gallery ?? []))">
                            <label class="form-label">Reorder Existing Gallery</label>
                            <input type="hidden" name="gallery_order_existing" :value="JSON.stringify(gallery)">
                            <div class="grid grid-cols-4 gap-3">
                                <template x-for="(url, idx) in gallery" :key="url + idx">
                                    <div class="border rounded p-1 bg-white" draggable="true"
                                        @dragstart="dragStart(idx)" @dragover.prevent @drop="dropAt(idx)">
                                        <img :src="url" class="w-full h-20 object-cover rounded" />
                                        <div class="text-[10px] text-center mt-1 text-[#8c8070]">Drag to reorder</div>
                                    </div>
                                </template>
                            </div>
                            <label class="form-label mt-4">Add New Gallery Images</label>
                            <input type="file" name="gallery[]" accept="image/*" multiple class="form-input form-input-file">
                        </div>
                    </div>

                    {{-- ── RESIDENTIAL DETAILS ── --}}
                    <div x-show="(type === 'flat' || type === 'house') && category === 'residential'" x-transition
                        style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Property Details</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                            <div>
                                <label class="form-label">Bedrooms</label>
                                <input type="number" name="bedrooms" min="0" class="form-input" placeholder="e.g. 3"
                                    value="{{ old('bedrooms', $property->bedrooms) }}">
                            </div>
                            <div>
                                <label class="form-label">Bathrooms</label>
                                <input type="number" name="bathrooms" min="0" class="form-input" placeholder="e.g. 2"
                                    value="{{ old('bathrooms', $property->bathrooms) }}">
                            </div>
                            <div x-show="type === 'flat'">
                                <label class="form-label">Floor No</label>
                                <input type="number" name="floor_no" min="0" class="form-input" placeholder="e.g. 2"
                                    value="{{ old('floor_no', $property->floor_no) }}">
                            </div>
                            <div>
                                <label class="form-label">Year Built</label>
                                <input type="number" name="year_built" min="1900" :max="new Date().getFullYear()"
                                    class="form-input" :placeholder="'e.g. ' + new Date().getFullYear()"
                                    value="{{ old('year_built', $property->year_built) }}">
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
                                <input type="number" name="road_access" class="form-input" placeholder="e.g. 20"
                                    value="{{ old('road_access', $property->road_access) }}">
                            </div>
                            <div>
                                <label class="form-label">Facing</label>
                                <select name="facing" class="form-select">
                                    <option value="">Select</option>
                                    @foreach(['east','west','north','south','northeast','northwest','southeast','southwest'] as $dir)
                                        <option value="{{ $dir }}" {{ old('facing', $property->facing) === $dir ? 'selected' : '' }}>{{ ucfirst($dir) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Land Shape</label>
                                <select name="land_shape" class="form-select">
                                    <option value="">Select</option>
                                    @foreach(['rectangle','square','irregular','triangular'] as $shape)
                                        <option value="{{ $shape }}" {{ old('land_shape', $property->land_shape) === $shape ? 'selected' : '' }}>{{ ucfirst($shape) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Plot Number</label>
                                <input type="text" name="plot_number" class="form-input" placeholder="e.g. Kitta 12"
                                    value="{{ old('plot_number', $property->plot_number) }}">
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
                                <input type="checkbox" name="parking" value="1" {{ old('parking', $property->parking) ? 'checked' : '' }}>
                                <div><span class="check-tile-label">Parking</span><span class="check-tile-sub" style="display:block">Available</span></div>
                            </label>
                            <label class="check-tile">
                                <input type="checkbox" name="water" value="1" {{ old('water', $property->water) ? 'checked' : '' }}>
                                <div><span class="check-tile-label">Water Supply</span><span class="check-tile-sub" style="display:block">24/7 Available</span></div>
                            </label>
                            <label class="check-tile">
                                <input type="checkbox" name="electricity" value="1" {{ old('electricity', $property->electricity) ? 'checked' : '' }}>
                                <div><span class="check-tile-label">Electricity</span><span class="check-tile-sub" style="display:block">Grid / Backup</span></div>
                            </label>
                            <label class="check-tile">
                                <input type="checkbox" name="security" value="1" {{ old('security', $property->security) ? 'checked' : '' }}>
                                <div><span class="check-tile-label">Security</span><span class="check-tile-sub" style="display:block">Guarded</span></div>
                            </label>
                        </div>

                        {{-- Residential specific --}}
                        <div x-show="category === 'residential'" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="check-tile"><input type="checkbox" name="garden" value="1" {{ old('garden', $property->garden) ? 'checked' : '' }}><span class="check-tile-label">Garden</span></label>
                            <label class="check-tile"><input type="checkbox" name="balcony" value="1" {{ old('balcony', $property->balcony) ? 'checked' : '' }}><span class="check-tile-label">Balcony</span></label>
                            <label class="check-tile"><input type="checkbox" name="gym" value="1" {{ old('gym', $property->gym) ? 'checked' : '' }}><span class="check-tile-label">Gym</span></label>
                            <label class="check-tile"><input type="checkbox" name="lift" value="1" {{ old('lift', $property->lift) ? 'checked' : '' }}><span class="check-tile-label">Lift / Elevator</span></label>
                        </div>

                        {{-- Commercial / Industrial specific --}}
                        <div x-show="category !== 'residential'" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="check-tile"><input type="checkbox" name="ac" value="1" {{ old('ac', $property->ac) ? 'checked' : '' }}><span class="check-tile-label">Air Conditioning</span></label>
                            <label class="check-tile"><input type="checkbox" name="fire_safety" value="1" {{ old('fire_safety', $property->fire_safety) ? 'checked' : '' }}><span class="check-tile-label">Fire Safety</span></label>
                            <label class="check-tile"><input type="checkbox" name="internet" value="1" {{ old('internet', $property->internet) ? 'checked' : '' }}><span class="check-tile-label">Internet Ready</span></label>
                            <label class="check-tile"><input type="checkbox" name="loading_area" value="1" {{ old('loading_area', $property->loading_area) ? 'checked' : '' }}><span class="check-tile-label">Loading Area</span></label>
                        </div>
                    </div>

                    {{-- ── AVAILABILITY ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem">
                        <h2 class="form-section-title">Availability</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label">Available From</label>
                                <input type="date" name="available_from" class="form-input"
                                    value="{{ old('available_from', $property->available_from) }}">
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
                                    <option value="freehold" {{ old('ownership_type', $property->ownership_type) === 'freehold' ? 'selected' : '' }}>Freehold</option>
                                    <option value="leasehold" {{ old('ownership_type', $property->ownership_type) === 'leasehold' ? 'selected' : '' }}>Leasehold</option>
                                    <option value="cooperative" {{ old('ownership_type', $property->ownership_type) === 'cooperative' ? 'selected' : '' }}>Cooperative</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Contact Number *</label>
                                <input type="tel" name="contact_number" required class="form-input"
                                    placeholder="e.g. 9841234567"
                                    value="{{ old('contact_number', $property->contact_number) }}">
                            </div>
                        </div>
                    </div>

                    {{-- ── SUBMIT ── --}}
                    <div style="display:flex;justify-content:flex-end;gap:1rem;padding-top:1.25rem;border-top:1px solid #ede8df">
                        <a href="{{ route('seller.properties.index') }}" class="btn-ghost">Cancel</a>
                        <button type="submit" class="btn-gold">Update Property</button>
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
                        attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
                    }).addTo(this.map);

                    this.map.on('click', (e) => { this.setLocation(e.latlng.lat, e.latlng.lng); });

                    if (hasCoords) {
                        this.marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(this.map);
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
                        timeout = setTimeout(() => { this.searchLocation(e.target.value); }, 600);
                    });
                },

                async searchLocation(query) {
                    if (!query || query.length < 3) return;
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=np&limit=5`);
                        const data = await res.json();
                        if (data.length > 0) {
                            const lat = parseFloat(data[0].lat), lon = parseFloat(data[0].lon);
                            this.setLocation(lat, lon);
                            this.locationAddress = data[0].display_name;
                            if (this.searchMarker) this.map.removeLayer(this.searchMarker);
                            this.searchMarker = L.marker([lat, lon], {
                                icon: L.icon({ iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png', iconSize: [25, 41], iconAnchor: [12, 41] })
                            }).addTo(this.map);
                        }
                    } catch (e) { console.error('Search error:', e); }
                },

                async reverseGeocode(lat, lng) {
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`);
                        const data = await res.json();
                        if (data && data.display_name) this.locationAddress = data.display_name;
                    } catch (e) {}
                },

                setLocation(lat, lng) {
                    this.lat = lat; this.lng = lng;
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
        function existingGalleryOrder(initialGallery) {
            return {
                gallery: initialGallery || [],
                dragging: null,
                dragStart(index) {
                    this.dragging = index;
                },
                dropAt(index) {
                    if (this.dragging === null || this.dragging === index) return;
                    const moved = this.gallery.splice(this.dragging, 1)[0];
                    this.gallery.splice(index, 0, moved);
                    this.dragging = null;
                }
            };
        }
    </script>
</x-app-layout>
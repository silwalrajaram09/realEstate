<div>
    {{-- ── HEADER ── --}}
    <div class="sugg-header">
        <div class="sugg-header-eyebrow">
            <div class="sugg-header-eyebrow-line"></div>
            <span>{{ $strategyLabel }}</span>
        </div>
        <h1>Your <em>Suggestions</em></h1>
        <p>Properties matched to your preferences using cosine similarity — the closer the match, the higher it ranks.</p>

        @if($properties->count() > 0)
            <div class="sugg-header-meta">
                <div class="sugg-header-count">{{ $properties->count() }}</div>
                <div class="sugg-header-count-label">matched listings</div>
            </div>
        @endif
    </div>

    {{-- ── TOOLBAR ── --}}
    <div class="sugg-toolbar">
        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap; flex:1;">
            <div class="cosine-badge">
                <div class="cosine-dot"></div>
                Hybrid Intelligence Ranked
            </div>
            
            {{-- Non-refresh Search for suggestions --}}
            <div style="position:relative; width: 300px;">
                <input type="text" wire:model.live.debounce.400ms="query" 
                       placeholder="Filter suggestions by text..." 
                       style="width: 100%; font-size: 0.8rem; border-radius: 4px; border: 1px solid #ddd5c8; padding: 0.45rem 1rem 0.45rem 2.25rem; font-family: 'Outfit', sans-serif;">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#8c8070" stroke-width="2"
                     style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        
        <div style="display:flex; align-items:center; gap:0.75rem;">
            <a href="{{ route('buyer.properties') }}" class="browse-all-btn">
                Browse all listings
            </a>
        </div>
    </div>

    {{-- Loading state overlay --}}
    <div wire:loading.flex style="position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background:rgba(255,255,255,0.8); z-index:100; padding:1.5rem; border-radius:8px; align-items:center; gap:0.75rem; box-shadow:0 10px 30px rgba(0,0,0,0.1); border:1px solid #ede8df;">
         <svg class="animate-spin h-5 w-5 text-gold" style="color:#c9a96e;" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
         <span style="font-size:0.8rem; font-weight:600; color:#0f0f0f; text-transform:uppercase; letter-spacing:0.1em;">Updating Suggestions...</span>
    </div>

    {{-- ── GRID ── --}}
    <div wire:loading.class="opacity-40" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 transition-opacity duration-300">

        @forelse($properties as $i => $property)
            <div class="prop-card" style="animation-delay: {{ $i * 0.05 }}s">
                <div class="prop-img">
                    <img src="{{ $property->image_url }}" alt="{{ $property->title }}">
                    <span class="prop-badge prop-badge-purpose">{{ ucfirst($property->purpose) }}</span>
                    <span class="prop-badge-type">{{ ucfirst($property->type) }}</span>
                </div>
                <div class="prop-body">
                    <div class="prop-title">{{ $property->title }}</div>
                    <div class="prop-loc">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $property->location }}
                    </div>

                    <div class="prop-specs">
                        @if($property->bedrooms)<div class="prop-spec">{{ $property->bedrooms }} bed</div>@endif
                        @if($property->bathrooms)<div class="prop-spec">{{ $property->bathrooms }} bath</div>@endif
                        @if($property->area)<div class="prop-spec">{{ number_format($property->area) }} sq.ft</div>@endif
                    </div>

                    <div class="prop-footer">
                        <div class="prop-price">
                             Rs {{ number_format($property->price) }}
                             @if($property->category)<span>{{ ucfirst($property->category) }}</span>@endif
                        </div>
                        <a href="{{ route('buyer.properties.show', $property->id) }}" class="prop-btn">View</a>
                    </div>

                    {{-- HYBRID RECOMMENDATION REASONS --}}
                    <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; margin-top: 0.75rem;">
                        @if(isset($property->hybrid_score))
                            <div class="prop-match-pill" style="background:#fdf6ec; color:#9a7340; border-color:#f0e0c0;">⭐ {{ round($property->hybrid_score * 100) }}% Match</div>
                        @endif
                        @if(isset($property->hybrid_content_score) && $property->hybrid_content_score > 0.3)
                            <div class="prop-match-pill" style="background:#e2e8f0; color:#4a5568; border-color:#cbd5e1;">🎯 Content</div>
                        @endif
                        @if(isset($property->hybrid_collab_score) && $property->hybrid_collab_score > 0.3)
                            <div class="prop-match-pill" style="background:#ebf8ff; color:#2b6cb0; border-color:#bee3f8;">👥 Community</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-title">No Suggestions Match Your Filter</div>
                <p class="empty-sub">Try searching for something broader or clearing your text filter.</p>
                <button wire:click="$set('query', '')" class="empty-cta">Clear Search Filter</button>
            </div>
        @endforelse
    </div>
</div>

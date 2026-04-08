@php
    $strongRecommendations = collect($recommendations ?? [])->filter(function ($p) {
        return ($p->recommendation_confidence ?? 'medium') !== 'low';
    })->values();
    $heading = $sectionTitle ?? 'Similar Properties';
@endphp

@if($strongRecommendations->count())
    <div class="mt-10">
        <div class="flex items-end justify-between mb-5">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-[#b5813a] font-semibold">
                    {{ $heading === 'Similar Properties' ? 'Cosine Ranked' : 'Recommended for you' }}
                </p>
                <h3 class="text-2xl font-semibold text-[#1a1a2e]">{{ $heading }}</h3>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($strongRecommendations as $property)
                <x-property-card :property="$property" :showScore="true" />
            @endforeach
        </div>
    </div>
@endif

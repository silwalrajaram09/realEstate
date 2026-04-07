@php
    $strongRecommendations = collect($recommendations ?? [])->filter(function ($p) {
        return ($p->recommendation_confidence ?? 'medium') !== 'low';
    })->values();
@endphp

@if($strongRecommendations->count())
    <div class="mt-10">
        <div class="flex items-end justify-between mb-5">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-[#b5813a] font-semibold">Cosine Ranked</p>
                <h3 class="text-2xl font-semibold text-[#1a1a2e]">Similar Properties</h3>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($strongRecommendations as $property)
                <x-property-card :property="$property" :showScore="true" />
            @endforeach
        </div>
    </div>
@elseif(isset($recommendations))
    <div class="mt-10 rounded border border-[#ede8df] bg-white p-4 text-sm text-[#8c8070]">
        No strong similar properties found for this listing yet.
    </div>
@endif

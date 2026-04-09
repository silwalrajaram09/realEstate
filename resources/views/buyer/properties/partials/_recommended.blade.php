@if(isset($recommendations) && $recommendations->count())
    <div class="mt-10">
        <div class="flex items-end justify-between mb-5">
            <div>
                <p class="text-xs uppercase tracking-[0.18em] text-[#b5813a] font-semibold">Cosine Ranked</p>
                <h3 class="text-2xl font-semibold text-[#1a1a2e]">Similar Properties</h3>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recommendations as $property)
                <x-property-card :property="$property" :showScore="true" />
            @endforeach
        </div>
    </div>
@endif

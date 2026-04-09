<x-app-layout>

<link rel="stylesheet" href="{{ asset('css/suggestion.css') }}">

<div class="sugg-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">

    {{-- ── LIVEWIRE SUGGESTIONS ── --}}
    <livewire:user-suggestions />
</div>

    {{--
        PAGINATION REMOVED:
        personalized($preferences, $limit) returns a plain Collection, not a
        LengthAwarePaginator. The limit is 12, so there is nothing to paginate.
        If you later switch the service to return a Paginator, add it back.
    --}}

</div>

{{--
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ADD TO suggestion.css
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

.prop-match-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    margin-top: 0.65rem;
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    color: #9a7340;
    background: #fdf6ec;
    border: 1px solid #f0e0c0;
    border-radius: 20px;
    padding: 0.25rem 0.6rem;
}
--}}

</x-app-layout>
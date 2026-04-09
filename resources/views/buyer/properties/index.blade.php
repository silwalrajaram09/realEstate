<x-app-layout>

<style>
    .browse-root { font-family: 'Outfit', sans-serif; }

    /* Sidebar */
    .filter-sidebar {
        background: #fff;
        border: 1px solid #ede8df;
        border-radius: 4px;
        padding: 1.75rem;
        position: sticky;
        top: 5.5rem;
    }
    .filter-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 600;
        color: #0f0f0f;
        margin-bottom: 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
    }
    .filter-reset {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #c9a96e;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .filter-reset:hover { color: #9a7340; }

    .filter-group { margin-bottom: 1.375rem; }
    .filter-label {
        font-size: 0.65rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #8c8070;
        font-weight: 600;
        display: block;
        margin-bottom: 0.625rem;
    }
    .filter-input, .filter-select {
        font-family: 'Outfit', sans-serif;
        font-size: 0.8125rem;
        font-weight: 400;
        color: #0f0f0f;
        background: #faf7f2;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        padding: 0.625rem 0.875rem;
        width: 100%;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #c9a96e;
        box-shadow: 0 0 0 3px rgba(201,169,110,0.1);
        background: #fff;
    }
    .filter-select {
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 2rem;
        cursor: pointer;
    }

    /* Radio pill group */
    .pill-group { display: flex; flex-wrap: wrap; gap: 0.375rem; }
    .pill-label { cursor: pointer; }
    .pill-label input { position: absolute; opacity: 0; pointer-events: none; }
    .pill-span {
        display: block;
        padding: 0.3rem 0.75rem;
        font-size: 0.72rem;
        font-weight: 500;
        color: #4a4038;
        border: 1px solid #ddd5c8;
        border-radius: 2px;
        background: #faf7f2;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .pill-label input:checked + .pill-span, .pill-span.active {
        background: #c9a96e;
        border-color: #c9a96e;
        color: #0f0f0f;
        font-weight: 600;
    }
    .pill-span:hover { border-color: #c9a96e; }

    /* Checkbox */
    .check-item { display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.25rem 0; }
    .check-item input[type="checkbox"] {
        width: 14px; height: 14px;
        border: 1.5px solid #ddd5c8;
        border-radius: 2px;
        accent-color: #c9a96e;
        cursor: pointer;
    }
    .check-item-label { font-size: 0.8125rem; color: #4a4038; font-weight: 400; }

    .filter-sep { height: 1px; background: #f0ece4; margin: 1.375rem 0; }

    .filter-submit {
        width: 100%;
        padding: 0.75rem;
        background: #c9a96e;
        color: #0f0f0f;
        font-family: 'Outfit', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .filter-submit:hover { background: #b5924f; }

    /* Active filter chip */
    .filter-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        padding: 0.25rem 0.625rem;
        background: rgba(201,169,110,0.1);
        border: 1px solid rgba(201,169,110,0.3);
        border-radius: 2px;
        font-size: 0.72rem;
        font-weight: 500;
        color: #6b5e52;
    }
    .filter-chip a { color: #9a7340; text-decoration: none; line-height: 1; font-size: 0.9rem; }
    .filter-chip a:hover { color: #c0392b; }

    /* Quick price links */
    .qp-link {
        display: inline-block;
        padding: 0.3rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 500;
        border: 1px solid #ddd5c8;
        border-radius: 2px;
        color: #4a4038;
        text-decoration: none;
        background: #faf7f2;
        transition: all 0.2s ease;
    }
    .qp-link:hover, .qp-link.active {
        background: rgba(201,169,110,0.12);
        border-color: #c9a96e;
        color: #9a7340;
    }

    /* Results header */
    .results-header {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    .results-count {
        font-size: 0.8rem; font-weight: 300; color: #8c8070;
    }
    .sort-dropdown {
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem; font-weight: 500; color: #3a3028;
        background: #fff;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        padding: 0.45rem 2rem 0.45rem 0.75rem;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.625rem center;
        cursor: pointer;
    }
    .sort-dropdown:focus { outline: none; border-color: #c9a96e; }

    .per-page-select {
        font-family: 'Outfit', sans-serif;
        font-size: 0.78rem; font-weight: 500; color: #3a3028;
        background: #fff;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        padding: 0.45rem 2rem 0.45rem 0.75rem;
        appearance: none; -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.625rem center;
        cursor: pointer;
    }
    .per-page-select:focus { outline: none; border-color: #c9a96e; }

    /* Location button */
    .loc-btn {
        width: 100%;
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.625rem 0.875rem;
        background: #faf7f2;
        border: 1px solid #ddd5c8;
        border-radius: 3px;
        font-family: 'Outfit', sans-serif;
        font-size: 0.8rem; font-weight: 500; color: #4a4038;
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease;
    }
    .loc-btn:hover { border-color: #c9a96e; background: #fff; }
</style>

<div class="browse-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:0.375rem;">
                <div style="width:1.5rem; height:1px; background:#c9a96e;"></div>
                <span style="font-size:0.65rem; letter-spacing:0.14em; text-transform:uppercase; color:#c9a96e; font-weight:600;">Browse</span>
            </div>
            <h1 style="font-family:'Cormorant Garamond',serif; font-size:clamp(1.75rem,3.5vw,2.5rem); font-weight:600; color:#0f0f0f; line-height:1.1;">
                All <em style="color:#c9a96e; font-style:italic;">Properties</em>
            </h1>
        </div>
    </div>

    {{-- ── LIVEWIRE COMPONENT ── --}}
    <livewire:property-listing />
</div>

@push('scripts')
<script>
    // QS helpers removed since Livewire handles state now
</script>
@endpush

</x-app-layout>
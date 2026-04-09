<x-app-layout>

<style>
    .browse-root { font-family: 'Outfit', sans-serif; }
    .filter-sidebar { background:#fff; border:1px solid #ede8df; border-radius:4px; padding:1.75rem; position:sticky; top:5.5rem; }
    .filter-title { font-family:'Cormorant Garamond',serif; font-size:1.25rem; font-weight:600; color:#0f0f0f; margin-bottom:1.5rem; display:flex; align-items:center; justify-content:space-between; }
    .filter-reset { font-size:0.7rem; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:#c9a96e; text-decoration:none; }
    .filter-reset:hover { color:#9a7340; }
    .filter-group { margin-bottom:1.375rem; }
    .filter-label { font-size:0.65rem; letter-spacing:0.12em; text-transform:uppercase; color:#8c8070; font-weight:600; display:block; margin-bottom:0.625rem; }
    .filter-input, .filter-select { font-family:'Outfit',sans-serif; font-size:0.8125rem; color:#0f0f0f; background:#faf7f2; border:1px solid #ddd5c8; border-radius:3px; padding:0.625rem 0.875rem; width:100%; transition:border-color 0.2s ease; }
    .filter-input:focus, .filter-select:focus { outline:none; border-color:#c9a96e; box-shadow:0 0 0 3px rgba(201,169,110,0.1); background:#fff; }
    .filter-select { appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; padding-right:2rem; cursor:pointer; }
    .filter-sep { height:1px; background:#f0ece4; margin:1.375rem 0; }
    .filter-submit { width:100%; padding:0.75rem; background:#c9a96e; color:#0f0f0f; font-family:'Outfit',sans-serif; font-size:0.75rem; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; border:none; border-radius:3px; cursor:pointer; transition:background 0.2s ease; }
    .filter-submit:hover { background:#b5924f; }
    .pill-group { display: flex; flex-wrap: wrap; gap: 0.375rem; }
    .pill-label { cursor: pointer; }
    .pill-label input { position: absolute; opacity: 0; pointer-events: none; }
    .pill-span { display: block; padding: 0.3rem 0.75rem; font-size: 0.72rem; font-weight: 500; color: #4a4038; border: 1px solid #ddd5c8; border-radius: 2px; background: #faf7f2; transition: all 0.2s ease; cursor: pointer; }
    .pill-label input:checked + .pill-span { background: #c9a96e; border-color: #c9a96e; color: #0f0f0f; font-weight: 600; }
    .qp-link { display: inline-block; padding: 0.3rem 0.75rem; font-size: 0.7rem; font-weight: 500; border: 1px solid #ddd5c8; border-radius: 2px; color: #4a4038; text-decoration: none; background: #faf7f2; transition: all 0.2s ease; }
    .qp-link:hover, .qp-link.active { background: rgba(201,169,110,0.12); border-color: #c9a96e; color: #9a7340; }
    .results-header { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem; }
    .results-count { font-size: 0.8rem; font-weight: 300; color: #8c8070; }
    .sort-dropdown, .per-page-select { font-family:'Outfit',sans-serif; font-size:0.78rem; font-weight:500; color:#3a3028; background:#fff; border:1px solid #ddd5c8; border-radius:3px; padding:0.45rem 2rem 0.45rem 0.75rem; appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath fill='none' stroke='%239a8878' stroke-width='1.5' stroke-linecap='round' d='M1 1l4 4 4-4'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.625rem center; cursor:pointer; }
    .loc-btn { width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.625rem 0.875rem; background: #faf7f2; border: 1px solid #ddd5c8; border-radius: 3px; font-family: 'Outfit', sans-serif; font-size: 0.8rem; font-weight: 500; color: #4a4038; cursor: pointer; transition: border-color 0.2s ease, background 0.2s ease; }
</style>

<div class="browse-root max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-10">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:0.375rem;">
                <div style="width:1.5rem; height:1px; background:#c9a96e;"></div>
                <span style="font-size:0.65rem; letter-spacing:0.14em; text-transform:uppercase; color:#c9a96e; font-weight:600;">For Sale</span>
            </div>
            <h1 style="font-family:'Cormorant Garamond',serif; font-size:clamp(1.75rem,3.5vw,2.5rem); font-weight:600; color:#0f0f0f; line-height:1.1;">
                Properties <em style="color:#c9a96e; font-style:italic;">For Sale</em>
            </h1>
        </div>
    </div>

    {{-- Livewire component locked to 'buy' purpose --}}
    <livewire:property-listing :purpose="'buy'" />
</div>

</x-app-layout>
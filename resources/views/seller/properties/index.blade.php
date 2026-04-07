<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap');

        .idx-root { font-family: 'DM Sans', sans-serif; background: var(--mist, #f4f0e8); min-height: 100%; }

        /* ── Page header ── */
        .idx-page-header {
            background: var(--cream, #faf7f2);
            border-bottom: 1px solid #ede8df;
            padding: 2rem 0 1.75rem;
        }
        .idx-eyebrow { display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.4rem; }
        .idx-eyebrow-line { width: 1.5rem; height: 1px; background: #b5813a; }
        .idx-eyebrow-text { font-size: 0.65rem; letter-spacing: 0.14em; text-transform: uppercase; color: #b5813a; font-weight: 600; }
        .idx-page-title { font-family: 'Playfair Display', serif; font-size: clamp(1.5rem, 3vw, 2.1rem); font-weight: 600; color: #1a1a2e; line-height: 1.1; }

        /* ── Buttons ── */
        .btn-gold {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.55rem 1.25rem;
            background: #b5813a; color: #fff;
            border-radius: 0.5rem; font-size: 0.8125rem; font-weight: 600;
            letter-spacing: 0.04em; text-transform: uppercase; text-decoration: none;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-gold:hover { background: #9a6e2f; box-shadow: 0 4px 14px rgba(181,129,58,0.3); }

        .btn-ghost-red {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.55rem 1.1rem;
            background: #fef2f2; color: #dc2626;
            border: 1px solid #fecaca;
            border-radius: 0.5rem; font-size: 0.8125rem; font-weight: 600;
            letter-spacing: 0.04em; text-transform: uppercase; text-decoration: none;
            transition: background 0.2s ease;
        }
        .btn-ghost-red:hover { background: #fee2e2; }

        /* ── Status filter tabs ── */
        .filter-tab {
            padding: 0.4rem 1rem;
            border-radius: 2rem;
            font-size: 0.78rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            color: #8c8070;
            background: var(--cream, #faf7f2);
            border-color: #ede8df;
        }
        .filter-tab:hover { color: #b5813a; border-color: #d4a85e; }
        .filter-tab.active { background: #b5813a; color: #fff; border-color: #b5813a; }

        /* ── Panel ── */
        .panel { background: var(--cream, #faf7f2); border: 1px solid #ede8df; border-radius: 0.75rem; overflow: hidden; }
        .panel-footer { padding: 1rem 1.5rem; border-top: 1px solid #ede8df; }

        /* ── Table ── */
        .re-table { width: 100%; border-collapse: collapse; }
        .re-table thead th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.65rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
            color: #8c8070; background: #f4f0e8;
            border-bottom: 1px solid #ede8df;
        }
        .re-table thead th:last-child { text-align: right; }
        .re-table tbody tr { border-bottom: 1px solid #ede8df; transition: background 0.15s ease; }
        .re-table tbody tr:last-child { border-bottom: none; }
        .re-table tbody tr:hover { background: rgba(181,129,58,0.04); }
        .re-table td { padding: 1rem 1.5rem; font-size: 0.875rem; color: #1a1a2e; vertical-align: middle; }
        .re-table td:last-child { text-align: right; }

        .prop-thumb { width: 3rem; height: 3rem; border-radius: 0.5rem; background: #ede8df; overflow: hidden; flex-shrink: 0; }
        .prop-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .prop-thumb-empty { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #8c8070; }
        .prop-name { font-weight: 500; color: #1a1a2e; }
        .prop-loc  { font-size: 0.78rem; color: #8c8070; margin-top: 0.15rem; }
        .prop-price { font-weight: 600; }

        /* ── Badges ── */
        .badge { display: inline-block; padding: 0.2rem 0.65rem; border-radius: 2rem; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.04em; }
        .badge-approved { background: #ecfdf5; color: #059669; }
        .badge-pending  { background: #fffbeb; color: #d97706; }
        .badge-rejected { background: #fef2f2; color: #dc2626; }

        /* ── Action links ── */
        .act-link { font-size: 0.78rem; font-weight: 500; text-decoration: none; color: #b5813a; transition: color 0.15s; }
        .act-link:hover { color: #9a6e2f; }
        .act-link-muted { color: #8c8070; }
        .act-link-muted:hover { color: #1a1a2e; }
        .act-link-danger { color: #dc2626; }
        .act-link-danger:hover { color: #b91c1c; }

        /* ── Stat mini cards ── */
        .mini-stat { background: var(--cream, #faf7f2); border: 1px solid #ede8df; border-radius: 0.75rem; padding: 1.1rem 1.25rem; }
        .mini-stat-label { font-size: 0.72rem; color: #8c8070; font-weight: 500; letter-spacing: 0.04em; text-transform: uppercase; }
        .mini-stat-value { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 600; margin-top: 0.2rem; }

        /* ── Empty state ── */
        .empty-cell { padding: 3.5rem 1.5rem; text-align: center; color: #8c8070; }
    </style>

    <div class="idx-root">

        {{-- Page header --}}
        <div class="idx-page-header">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div>
                        <div class="idx-eyebrow">
                            <span class="idx-eyebrow-line"></span>
                            <span class="idx-eyebrow-text">Seller Portal</span>
                        </div>
                        <h1 class="idx-page-title">My Listings</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('seller.properties.rejected') }}" class="btn-ghost-red">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Rejected
                        </a>
                        <a href="{{ route('seller.properties.create') }}" class="btn-gold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Property
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-8">

            {{-- Filter tabs --}}
            <div class="flex gap-2 mb-6">
                <a href="{{ route('seller.properties.index') }}"
                   class="filter-tab {{ !request('status') ? 'active' : '' }}">
                    All Properties
                </a>
            </div>

            {{-- Table --}}
            <div class="panel reveal">
                <div class="overflow-x-auto">
                    <table class="re-table">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Listed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($properties as $property)
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:0.875rem">
                                            <div class="prop-thumb">
                                                @if($property->image)
                                                    <img src="{{ asset('images/' . $property->image) }}" alt="">
                                                @else
                                                    <div class="prop-thumb-empty">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="prop-name">{{ $property->title }}</div>
                                                <div class="prop-loc">{{ $property->location }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $property->views_count }}</td>

                                    <td class="prop-price">Rs {{ number_format($property->price) }}</td>

                                    <td>
                                        <span class="badge
                                            @if($property->status === 'approved') badge-approved
                                            @elseif($property->status === 'pending') badge-pending
                                            @else badge-rejected @endif">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                        @if($property->status === 'rejected' && $property->rejection_reason)
                                            <span style="margin-left:0.4rem;font-size:0.75rem;color:#8c8070;cursor:help"
                                                  title="{{ $property->rejection_reason }}">ⓘ</span>
                                        @endif
                                    </td>

                                    <td style="color:#8c8070">{{ $property->created_at->format('M d, Y') }}</td>

                                    <td>
                                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:1rem">
                                            <a href="{{ route('seller.properties.show', $property->id) }}" class="act-link">View</a>

                                            @if($property->status !== 'rejected')
                                                <a href="{{ route('seller.properties.edit', $property->id) }}" class="act-link act-link-muted">Edit</a>
                                            @endif

                                            <form action="{{ route('seller.properties.destroy', $property->id) }}" method="POST" style="display:inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="act-link act-link-danger" style="background:none;border:none;cursor:pointer;padding:0"
                                                    onclick="return confirm('Are you sure you want to delete this property? This action cannot be undone.')">
                                                    Delete
                                                </button>
                                            </form>
                                            <form action="{{ route('seller.properties.listing-status', $property->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <select name="listing_status" onchange="this.form.submit()" class="text-xs border rounded px-1 py-0.5">
                                                    <option value="available" {{ ($property->listing_status ?? 'available') === 'available' ? 'selected' : '' }}>Available</option>
                                                    <option value="sold" {{ ($property->listing_status ?? '') === 'sold' ? 'selected' : '' }}>Sold</option>
                                                    <option value="rented" {{ ($property->listing_status ?? '') === 'rented' ? 'selected' : '' }}>Rented</option>
                                                </select>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-cell">
                                        <svg class="w-12 h-12 mx-auto mb-3" style="color:#ede8df" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                                        </svg>
                                        <p style="margin-bottom:0.5rem">No properties listed yet.</p>
                                        <a href="{{ route('seller.properties.create') }}" class="act-link" style="font-size:0.875rem">+ Add Your First Property</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(method_exists($properties, 'hasPages') && $properties->hasPages())
                    <div class="panel-footer">{{ $properties->links() }}</div>
                @endif
            </div>

            {{-- Mini stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 reveal reveal-delay-1">
                <div class="mini-stat">
                    <p class="mini-stat-label">Total</p>
                    <p class="mini-stat-value" style="color:#1a1a2e">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="mini-stat">
                    <p class="mini-stat-label">Approved</p>
                    <p class="mini-stat-value" style="color:#059669">{{ $stats['approved'] ?? 0 }}</p>
                </div>
                <div class="mini-stat">
                    <p class="mini-stat-label">Pending</p>
                    <p class="mini-stat-value" style="color:#d97706">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="mini-stat">
                    <p class="mini-stat-label">Rejected</p>
                    <p class="mini-stat-value" style="color:#dc2626">{{ $stats['rejected'] ?? 0 }}</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
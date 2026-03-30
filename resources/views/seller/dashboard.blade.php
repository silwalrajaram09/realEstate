<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500;600&display=swap');

        .dash-root {
            font-family: 'DM Sans', sans-serif;
            background: var(--mist, #f4f0e8);
            min-height: 100%;
        }

        /* ── Page header ── */
        .dash-page-header {
            background: var(--cream, #faf7f2);
            border-bottom: 1px solid #ede8df;
            padding: 2rem 0 1.75rem;
        }
        .dash-eyebrow {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 0.4rem;
        }
        .dash-eyebrow-line { width: 1.5rem; height: 1px; background: #b5813a; }
        .dash-eyebrow-text {
            font-size: 0.65rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #b5813a;
            font-weight: 600;
        }
        .dash-page-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.5rem, 3vw, 2.1rem);
            font-weight: 600;
            color: #1a1a2e;
            line-height: 1.1;
        }
        .dash-page-sub {
            font-size: 0.85rem;
            color: #8c8070;
            margin-top: 0.25rem;
        }

        /* ── Add button ── */
        .btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.25rem;
            background: #b5813a;
            color: #fff;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            text-decoration: none;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-gold:hover {
            background: #9a6e2f;
            box-shadow: 0 4px 14px rgba(181,129,58,0.3);
        }

        /* ── Stat cards ── */
        .stat-card {
            background: var(--cream, #faf7f2);
            border: 1px solid #ede8df;
            border-radius: 0.75rem;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: box-shadow 0.2s ease;
        }
        .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
        .stat-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-icon-gold  { background: #fdf3e3; color: #b5813a; }
        .stat-icon-green { background: #ecfdf5; color: #059669; }
        .stat-icon-amber { background: #fffbeb; color: #d97706; }
        .stat-icon-red   { background: #fef2f2; color: #dc2626; }
        .stat-label { font-size: 0.75rem; color: #8c8070; font-weight: 500; letter-spacing: 0.04em; text-transform: uppercase; }
        .stat-value { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 600; color: #1a1a2e; line-height: 1; margin-top: 0.2rem; }

        /* ── Table panel ── */
        .panel {
            background: var(--cream, #faf7f2);
            border: 1px solid #ede8df;
            border-radius: 0.75rem;
            overflow: hidden;
        }
        .panel-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #ede8df;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .panel-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: #1a1a2e;
        }

        /* ── Table ── */
        .re-table { width: 100%; border-collapse: collapse; }
        .re-table thead th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #8c8070;
            background: #f4f0e8;
            border-bottom: 1px solid #ede8df;
        }
        .re-table thead th:last-child { text-align: right; }
        .re-table tbody tr {
            border-bottom: 1px solid #ede8df;
            transition: background 0.15s ease;
        }
        .re-table tbody tr:last-child { border-bottom: none; }
        .re-table tbody tr:hover { background: rgba(181,129,58,0.04); }
        .re-table td { padding: 1rem 1.5rem; font-size: 0.875rem; color: #1a1a2e; vertical-align: middle; }
        .re-table td:last-child { text-align: right; }

        .prop-name { font-weight: 500; color: #1a1a2e; }
        .prop-loc  { font-size: 0.78rem; color: #8c8070; margin-top: 0.15rem; }
        .prop-price { font-weight: 600; color: #1a1a2e; }

        /* ── Badges ── */
        .badge {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.04em;
        }
        .badge-sale   { background: #ecfdf5; color: #059669; }
        .badge-rent   { background: #eff6ff; color: #2563eb; }
        .badge-approved { background: #ecfdf5; color: #059669; }
        .badge-pending  { background: #fffbeb; color: #d97706; }
        .badge-rejected { background: #fef2f2; color: #dc2626; }

        /* ── Action links ── */
        .act-link {
            font-size: 0.78rem;
            font-weight: 500;
            text-decoration: none;
            color: #b5813a;
            transition: color 0.15s;
        }
        .act-link:hover { color: #9a6e2f; }
        .act-link-muted { color: #8c8070; }
        .act-link-muted:hover { color: #1a1a2e; }
        .act-link-danger { color: #dc2626; }
        .act-link-danger:hover { color: #b91c1c; }

        /* ── Pagination ── */
        .panel-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #ede8df;
        }

        /* ── Empty state ── */
        .empty-cell { padding: 3rem 1.5rem; text-align: center; color: #8c8070; font-size: 0.875rem; }
    </style>

    <div class="dash-root">
        <div class="dash-page-header">
            <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 flex items-end justify-between">
                <div>
                    <div class="dash-eyebrow">
                        <span class="dash-eyebrow-line"></span>
                        <span class="dash-eyebrow-text">Seller Portal</span>
                    </div>
                    <h1 class="dash-page-title">Dashboard</h1>
                    <p class="dash-page-sub">Manage your property listings</p>
                </div>

                <a href="{{ route('seller.properties.create') }}" class="btn-gold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Property
                </a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-5 sm:px-8 lg:px-10 py-10">

            {{-- ── STATS ── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8 reveal">

                <div class="stat-card">
                    <div class="stat-icon stat-icon-gold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Total</p>
                        <p class="stat-value">{{ $stats['total'] }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-green">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="1.75" d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Approved</p>
                        <p class="stat-value">{{ $stats['approved'] }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-amber">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="1.75" d="M12 8v4l3 3"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Pending</p>
                        <p class="stat-value">{{ $stats['pending'] }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-red">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="1.75" d="M10 14l4-4m0 4l-4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="stat-label">Rejected</p>
                        <p class="stat-value">{{ $stats['rejected'] }}</p>
                    </div>
                </div>
            </div>

            {{-- ── TABLE ── --}}
            <div class="panel reveal reveal-delay-1">
                <div class="panel-header">
                    <span class="panel-title">Your Properties</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="re-table">
                        <thead>
                            <tr>
                                <th>Property</th>
                                <th>Price</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Listed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($properties as $property)
                                <tr>
                                    <td>
                                        <div class="prop-name">{{ $property->title }}</div>
                                        <div class="prop-loc">{{ $property->location }}</div>
                                    </td>

                                    <td class="prop-price">₹ {{ number_format($property->price) }}</td>

                                    <td>
                                        <span class="badge {{ $property->purpose === 'buy' ? 'badge-sale' : 'badge-rent' }}">
                                            {{ ucfirst($property->purpose) }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="badge
                                            @if($property->status === 'approved') badge-approved
                                            @elseif($property->status === 'pending') badge-pending
                                            @else badge-rejected @endif">
                                            {{ ucfirst($property->status) }}
                                        </span>
                                    </td>

                                    <td style="color:#8c8070">{{ $property->created_at->format('M d, Y') }}</td>

                                    <td>
                                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:1rem">
                                            <a href="{{ route('seller.properties.show', $property) }}" class="act-link">View</a>
                                            <a href="{{ route('seller.properties.edit', $property) }}" class="act-link act-link-muted">Edit</a>
                                            <form action="{{ route('seller.properties.destroy', $property) }}" method="POST" style="display:inline">
                                                @csrf @method('DELETE')
                                                <button onclick="return confirm('Delete this property?')" class="act-link act-link-danger" style="background:none;border:none;cursor:pointer;padding:0">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-cell">No properties listed yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="panel-footer">
                    {{ $properties->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
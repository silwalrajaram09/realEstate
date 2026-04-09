<x-app-layout>
<div class="show-root" style="background:#f4f0e8;min-height:100vh;">

    <div class="max-w-5xl mx-auto px-6 py-10">

        {{-- Header --}}
        <div style="margin-bottom:2rem;">
            <div style="display:flex;align-items:center;gap:0.625rem;margin-bottom:0.5rem;">
                <div style="width:1.5rem;height:1px;background:#c9a96e;"></div>
                <span style="font-size:0.65rem;letter-spacing:0.14em;text-transform:uppercase;color:#c9a96e;font-weight:600;">
                    My Activity
                </span>
            </div>

            <h1 style="font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:600;color:#0f0f0f;">
                My Enquiries
            </h1>

            <p style="font-size:0.85rem;color:#8c8070;font-weight:300;margin-top:0.25rem;">
                {{ $enquiries->total() }} enquir{{ $enquiries->total() === 1 ? 'y' : 'ies' }} submitted
            </p>
        </div>

        @forelse($enquiries as $enq)
        <div class="detail-card"
             style="margin-bottom:1.25rem;padding:1.5rem 1.75rem;transition:0.2s ease; border: 1px solid #ede8df;">

            <div style="display:flex;align-items:flex-start;gap:1.5rem;flex-wrap:wrap;">

                {{-- Property Image --}}
                <div style="width:110px;height:85px;border-radius:6px;overflow:hidden;background:#e8e0d4;flex-shrink:0;">
                    <img src="{{ optional($enq->property)->image_url ?? asset('images/image1.jpg') }}"
                         style="width:100%;height:100%;object-fit:cover;">
                </div>

                {{-- Info --}}
                <div style="flex:1;min-width:0;">

                    {{-- Title + Match --}}
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;margin-bottom:0.25rem;">
                        <h2 style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:600;color:#0f0f0f;margin:0;">
                            {{ optional($enq->property)->title ?? 'Property not available' }}
                        </h2>
                        @if($enq->match_score > 0)
                            <div style="font-size:0.6rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:20px;
                                        background:#fdf6ec;color:#9a7340;border:1px solid #f0e0c0;
                                        text-transform:uppercase;letter-spacing:0.04em;display:flex;align-items:center;gap:0.25rem;">
                                🏆 {{ round($enq->match_score * 100) }}% Match
                            </div>
                        @endif
                    </div>

                    {{-- Meta --}}
                    <div style="font-size:0.78rem;color:#8c8070;display:flex;align-items:center;gap:0.5rem;">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ optional($enq->property)->location ?? 'Unknown location' }}
                        &nbsp;·&nbsp;
                        Sent {{ $enq->created_at->diffForHumans() }}
                    </div>

                    {{-- User Message --}}
                    @if($enq->message)
                    <div style="margin-top:0.875rem;">
                        <div style="font-size:0.85rem;color:#4a4038;line-height:1.5;font-style:italic;">
                            "{{ \Illuminate\Support\Str::limit($enq->message, 150) }}"
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Status --}}
                <div style="flex-shrink:0;">
                    @php
                        $colours = [
                            'new'     => ['bg'=>'#fff8e8','txt'=>'#b8860b','bdr'=>'#f0d080'],
                            'read'    => ['bg'=>'#f0f4ff','txt'=>'#3b5bdb','bdr'=>'#c5d0f8'],
                            'replied' => ['bg'=>'#f0fff4','txt'=>'#2f7c55','bdr'=>'#b4e8c8'],
                            'closed'  => ['bg'=>'#f5f5f5','txt'=>'#888','bdr'=>'#ddd'],
                        ];
                        $c = $colours[$enq->status] ?? $colours['new'];
                    @endphp

                    <div style="text-align:right;">
                        <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;
                                     padding:0.25rem 0.65rem;border-radius:3px;
                                     background:{{ $c['bg'] }};color:{{ $c['txt'] }};border:1px solid {{ $c['bdr'] }};">
                            {{ ucfirst($enq->status) }}
                        </span>
                        
                        <div style="margin-top:0.75rem;">
                            <a href="{{ route('buyer.properties.show', $enq->property) }}" 
                               style="font-size:0.68rem;font-weight:700;text-transform:uppercase;color:#c9a96e;text-decoration:none;letter-spacing:0.05em;display:block;">
                                View Listing →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Seller Reply --}}
            @if($enq->reply)
            <div style="margin-top:1.25rem;padding:1.25rem;background:#faf7f2;
                        border-left:3px solid #c9a96e;border-radius:0 6px 6px 0; animation: fadeIn 0.3s ease;">

                <div style="font-size:0.65rem;color:#c9a96e;font-weight:700;
                            letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.5rem;display:flex;align-items:center;gap:0.5rem;">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Seller's Response · {{ optional($enq->replied_at)->format('d M Y') }}
                </div>

                <div style="font-size:0.92rem;color:#0f0f0f;line-height:1.6;font-weight:400;">
                    {{ $enq->reply }}
                </div>
            </div>
            @endif

        </div>
        @empty

        {{-- Empty State --}}
        <div style="text-align:center;padding:4rem 0;">
            <p style="font-size:0.9rem;color:#b0a090;">
                You haven't sent any enquiries yet.
            </p>

            <a href="{{ route('buyer.properties') }}"
               style="display:inline-block;margin-top:1rem;font-size:0.75rem;font-weight:700;
                      letter-spacing:0.1em;text-transform:uppercase;color:#0f0f0f;
                      border-bottom:1.5px solid #c9a96e;text-decoration:none;">
                Browse Properties →
            </a>
        </div>

        @endforelse

        {{-- Pagination --}}
        @if($enquiries->hasPages())
        <div style="margin-top:2rem;">
            {{ $enquiries->links() }}
        </div>
        @endif

    </div>
</div>
</x-app-layout>
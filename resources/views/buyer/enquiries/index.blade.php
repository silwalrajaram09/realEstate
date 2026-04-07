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
             style="margin-bottom:1.25rem;padding:1.5rem 1.75rem;transition:0.2s ease;cursor:pointer;"
             onclick="window.location='{{ route('buyer.properties.show', $enq->property) }}'">

            <div style="display:flex;align-items:flex-start;gap:1.25rem;flex-wrap:wrap;">

                {{-- Property Image --}}
                <div style="width:90px;height:70px;border-radius:6px;overflow:hidden;background:#e8e0d4;">
                    <img src="{{ optional($enq->property)->image_url ?? asset('images/image1.jpg') }}"
                         style="width:100%;height:100%;object-fit:cover;">
                </div>

                {{-- Info --}}
                <div style="flex:1;min-width:0;">

                    {{-- Title --}}
                    <div style="font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:600;color:#0f0f0f;">
                        {{ optional($enq->property)->title ?? 'Property not available' }}
                    </div>

                    {{-- Meta --}}
                    <div style="font-size:0.78rem;color:#8c8070;margin-top:0.2rem;">
                        {{ optional($enq->property)->location ?? 'Unknown location' }}
                        &nbsp;·&nbsp;
                        Sent {{ $enq->created_at->diffForHumans() }}
                    </div>

                    {{-- User Message --}}
                    @if($enq->message)
                    <div style="margin-top:0.6rem;">
                        <div style="font-size:0.7rem;color:#c9a96e;font-weight:600;text-transform:uppercase;margin-bottom:0.2rem;">
                            Your Message
                        </div>

                        <div style="font-size:0.85rem;color:#4a4038;line-height:1.5;">
                            {{ \Illuminate\Support\Str::limit($enq->message, 120) }}
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Status --}}
                <div style="flex-shrink:0;text-align:right;">
                    @php
                        $colours = [
                            'new'     => ['bg'=>'#fff8e8','txt'=>'#b8860b'],
                            'read'    => ['bg'=>'#f0f4ff','txt'=>'#3b5bdb'],
                            'replied' => ['bg'=>'#f0fff4','txt'=>'#2f7c55'],
                            'closed'  => ['bg'=>'#f5f5f5','txt'=>'#888'],
                        ];
                        $c = $colours[$enq->status] ?? $colours['new'];
                    @endphp

                    <span style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;
                                 padding:0.3rem 0.6rem;border-radius:3px;
                                 background:{{ $c['bg'] }};color:{{ $c['txt'] }};">
                        {{ ucfirst($enq->status) }}
                    </span>

                    {{-- Extra status info --}}
                    @if($enq->status === 'replied')
                        <div style="font-size:0.65rem;color:#2f7c55;margin-top:0.3rem;">
                            ✔ Seller replied
                        </div>
                    @endif
                </div>
            </div>

            {{-- Seller Reply --}}
            @if($enq->reply)
            <div style="margin-top:1.2rem;padding:1rem 1.2rem;background:#faf7f2;
                        border-left:3px solid #c9a96e;border-radius:0 6px 6px 0;">

                <div style="font-size:0.65rem;color:#c9a96e;font-weight:700;
                            letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.4rem;">
                    Seller Reply · {{ optional($enq->replied_at)->format('d M Y') }}
                </div>

                <div style="font-size:0.88rem;color:#4a4038;line-height:1.6;">
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
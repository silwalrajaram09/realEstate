 {{-- resources/views/seller/enquiries/index.blade.php --}}
<x-app-layout>
<div class="show-root" style="background:#f4f0e8;min-height:100vh;">
<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:2rem;">
        <div>
            <div style="display:flex;align-items:center;gap:0.625rem;margin-bottom:0.5rem;">
                <div style="width:1.5rem;height:1px;background:#c9a96e;"></div>
                <span style="font-size:0.65rem;letter-spacing:0.14em;text-transform:uppercase;color:#c9a96e;font-weight:600;">
                    Inbox
                </span>
            </div>
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:600;color:#0f0f0f;">
                Enquiries
            </h1>
        </div>
        @if($newCount > 0)
        <span style="font-size:0.78rem;font-weight:700;padding:0.4rem 0.9rem;
                     background:#fff8e8;color:#b8860b;border:1px solid #f0d080;border-radius:4px;">
            {{ $newCount }} new unread
        </span>
        @endif
    </div>

    {{-- Filter tabs --}}
    <div style="display:flex;gap:0.5rem;margin-bottom:1.75rem;flex-wrap:wrap;" id="filterTabs">
        @foreach(['all','new','read','replied','closed'] as $tab)
        <button onclick="filterTab('{{ $tab }}', this)"
                class="filter-tab {{ $tab === 'all' ? 'active' : '' }}"
                style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;
                       padding:0.4rem 0.85rem;border-radius:3px;border:1px solid #ddd;
                       background:{{ $tab === 'all' ? '#0f0f0f' : '#fff' }};
                       color:{{ $tab === 'all' ? '#fff' : '#4a4038' }};
                       cursor:pointer;transition:all 0.2s;">
            {{ ucfirst($tab) }}
        </button>
        @endforeach
    </div>

    {{-- Enquiry list --}}
    @forelse($enquiries as $enq)
    <div class="enq-row detail-card"
         data-status="{{ $enq->status }}"
         data-id="{{ $enq->id }}"
         style="margin-bottom:1rem;padding:1.25rem 1.5rem;cursor:pointer;transition:box-shadow 0.2s;"
         onclick="toggleEnquiry({{ $enq->id }})">

        {{-- Row summary --}}
        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">

            {{-- Unread dot --}}
            <div style="flex-shrink:0;width:8px;height:8px;border-radius:50%;
                        background:{{ $enq->status === 'new' ? '#c9a96e' : 'transparent' }};
                        border:{{ $enq->status !== 'new' ? '1px solid #ddd' : 'none' }};"
                 id="dot-{{ $enq->id }}">
            </div>

            {{-- Avatar --}}
            <div style="width:38px;height:38px;border-radius:50%;background:#1a1510;
                        display:flex;align-items:center;justify-content:center;
                        color:#c9a96e;font-size:0.85rem;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($enq->name, 0, 2)) }}
            </div>

            {{-- Buyer info --}}
            <div style="flex:1;min-width:0;">
                <div style="font-weight:600;color:#0f0f0f;font-size:0.92rem;">{{ $enq->name }}</div>
                <div style="font-size:0.75rem;color:#8c8070;">
                    {{ $enq->email }} &nbsp;·&nbsp; {{ $enq->phone }}
                </div>
            </div>

            {{-- Property --}}
            <div style="flex:1;min-width:0;display:none;" class="sm-show">
                <div style="font-size:0.78rem;font-weight:600;color:#4a4038;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $enq->property->title ?? '—' }}
                </div>
                <div style="font-size:0.7rem;color:#b0a090;">{{ $enq->created_at->diffForHumans() }}</div>
            </div>

            {{-- Status + actions --}}
            <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                @php
                    $colours = [
                        'new'     => ['bg'=>'#fff8e8','txt'=>'#b8860b','bdr'=>'#f0d080'],
                        'read'    => ['bg'=>'#f0f4ff','txt'=>'#3b5bdb','bdr'=>'#c5d0f8'],
                        'replied' => ['bg'=>'#f0fff4','txt'=>'#2f7c55','bdr'=>'#b4e8c8'],
                        'closed'  => ['bg'=>'#f5f5f5','txt'=>'#888','bdr'=>'#ddd'],
                    ];
                    $c = $colours[$enq->status] ?? $colours['new'];
                @endphp
                <span id="badge-{{ $enq->id }}"
                      style="font-size:0.65rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;
                             padding:0.225rem 0.55rem;border-radius:3px;
                             background:{{ $c['bg'] }};color:{{ $c['txt'] }};border:1px solid {{ $c['bdr'] }};">
                    {{ ucfirst($enq->status) }}
                </span>

                {{-- Delete btn --}}
                <button onclick="deleteEnquiry(event, {{ $enq->id }})"
                        style="background:none;border:none;cursor:pointer;color:#c0a090;padding:0.2rem;"
                        title="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Expandable detail --}}
        <div id="expand-{{ $enq->id }}" style="display:none;margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid #f0ece4;">

            {{-- Buyer's message --}}
            @if($enq->message)
            <div style="font-size:0.88rem;color:#4a4038;line-height:1.65;font-style:italic;margin-bottom:1.25rem;">
                "{{ $enq->message }}"
            </div>
            @endif

            {{-- Existing reply --}}
            @if($enq->reply)
            <div style="padding:1rem 1.25rem;background:#faf7f2;border-left:3px solid #c9a96e;
                        border-radius:0 6px 6px 0;margin-bottom:1rem;"
                 id="replyDisplay-{{ $enq->id }}">
                <div style="font-size:0.62rem;color:#c9a96e;font-weight:700;letter-spacing:0.1em;
                            text-transform:uppercase;margin-bottom:0.4rem;">
                    Your Reply · {{ $enq->replied_at->format('d M Y') }}
                </div>
                <div style="font-size:0.88rem;color:#4a4038;">{{ $enq->reply }}</div>
            </div>
            @else
            <div id="replyDisplay-{{ $enq->id }}" style="display:none;"></div>
            @endif

            {{-- Reply form --}}
            <div id="replyForm-{{ $enq->id }}">
                <textarea id="replyText-{{ $enq->id }}"
                          placeholder="Write your reply…"
                          rows="3"
                          onclick="event.stopPropagation()"
                          style="width:100%;padding:0.75rem 1rem;border:1px solid #e8e0d4;border-radius:6px;
                                 font-size:0.88rem;color:#4a4038;background:#fff;resize:vertical;
                                 font-family:inherit;margin-bottom:0.75rem;box-sizing:border-box;">{{ $enq->reply }}</textarea>
                <div style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;">
                    <button onclick="sendReply(event, {{ $enq->id }})"
                            id="replyBtn-{{ $enq->id }}"
                            style="font-size:0.72rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;
                                   padding:0.55rem 1.25rem;background:#0f0f0f;color:#fff;border:none;
                                   border-radius:4px;cursor:pointer;">
                        {{ $enq->reply ? 'Update Reply' : 'Send Reply' }}
                    </button>

                    {{-- Close button --}}
                    @if($enq->status !== 'closed')
                    <button onclick="changeStatus(event, {{ $enq->id }}, 'closed')"
                            style="font-size:0.72rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;
                                   padding:0.55rem 1rem;background:none;color:#888;border:1px solid #ddd;
                                   border-radius:4px;cursor:pointer;">
                        Mark Closed
                    </button>
                    @endif

                    <div id="replyMsg-{{ $enq->id }}" style="font-size:0.78rem;color:#2f7c55;display:none;">
                        ✓ Saved
                    </div>
                </div>
            </div>
        </div>

    </div>
    @empty
    <div style="text-align:center;padding:4rem 0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none"
             viewBox="0 0 24 24" stroke="rgba(201,169,110,0.35)" stroke-width="1.25"
             style="margin:0 auto 1rem;display:block;">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <p style="color:#b0a090;font-size:0.9rem;">No enquiries yet.</p>
    </div>
    @endforelse

    @if($enquiries->hasPages())
    <div style="margin-top:2rem;">{{ $enquiries->links() }}</div>
    @endif

</div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

/* ── Expand / collapse row ── */
function toggleEnquiry(id) {
    const expand = document.getElementById(`expand-${id}`);
    const isOpen = expand.style.display !== 'none';
    expand.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) markRead(id);
}

/* ── Mark as read (silent) ── */
async function markRead(id) {
    const dot   = document.getElementById(`dot-${id}`);
    const badge = document.getElementById(`badge-${id}`);
    if (!dot || dot.style.background === 'transparent') return;

    await fetch(`/seller/enquiries/${id}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ status: 'read' }),
    });
    dot.style.background = 'transparent';
    dot.style.border     = '1px solid #ddd';
    badge.textContent    = 'Read';
    badge.style.background = '#f0f4ff';
    badge.style.color      = '#3b5bdb';
    badge.style.border     = '1px solid #c5d0f8';
}

/* ── Send / update reply ── */
async function sendReply(event, id) {
    event.stopPropagation();
    const btn  = document.getElementById(`replyBtn-${id}`);
    const text = document.getElementById(`replyText-${id}`).value.trim();
    const msg  = document.getElementById(`replyMsg-${id}`);

    if (!text) return;

    btn.disabled    = true;
    btn.textContent = 'Sending…';

    const res  = await fetch(`/seller/enquiries/${id}/reply`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ reply: text }),
    });
    const data = await res.json();

    if (data.success) {
        btn.textContent = 'Update Reply';
        btn.disabled    = false;

        // Update badge
        const badge = document.getElementById(`badge-${id}`);
        badge.textContent    = 'Replied';
        badge.style.background = '#f0fff4';
        badge.style.color      = '#2f7c55';
        badge.style.border     = '1px solid #b4e8c8';

        // Show confirmation
        msg.style.display = 'inline';
        setTimeout(() => msg.style.display = 'none', 3000);
    } else {
        btn.disabled    = false;
        btn.textContent = 'Update Reply';
    }
}

/* ── Change status ── */
async function changeStatus(event, id, status) {
    event.stopPropagation();
    await fetch(`/seller/enquiries/${id}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ status }),
    });

    const badge = document.getElementById(`badge-${id}`);
    badge.textContent    = ucfirst(status);
    badge.style.background = '#f5f5f5';
    badge.style.color      = '#888';
    badge.style.border     = '1px solid #ddd';
}

/* ── Delete ── */
async function deleteEnquiry(event, id) {
    event.stopPropagation();
    if (!confirm('Delete this enquiry?')) return;

    await fetch(`/seller/enquiries/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
    });

    const row = document.querySelector(`[data-id="${id}"]`);
    row.style.transition = 'opacity 0.3s, transform 0.3s';
    row.style.opacity    = '0';
    row.style.transform  = 'translateX(-8px)';
    setTimeout(() => row.remove(), 300);
}

/* ── Filter tabs ── */
function filterTab(status, btn) {
    document.querySelectorAll('.filter-tab').forEach(b => {
        b.style.background = '#fff';
        b.style.color      = '#4a4038';
    });
    btn.style.background = '#0f0f0f';
    btn.style.color      = '#fff';

    document.querySelectorAll('.enq-row').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
    });
}

function ucfirst(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
</script>
</x-app-layout>
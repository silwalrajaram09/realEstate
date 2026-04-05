{{-- ═══════════════════════════════════════════════════
     resources/views/partials/enquiry-form.blade.php
     Drop this inside your price-card in show.blade.php,
     replacing the old <form> block.
    ═══════════════════════════════════════════════════ --}}

<div class="price-card-heading">Enquire About This Property</div>

{{-- Toast notification (hidden by default) --}}
<div id="enquiryToast" style="
    display:none;
    padding:0.75rem 1rem;
    border-radius:6px;
    font-size:0.82rem;
    font-weight:500;
    margin-bottom:1rem;
    transition:all 0.3s ease;">
</div>

<div id="enquiryForm">
    <input  type="text"  id="enq_name"    class="contact-input" placeholder="Your Name"     required>
    <input  type="email" id="enq_email"   class="contact-input" placeholder="Email Address" required>
    <input  type="tel"   id="enq_phone"   class="contact-input" placeholder="Phone Number"  required>
    <textarea id="enq_message" class="contact-input"
              placeholder="Optional message…"
              rows="3"
              style="resize:vertical;"></textarea>

    <button id="enquirySubmitBtn" type="button"
            class="contact-submit"
            onclick="submitEnquiry({{ $property->id }})">
        Request Information
    </button>
</div>

{{-- Success state (shown after submission) --}}
<div id="enquirySuccess" style="display:none;text-align:center;padding:1.25rem 0;">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none"
         viewBox="0 0 24 24" stroke="#5a8a5a" stroke-width="1.5"
         style="margin:0 auto 0.75rem;display:block;">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <p style="font-weight:600;color:#0f0f0f;margin-bottom:0.25rem;">Enquiry Sent!</p>
    <p style="font-size:0.78rem;color:#8c8070;font-weight:300;">
        The seller will contact you shortly.
    </p>
</div>

<script>
async function submitEnquiry(propertyId) {
    const btn   = document.getElementById('enquirySubmitBtn');
    const toast = document.getElementById('enquiryToast');

    const name    = document.getElementById('enq_name').value.trim();
    const email   = document.getElementById('enq_email').value.trim();
    const phone   = document.getElementById('enq_phone').value.trim();
    const message = document.getElementById('enq_message').value.trim();

    // ── Basic client-side validation ──
    if (!name || !email || !phone) {
        showToast('Please fill in all required fields.', 'error');
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showToast('Please enter a valid email address.', 'error');
        return;
    }

    // ── Loading state ──
    btn.disabled    = true;
    btn.textContent = 'Sending…';

    try {
        const response = await fetch(`/properties/${propertyId}/enquire`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ name, email, phone, message }),
        });

        const data = await response.json();

        if (data.success) {
            // Show success UI
            document.getElementById('enquiryForm').style.display    = 'none';
            document.getElementById('enquirySuccess').style.display = 'block';
        } else {
            showToast(data.message || 'Something went wrong.', 'error');
            btn.disabled    = false;
            btn.textContent = 'Request Information';
        }

    } catch (err) {
        showToast('Network error. Please try again.', 'error');
        btn.disabled    = false;
        btn.textContent = 'Request Information';
    }
}

function showToast(message, type) {
    const toast = document.getElementById('enquiryToast');
    toast.textContent   = message;
    toast.style.display = 'block';
    toast.style.background = type === 'error' ? '#fff0f0' : '#f0fff4';
    toast.style.color      = type === 'error' ? '#c0392b' : '#27ae60';
    toast.style.border     = `1px solid ${type === 'error' ? '#f5c6cb' : '#c3e6cb'}`;

    // Auto-hide after 5 s
    setTimeout(() => { toast.style.display = 'none'; }, 5000);
}
</script>
import './bootstrap';
import './hero-slider';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.showRealtimeToast = function (message) {
    const el = document.createElement('div');
    el.textContent = message;
    el.style.position = 'fixed';
    el.style.bottom = '20px';
    el.style.right = '20px';
    el.style.background = '#1a1a2e';
    el.style.color = '#faf7f2';
    el.style.padding = '10px 14px';
    el.style.borderRadius = '8px';
    el.style.border = '1px solid #b5813a';
    el.style.zIndex = '9999';
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 3500);
};

window.bindRealtimeChannels = function ({ sellerId = null, ownerId = null } = {}) {
    if (!window.Echo) return;
    if (sellerId) {
        window.Echo.private(`seller.${sellerId}`).listen('.enquiry.alert', (e) => {
            window.showRealtimeToast(`New inquiry: ${e.payload?.property_title ?? 'property'}`);
        });
    }
    if (ownerId) {
        window.Echo.private(`owner.${ownerId}`).listen('.property.status.changed', (e) => {
            window.showRealtimeToast(`Listing status changed to ${e.payload?.status ?? 'updated'}`);
        });
    }
};
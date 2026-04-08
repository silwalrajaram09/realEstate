import './bootstrap';
import './hero-slider';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.showRealtimeToast = function (message) {
    const el = document.createElement('div');
    el.textContent = message;
    el.className = 'fixed bottom-5 right-5 bg-[#1a1a2e] text-[#faf7f2] px-3.5 py-2.5 rounded-lg border border-[#b5813a] z-[9999] shadow-lg transition-opacity duration-300';
    document.body.appendChild(el);
    setTimeout(() => {
        el.classList.add('opacity-0');
        setTimeout(() => el.remove(), 300);
    }, 3500);
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
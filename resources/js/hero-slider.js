document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevSlide');
    const nextBtn = document.getElementById('nextSlide');

    if (!slides.length) return;

    let current = 0;
    let interval;

    function goToSlide(index) {
        slides[current].classList.remove('opacity-100', 'scale-100');
        slides[current].classList.add('opacity-0', 'scale-110');

        dots[current].classList.remove('bg-white');
        dots[current].classList.add('bg-white/50');

        current = index;

        slides[current].classList.remove('opacity-0', 'scale-110');
        slides[current].classList.add('opacity-100', 'scale-100');

        dots[current].classList.remove('bg-white/50');
        dots[current].classList.add('bg-white');
    }

    function nextSlide() {
        goToSlide((current + 1) % slides.length);
    }

    function prevSlide() {
        goToSlide((current - 1 + slides.length) % slides.length);
    }

    function startAutoplay() {
        interval = setInterval(nextSlide, 5000);
    }

    function stopAutoplay() {
        clearInterval(interval);
    }

    // Buttons
    nextBtn?.addEventListener('click', () => {
        stopAutoplay();
        nextSlide();
        startAutoplay();
    });

    prevBtn?.addEventListener('click', () => {
        stopAutoplay();
        prevSlide();
        startAutoplay();
    });

    // Dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoplay();
            goToSlide(index);
            startAutoplay();
        });
    });

    // Pause on hover
    const slider = document.getElementById('slider');
    slider?.addEventListener('mouseenter', stopAutoplay);
    slider?.addEventListener('mouseleave', startAutoplay);

    startAutoplay();
});

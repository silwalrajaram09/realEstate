<style>
    .hero-slide {
        position: absolute;
        inset: 0;
        transition: opacity 1.2s ease, transform 1.2s ease;
    }
    .hero-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .hero-slide.active {
        opacity: 1;
        transform: scale(1);
        z-index: 1;
    }
    .hero-slide.inactive {
        opacity: 0;
        transform: scale(1.06);
        z-index: 0;
    }

    /* Content animations */
    @keyframes heroFadeUp {
        from { opacity: 0; transform: translateY(40px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes heroLineIn {
        from { width: 0; }
        to   { width: 4rem; }
    }

    .hero-eyebrow {
        animation: heroFadeUp 0.9s ease 0.2s both;
    }
    .hero-title {
        animation: heroFadeUp 0.9s ease 0.4s both;
    }
    .hero-subtitle {
        animation: heroFadeUp 0.9s ease 0.6s both;
    }
    .hero-cta {
        animation: heroFadeUp 0.9s ease 0.8s both;
    }

    .slide-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 20;
        width: 3rem;
        height: 3rem;
        border: 1px solid rgba(255,255,255,0.35);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        backdrop-filter: blur(6px);
        background: rgba(255,255,255,0.08);
        transition: background 0.2s ease, border-color 0.2s ease;
        font-size: 1.1rem;
        line-height: 1;
    }
    .slide-arrow:hover {
        background: rgba(201,169,110,0.35);
        border-color: rgba(201,169,110,0.7);
    }

    .dot-btn {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255,255,255,0.4);
        border: none;
        cursor: pointer;
        transition: background 0.3s, width 0.3s;
        padding: 0;
    }
    .dot-btn.active {
        background: #c9a96e;
        width: 24px;
        border-radius: 3px;
    }

    .hero-stat {
        text-align: center;
        padding: 1.25rem 2rem;
        border-right: 1px solid rgba(255,255,255,0.15);
    }
    .hero-stat:last-child { border-right: none; }
    .hero-stat .stat-num {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.25rem;
        font-weight: 600;
        color: #c9a96e;
        line-height: 1;
    }
    .hero-stat .stat-label {
        font-size: 0.7rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.6);
        margin-top: 0.25rem;
    }

    .scroll-hint {
        position: absolute;
        bottom: 2rem;
        right: 2.5rem;
        z-index: 20;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
        color: rgba(255,255,255,0.5);
        font-size: 0.65rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        writing-mode: vertical-rl;
        text-orientation: mixed;
    }
    .scroll-hint::after {
        content: '';
        display: block;
        width: 1px;
        height: 3rem;
        background: linear-gradient(to bottom, rgba(201,169,110,0.8), transparent);
        animation: scrollLine 1.8s ease-in-out infinite;
    }
    @keyframes scrollLine {
        0%   { transform: scaleY(0); transform-origin: top; opacity: 0; }
        40%  { transform: scaleY(1); transform-origin: top; opacity: 1; }
        60%  { transform: scaleY(1); transform-origin: bottom; opacity: 1; }
        100% { transform: scaleY(0); transform-origin: bottom; opacity: 0; }
    }
</style>

<section class="relative w-full overflow-hidden" style="height: 100vh; min-height: 600px;">

    {{-- Slides --}}
    <div id="heroSlider" class="relative w-full h-full">
        <div class="hero-slide active">
            <img src="{{ asset('images/image1.jpg') }}" alt="Luxury property">
        </div>
        <div class="hero-slide inactive">
            <img src="{{ asset('images/image2.jpg') }}" alt="Modern home">
        </div>
        <div class="hero-slide inactive">
            <img src="{{ asset('images/image3.jpg') }}" alt="Dream property">
        </div>
    </div>

    {{-- Overlay: dark gradient --}}
    <div class="absolute inset-0 z-10"
         style="background: linear-gradient(120deg, rgba(10,8,5,0.78) 0%, rgba(10,8,5,0.45) 55%, rgba(10,8,5,0.2) 100%);"></div>

    {{-- Gold accent line (left edge) --}}
    <div class="absolute left-0 top-0 bottom-0 z-20" style="width:3px; background: linear-gradient(to bottom, transparent, #c9a96e 30%, #c9a96e 70%, transparent);"></div>

    {{-- Content --}}
    <div class="absolute inset-0 z-20 flex items-center">
        <div class="max-w-7xl mx-auto px-8 md:px-14 w-full">

            <div class="max-w-2xl">
                {{-- Eyebrow --}}
                <div class="hero-eyebrow flex items-center gap-3 mb-6">
                    <div style="width:2.5rem; height:1px; background:#c9a96e;"></div>
                    <span style="font-size:0.7rem; letter-spacing:0.18em; text-transform:uppercase; color:#c9a96e; font-weight:500; font-family:'Outfit',sans-serif;">
                        Premium Real Estate
                    </span>
                </div>

                {{-- Title --}}
                <h1 class="hero-title" style="font-family:'Cormorant Garamond',serif; font-size:clamp(3rem,7vw,5.5rem); font-weight:600; line-height:1.05; color:#fff; margin-bottom:1.5rem;">
                    Find Your<br>
                    <em style="color:#c9a96e; font-style:italic;">Dream</em> Property
                </h1>

                {{-- Subtitle --}}
                <p class="hero-subtitle" style="color:rgba(255,255,255,0.72); font-size:1.05rem; font-weight:300; line-height:1.7; margin-bottom:2.5rem; max-width:34rem;">
                    Smart AI-powered suggestions matched to your budget, lifestyle, and preferences — for buyers and sellers alike.
                </p>

                {{-- CTAs --}}
                <div class="hero-cta flex flex-wrap gap-4">
                    <a href="{{ url('/properties') }}"
                       style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.875rem 2rem;
                              background:#c9a96e; color:#0f0f0f; font-weight:600; font-size:0.875rem;
                              letter-spacing:0.04em; border-radius:3px; text-decoration:none;
                              transition:background 0.2s ease, transform 0.2s ease;"
                       onmouseover="this.style.background='#b5924f'; this.style.transform='translateY(-1px)'"
                       onmouseout="this.style.background='#c9a96e'; this.style.transform='translateY(0)'">
                        Browse Properties
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>

                    <a href="{{ route('login') }}"
                       style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.875rem 2rem;
                              border:1px solid rgba(255,255,255,0.35); color:#fff; font-weight:500; font-size:0.875rem;
                              letter-spacing:0.04em; border-radius:3px; text-decoration:none; backdrop-filter:blur(6px);
                              background:rgba(255,255,255,0.06); transition:background 0.2s ease, border-color 0.2s ease;"
                       onmouseover="this.style.background='rgba(255,255,255,0.12)'; this.style.borderColor='rgba(255,255,255,0.6)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.06)'; this.style.borderColor='rgba(255,255,255,0.35)'">
                        Get Started
                    </a>
                </div>
            </div>

            {{-- Stats bar --}}
            <div class="mt-16 hidden md:inline-flex"
                 style="background:rgba(255,255,255,0.06); backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,0.1); border-radius:4px; overflow:hidden;">
                <div class="hero-stat">
                    <div class="stat-num">1,200+</div>
                    <div class="stat-label">Listings</div>
                </div>
                <div class="hero-stat">
                    <div class="stat-num">850+</div>
                    <div class="stat-label">Happy Buyers</div>
                </div>
                <div class="hero-stat">
                    <div class="stat-num">98%</div>
                    <div class="stat-label">Match Rate</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Arrows --}}
    <button id="heroPrev" class="slide-arrow" style="left:1.5rem;">&#8592;</button>
    <button id="heroNext" class="slide-arrow" style="right:1.5rem;">&#8594;</button>

    {{-- Dots --}}
    <div class="absolute bottom-8 left-1/2 z-20 flex items-center gap-2" style="transform:translateX(-50%);">
        <button class="dot-btn active" data-idx="0"></button>
        <button class="dot-btn" data-idx="1"></button>
        <button class="dot-btn" data-idx="2"></button>
    </div>

    {{-- Scroll hint --}}
    <div class="scroll-hint hidden md:flex">Scroll</div>

</section>

<script>
(function () {
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.dot-btn');
    let current  = 0;
    let timer;

    function goTo(idx) {
        slides[current].className = 'hero-slide inactive';
        dots[current].classList.remove('active');
        current = (idx + slides.length) % slides.length;
        slides[current].className = 'hero-slide active';
        dots[current].classList.add('active');
        clearInterval(timer);
        timer = setInterval(() => goTo(current + 1), 6000);
    }

    document.getElementById('heroNext').addEventListener('click', () => goTo(current + 1));
    document.getElementById('heroPrev').addEventListener('click', () => goTo(current - 1));
    dots.forEach(d => d.addEventListener('click', () => goTo(+d.dataset.idx)));

    timer = setInterval(() => goTo(current + 1), 6000);
})();
</script>
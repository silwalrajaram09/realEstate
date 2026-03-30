<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'RealEstate') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;1,400&family=Outfit:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --gold:       #c9a96e;
            --gold-light: #e8d5b0;
            --gold-dark:  #9a7340;
            --ink:        #0f0f0f;
            --cream:      #faf7f2;
            --mist:       #f4f0e8;
            --stone:      #8c8070;
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--mist);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
        }

        .app-page-header {
            background: var(--cream);
            border-bottom: 1px solid #ede8df;
            padding: 1.5rem 0;
        }
        .app-page-header-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .app-page-eyebrow {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            margin-bottom: 0.375rem;
        }
        .app-page-eyebrow-line {
            width: 1.5rem; height: 1px;
            background: var(--gold);
        }
        .app-page-eyebrow-text {
            font-size: 0.65rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
        }
        .app-page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.5rem, 3vw, 2.25rem);
            font-weight: 600;
            color: var(--ink);
            line-height: 1.1;
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
    </style>
</head>

<body>
    <div class="min-h-screen flex flex-col">

        {{-- Navbar --}}
        @include('layouts.navigation')

        {{-- Optional page header slot --}}
        @if(isset($header))
            <div class="app-page-header">
                <div class="app-page-header-inner">
                    {{ $header }}
                </div>
            </div>
        @endif

        {{-- Main content --}}
        <main class="grow">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <x-footer />
    </div>

    @stack('scripts')

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
</body>
</html>
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
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            min-height: 100vh;
            background: #0f0f0f;
            -webkit-font-smoothing: antialiased;
            display: flex;
        }

        .auth-split {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left panel: image */
        .auth-image-panel {
            flex: 1;
            position: relative;
            display: none;
            overflow: hidden;
        }
        @media (min-width: 1024px) {
            .auth-image-panel { display: block; }
        }

        .auth-image-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .auth-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(10,8,5,0.75) 0%, rgba(10,8,5,0.35) 100%);
        }

        .auth-image-content {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
        }

        .auth-image-quote {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.6rem, 2.8vw, 2.2rem);
            font-weight: 600;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .auth-image-quote em {
            color: #c9a96e;
            font-style: italic;
        }

        /* Right panel: form */
        .auth-form-panel {
            width: 100%;
            max-width: 480px;
            background: #faf7f2;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3rem 2.5rem;
            position: relative;
        }
        @media (min-width: 1024px) {
            .auth-form-panel { min-width: 460px; }
        }

        .auth-brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: #0f0f0f;
            text-decoration: none;
            display: block;
            margin-bottom: 2.5rem;
            letter-spacing: -0.01em;
        }
        .auth-brand span { color: #c9a96e; }

        /* Style form children injected by Laravel Breeze */
        .auth-form-panel label {
            font-size: 0.72rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
            color: #5a5048 !important;
        }

        .auth-form-panel input[type="text"],
        .auth-form-panel input[type="email"],
        .auth-form-panel input[type="password"] {
            border: 1px solid #ded8ce !important;
            border-radius: 3px !important;
            background: #fff !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 0.9375rem !important;
            font-weight: 400 !important;
            color: #0f0f0f !important;
            padding: 0.75rem 1rem !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
        }

        .auth-form-panel input:focus {
            outline: none !important;
            border-color: #c9a96e !important;
            box-shadow: 0 0 0 3px rgba(201,169,110,0.12) !important;
        }

        .auth-form-panel button[type="submit"],
        .auth-form-panel button.btn-primary {
            background: #c9a96e !important;
            color: #0f0f0f !important;
            font-family: 'Outfit', sans-serif !important;
            font-size: 0.8125rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.08em !important;
            text-transform: uppercase !important;
            border-radius: 3px !important;
            border: none !important;
            padding: 0.875rem 2rem !important;
            cursor: pointer !important;
            transition: background 0.2s ease !important;
            width: 100% !important;
        }
        .auth-form-panel button[type="submit"]:hover,
        .auth-form-panel button.btn-primary:hover {
            background: #b5924f !important;
        }

        .auth-form-panel a {
            color: #c9a96e !important;
            text-decoration: none !important;
        }
        .auth-form-panel a:hover {
            color: #9a7340 !important;
        }

        .gold-rule {
            height: 1px;
            background: linear-gradient(to right, #c9a96e, transparent);
            margin: 1.5rem 0;
        }
    </style>
</head>

<body>
    <div class="auth-split">

        {{-- Left: image + quote --}}
        <div class="auth-image-panel">
            <img src="{{ asset('images/image1.jpg') }}" alt="Luxury property">
            <div class="auth-image-overlay"></div>
            <div class="auth-image-content">
                {{-- Gold top rule --}}
                <div style="width:3rem; height:1.5px; background:#c9a96e; margin-bottom:1.5rem;"></div>
                <p class="auth-image-quote">
                    The right home<br>is waiting for <em>you</em>.
                </p>
                <p style="font-size:0.8rem; color:rgba(255,255,255,0.5); font-weight:300; letter-spacing:0.04em;">
                    RealEstate Suggester · Smart property matching
                </p>
            </div>
        </div>

        {{-- Right: form --}}
        <div class="auth-form-panel">
            <a href="{{ route('dashboard') }}" class="auth-brand">
                Real<span>Estate</span>
            </a>

            <div class="gold-rule"></div>

            {{ $slot }}
        </div>
    </div>
</body>
</html>
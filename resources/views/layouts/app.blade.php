<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ __('portal akademik') }}</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,100..900;1,100..900&amp;family=Libre+Caslon+Text:ital,wght@0,400;0,700;1,400&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-container": "#cc4811",
                        "on-surface-variant": "#594139",
                        "inverse-on-surface": "#f4f0ec",
                        "inverse-surface": "#31302e",
                        "surface-dim": "#ddd9d6",
                        "background": "#fdf8f5",
                        "tertiary-fixed": "#e9e2d5",
                        "on-primary-container": "#fffbff",
                        "tertiary-container": "#79746a",
                        "outline": "#8d7168",
                        "primary-fixed-dim": "#ffb59c",
                        "on-primary-fixed-variant": "#822700",
                        "surface-container-highest": "#e6e2de",
                        "secondary-fixed": "#e8e1db",
                        "surface-bright": "#fdf8f5",
                        "outline-variant": "#e1bfb4",
                        "inverse-primary": "#ffb59c",
                        "on-tertiary": "#ffffff",
                        "on-background": "#1c1b1a",
                        "on-tertiary-fixed": "#1e1b14",
                        "on-primary": "#ffffff",
                        "tertiary": "#605b52",
                        "on-secondary-container": "#68645f",
                        "surface-variant": "#e6e2de",
                        "on-primary-fixed": "#390c00",
                        "on-secondary-fixed-variant": "#494642",
                        "on-error-container": "#93000a",
                        "surface-container-high": "#ece7e4",
                        "on-secondary": "#ffffff",
                        "on-surface": "#1c1b1a",
                        "tertiary-fixed-dim": "#cdc6ba",
                        "secondary": "#615e59",
                        "on-tertiary-container": "#fffbff",
                        "surface-container": "#f1ede9",
                        "secondary-container": "#e8e1db",
                        "on-secondary-fixed": "#1d1b18",
                        "surface": "#fdf8f5",
                        "error": "#ba1a1a",
                        "on-tertiary-fixed-variant": "#4a463d",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed-dim": "#cbc5c0",
                        "error-container": "#ffdad6",
                        "surface-container-low": "#f7f3ef",
                        "primary": "#a73400",
                        "surface-tint": "#ab3600",
                        "primary-fixed": "#ffdbcf",
                        "on-error": "#ffffff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "unit": "8px",
                        "margin-mobile": "20px",
                        "container-max-width": "1280px",
                        "gutter": "32px",
                        "margin-desktop": "64px"
                    },
                    "fontFamily": {
                        "headline-lg": ["Libre Caslon Text"],
                        "label-sm": ["Hanken Grotesk"],
                        "display-lg-mobile": ["Libre Caslon Text"],
                        "display-lg": ["Libre Caslon Text"],
                        "body-lg": ["Hanken Grotesk"],
                        "headline-md": ["Libre Caslon Text"],
                        "label-lg": ["Hanken Grotesk"],
                        "body-md": ["Hanken Grotesk"]
                    },
                    "fontSize": {
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "400" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "letterSpacing": "0.03em", "fontWeight": "500" }],
                        "display-lg-mobile": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "400" }],
                        "label-lg": ["14px", { "lineHeight": "20px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>
    
    <style>
        .texture-bg {
            background-color: #fdf8f5;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.04'/%3E%3C/svg%3E");
        }
    </style>
</head>

@auth
    <body class="bg-background text-on-background font-body-md min-h-screen flex texture-bg">
        
        <nav class="bg-surface-container-low docked left-0 h-full w-64 border-r border-outline-variant flat no shadows fixed left-0 top-0 h-full flex flex-col py-8 gap-4 hidden md:flex z-50">
            <div class="px-6 flex flex-col items-center mb-8">
                <div class="w-16 h-16 rounded-full bg-surface-container-high mb-4 flex items-center justify-center overflow-hidden border border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">account_balance</span>
                </div>
                <h1 class="text-headline-sm font-headline-sm text-primary uppercase tracking-widest text-center">{{ __('SIS Admin') }}</h1>
            </div>
            
            <div class="flex-1 px-4 flex flex-col gap-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-300 ease-in-out cursor-pointer {{ Route::is('dashboard') ? 'text-primary font-bold border-r-4 border-primary bg-surface-bright shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-tertiary-fixed' }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ Route::is('dashboard') ? '1' : '0' }};">dashboard</span>
                    <span class="text-label-lg font-label-lg">{{ __('Dashboard') }}</span>
                </a>
                <a href="{{ route('students.index') }}" class="flex items-center gap-4 px-4 py-3 rounded-lg transition-all duration-300 ease-in-out cursor-pointer {{ Route::is('students.*') ? 'text-primary font-bold border-r-4 border-primary bg-surface-bright shadow-sm' : 'text-on-surface-variant hover:text-on-surface hover:bg-tertiary-fixed' }}">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ Route::is('students.*') ? '1' : '0' }};">school</span>
                    <span class="text-label-lg font-label-lg">{{ __('Student Directory') }}</span>
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="w-full text-left text-on-surface-variant hover:text-primary hover:bg-tertiary-fixed transition-all duration-300 flex items-center gap-4 px-4 py-3 rounded-lg ease-in-out">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="text-label-lg font-label-lg">{{ __('Logout') }}</span>
                    </button>
                </form>
            </div>
        </nav>

        <main class="flex-1 md:ml-64 flex flex-col min-h-screen">
            
            <header class="md:hidden bg-background border-b border-outline-variant flex justify-between items-center w-full px-margin-mobile h-16 sticky top-0 z-40">
                <h1 class="text-headline-md font-headline-md text-primary tracking-tight">{{ __('Academic Portal') }}</h1>
                <div class="flex items-center gap-4">
                    <a href="{{ route('lang.switch', 'en') }}" class="font-bold text-sm {{ App::getLocale() == 'en' ? 'text-primary' : 'text-on-surface-variant' }}">EN</a>
                    <span class="text-xs text-outline-variant">/</span>
                    <a href="{{ route('lang.switch', 'id') }}" class="font-bold text-sm {{ App::getLocale() == 'id' ? 'text-primary' : 'text-on-surface-variant' }}">ID</a>
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="material-symbols-outlined text-primary cursor-pointer active:opacity-80">logout</button>
                    </form>
                </div>
            </header>

            <header class="hidden md:flex bg-background border-b border-outline-variant flex justify-between items-center w-full px-margin-desktop h-16 sticky top-0 z-40 bg-opacity-90 backdrop-blur-md">
                <h1 class="text-headline-md font-headline-md text-primary tracking-tight">{{ __('Academic Portal') }}</h1>
                <div class="flex items-center gap-6">
                    <div class="relative">
                        <form action="{{ route('students.index') }}" method="GET">
                            <input class="bg-transparent border-0 border-b border-outline-variant px-2 py-1 text-body-md font-body-md focus:outline-none focus:border-primary w-64 placeholder-on-surface-variant transition-colors" name="search" value="{{ request('search') }}" placeholder="{{ __('Search students...') }}" type="text"/>
                            <button type="submit" class="material-symbols-outlined absolute right-0 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors">search</button>
                        </form>
                    </div>

                    <div class="flex items-center gap-3 border-r border-outline-variant pr-6">
                        <a href="{{ route('lang.switch', 'en') }}" class="font-label-lg text-label-lg uppercase tracking-wider {{ App::getLocale() == 'en' ? 'text-primary font-bold border-b border-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">EN</a>
                        <span class="text-outline-variant">/</span>
                        <a href="{{ route('lang.switch', 'id') }}" class="font-label-lg text-label-lg uppercase tracking-wider {{ App::getLocale() == 'id' ? 'text-primary font-bold border-b border-primary' : 'text-on-surface-variant hover:text-primary transition-colors' }}">ID</a>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-body-md font-body-md text-on-surface-variant font-medium">{{ Auth::user()->name }}</span>
                        <span class="w-8 h-8 rounded-full bg-primary-fixed text-primary flex items-center justify-center font-bold text-sm uppercase tracking-wide border border-outline-variant">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </span>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-margin-mobile md:p-margin-desktop overflow-y-auto">
                @yield('content')
            </div>
        </main>
    </body>
@else
    <body class="bg-background text-on-background min-h-screen flex flex-col relative texture-bg">
        <div class="absolute top-6 right-6 z-50 bg-surface border border-outline-variant px-4 py-2 flex gap-4 text-label-sm font-label-sm uppercase tracking-wider shadow-sm rounded">
            <a href="{{ route('lang.switch', 'en') }}" class="{{ App::getLocale() == 'en' ? 'text-primary font-bold underline decoration-primary underline-offset-4' : 'text-on-surface-variant hover:text-primary transition-colors' }}">🇬🇧 EN</a>
            <span class="text-outline-variant">|</span>
            <a href="{{ route('lang.switch', 'id') }}" class="{{ App::getLocale() == 'id' ? 'text-primary font-bold underline decoration-primary underline-offset-4' : 'text-on-surface-variant hover:text-primary transition-colors' }}">🇮🇩 ID</a>
        </div>

        <main class="flex-grow flex items-center justify-center p-margin-mobile md:p-margin-desktop relative z-10">
            @yield('content')
        </main>
    </body>
@endauth
</html>

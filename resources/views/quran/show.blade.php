<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $name }} — Coran</title>
    @include('partials.indie-theme')
    <style>
        .ayah-card {
            border: 1.5px solid var(--line);
            transition: box-shadow 0.2s ease;
        }
        .ayah-card:hover { box-shadow: var(--shadow-hover); }

        .ayah-number {
            min-width: 2.2rem;
            height: 2.2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            background: var(--accent-soft);
            color: var(--accent);
            flex-shrink: 0;
        }
        .arabic-text {
            font-size: 1.6rem;
            line-height: 2.2;
            direction: rtl;
            text-align: right;
            color: var(--text-main);
            font-family: 'Amiri', 'Scheherazade New', serif;
        }
        .english-text {
            font-size: 0.95rem;
            line-height: 1.7;
            font-style: italic;
            color: var(--text-muted);
        }
        audio {
            width: 100%;
            height: 36px;
            border-radius: 999px;
            margin-top: 0.75rem;
        }
        audio::-webkit-media-controls-panel { background: rgba(255,255,255,0.7); }
    </style>
</head>
<body class="indie-page min-h-screen {{ (auth()->user()->gender ?? 'male') === 'female' ? 'theme-female' : 'theme-male' }}">
<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-6">

    <!-- Header -->
    <div class="indie-panel p-7">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="indie-kicker mb-1">Sourate {{ $id }}</p>
                <h1 class="indie-title text-3xl font-black">📜 {{ $name }}</h1>
                <p class="mt-1 text-sm font-medium" style="color: var(--text-muted);">
                    {{ count($arabic) }} versets · Récitation par Cheikh Al-Afasy
                </p>
            </div>
            <a href="{{ route('quran.index') }}" class="secondary-button px-5 py-2.5 text-sm font-bold whitespace-nowrap">
                ← Toutes les sourates
            </a>
        </div>
    </div>

    <!-- Ayahs -->
    @foreach($arabic as $index => $ayah)
        <div class="ayah-card indie-panel p-6">

            <!-- Ayah number badge -->
            <div class="flex justify-end mb-3">
                <span class="ayah-number">{{ $ayah['numberInSurah'] }}</span>
            </div>

            <!-- Arabic -->
            <p class="arabic-text mb-4">{{ $ayah['text'] }}</p>

            <!-- French translation -->
            <p class="english-text mb-4">{{ $english[$index]['text'] }}</p>

            <!-- Audio — correct CDN URL using global ayah number -->
            <audio controls>
                <source src="https://cdn.islamic.network/quran/audio/128/ar.alafasy/{{ $ayah['number'] }}.mp3" type="audio/mpeg">
            </audio>

        </div>
    @endforeach

    </div>
</div>
</body>
</html>

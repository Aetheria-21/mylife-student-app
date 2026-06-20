<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coran — MyLife</title>
    @include('partials.indie-theme')
    <style>
        .surah-card {
            text-decoration: none;
            border: 1.5px solid var(--line) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .surah-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent) !important;
        }
        .surah-arabic {
            font-size: 1.15rem;
            direction: rtl;
            color: var(--accent);
        }
    </style>
</head>
<body class="indie-page min-h-screen {{ (auth()->user()->gender ?? 'male') === 'female' ? 'theme-female' : 'theme-male' }}">
<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-8">

    <!-- Header -->
    <div class="indie-panel p-8">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="indie-kicker mb-1">Saint Coran</p>
                <h1 class="indie-title text-4xl font-black">📖 Coran</h1>
                <p class="mt-2 font-medium" style="color: var(--text-muted);">
                    {{ count($surahs) }} sourates — choisissez-en une à lire et écouter
                </p>
            </div>
        </div>
    </div>

    <!-- Surah Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($surahs as $surah)
            <a href="{{ route('quran.show', $surah['number']) }}" class="surah-card indie-panel p-5">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-xs font-black px-2.5 py-1 rounded-full" style="background: var(--accent-soft); color: var(--accent);">
                        {{ $surah['number'] }}
                    </span>
                    <span class="text-xs font-semibold" style="color: var(--text-muted);">
                        {{ $surah['numberOfAyahs'] }} versets
                    </span>
                </div>
                <p class="font-black text-base mb-1" style="color: var(--text-main);">
                    {{ $surah['englishName'] }}
                </p>
                <p class="text-sm mb-2" style="color: var(--text-muted);">
                    {{ $surah['englishNameTranslation'] }}
                </p>
                <p class="surah-arabic font-bold">{{ $surah['name'] }}</p>
            </a>
        @endforeach
    </div>

    </div>
</div>
</body>
</html>

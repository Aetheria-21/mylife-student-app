<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace musulman — MyLife</title>
    @include('partials.indie-theme')
    <style>
        .hub-card {
            text-decoration: none;
            border: 1.5px solid var(--line) !important;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
        }
        .hub-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent) !important;
        }
        .hub-icon {
            font-size: 4rem;
            display: block;
            margin-bottom: 1rem;
            transition: transform 0.22s ease;
        }
        .hub-card:hover .hub-icon { transform: scale(1.12); }
    </style>
</head>
<body class="indie-page min-h-screen {{ (auth()->user()->gender ?? 'male') === 'female' ? 'theme-female' : 'theme-male' }}">
<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-8">

    <!-- Header -->
    <div class="indie-panel p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="indie-kicker mb-1">Coin spirituel</p>
                <h1 class="indie-title text-4xl font-black">🌙 Espace musulman</h1>
                <p class="mt-2 font-medium" style="color: var(--text-muted);">
                    Votre compagnon spirituel quotidien — Coran & Dhikr en un seul endroit.
                </p>
            </div>
        </div>
    </div>

    <!-- Hub Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Quran -->
        <a href="{{ route('quran.index') }}" class="hub-card indie-panel p-12 text-center">
            <span class="hub-icon">📖</span>
            <h2 class="text-2xl font-black mb-3" style="color: var(--text-main);">Saint Coran</h2>
            <p class="text-sm leading-relaxed font-medium" style="color: var(--text-muted);">
                Lisez les 114 sourates avec texte arabe,<br>traduction française et récitation audio.
            </p>
            <div class="mt-6 inline-block px-5 py-2 rounded-full text-sm font-black"
                 style="background: var(--accent-soft); color: var(--accent);">
                Ouvrir le Coran →
            </div>
        </a>

        <!-- Dhikr -->
        <a href="{{ route('dhikr.index') }}" class="hub-card indie-panel p-12 text-center">
            <span class="hub-icon">📿</span>
            <h2 class="text-2xl font-black mb-3" style="color: var(--text-main);">Adhkar & Dhikr</h2>
            <p class="text-sm leading-relaxed font-medium" style="color: var(--text-muted);">
                Adhkar du matin, du soir et du coucher<br>avec un compteur de répétitions pour chaque dhikr.
            </p>
            <div class="mt-6 inline-block px-5 py-2 rounded-full text-sm font-black"
                 style="background: var(--accent-soft); color: var(--accent);">
                Ouvrir les Adhkar →
            </div>
        </a>

    </div>
    </div>
</div>
</body>
</html>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adhkar — MyLife</title>
    @include('partials.indie-theme')
    <style>
        .category-card {
            text-decoration: none;
            border: 1.5px solid var(--line) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent) !important;
        }
        .category-icon {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 0.75rem;
            transition: transform 0.2s ease;
        }
        .category-card:hover .category-icon { transform: scale(1.15); }
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
                <p class="indie-kicker mb-1">Adhkar & Dhikr</p>
                <h1 class="indie-title text-4xl font-black">📿 Adhkar</h1>
                <p class="mt-2 font-medium" style="color: var(--text-muted);">
                    Choisissez une catégorie pour commencer votre séance de dhikr.
                </p>
            </div>
        </div>
    </div>

    @php
        $categoryIcons = [
            'morning' => ['icon' => '🌅', 'label' => 'Adhkar du matin'],
            'evening' => ['icon' => '🌆', 'label' => 'Adhkar du soir'],
            'sleep'   => ['icon' => '🌙', 'label' => 'Adhkar du coucher'],
            'prayer'  => ['icon' => '🕌', 'label' => 'Adhkar de la prière'],
            'general' => ['icon' => '📿', 'label' => 'Adhkar généraux'],
        ];
    @endphp

    <!-- Category Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($categories as $cat)
            @php
                $meta = $categoryIcons[$cat->category] ?? ['icon' => '📿', 'label' => 'Adhkar ' . ucfirst($cat->category)];
            @endphp
            <a href="{{ route('dhikr.show', $cat->category) }}" class="category-card indie-panel p-8 text-center">
                <span class="category-icon">{{ $meta['icon'] }}</span>
                <h3 class="text-lg font-black mb-1" style="color: var(--text-main);">{{ $meta['label'] }}</h3>
                <p class="text-xs font-semibold" style="color: var(--text-muted);">Toucher pour commencer →</p>
            </a>
        @endforeach
    </div>

    </div>
</div>
</body>
</html>

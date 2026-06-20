<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $categoryLabels = [
            'morning' => 'Adhkar du matin',
            'evening' => 'Adhkar du soir',
            'sleep'   => 'Adhkar du coucher',
            'prayer'  => 'Adhkar de la prière',
            'general' => 'Adhkar généraux',
        ];
        $categoryLabel = $categoryLabels[$category] ?? ('Adhkar ' . ucfirst($category));
    @endphp
    <title>{{ $categoryLabel }} — MyLife</title>
    @include('partials.indie-theme')
    <style>
        .dhikr-card {
            border: 1.5px solid var(--line);
            transition: box-shadow 0.2s ease;
        }
        .dhikr-card:hover { box-shadow: var(--shadow-hover); }

        .arabic-text {
            font-size: 1.65rem;
            line-height: 2.1;
            direction: rtl;
            text-align: right;
            color: var(--text-main);
            font-family: 'Amiri', 'Scheherazade New', serif;
        }
        .translation-text {
            font-size: 0.95rem;
            line-height: 1.75;
            font-style: italic;
            color: var(--text-muted);
        }
        .counter-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.25rem;
            border-radius: 999px;
            font-weight: 800;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.18s ease;
            background: var(--accent-soft);
            color: var(--accent);
            border: 1.5px solid var(--accent);
        }
        .counter-btn:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }
        .counter-btn.done {
            background: rgba(16,185,129,0.12);
            color: #059669;
            border-color: #059669;
            pointer-events: none;
        }
        .counter-track {
            height: 5px;
            border-radius: 999px;
            background: rgba(255,255,255,0.55);
            border: 1px solid var(--line);
            overflow: hidden;
            margin-top: 0.5rem;
        }
        .counter-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            transition: width 0.3s ease;
        }
        audio {
            width: 100%;
            height: 36px;
            border-radius: 999px;
            margin-top: 0.75rem;
        }
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
                <p class="indie-kicker mb-1">Adhkar</p>
                <h1 class="indie-title text-3xl font-black">
                    @php
                        $icons = ['morning' => '🌅', 'evening' => '🌆', 'sleep' => '🌙', 'prayer' => '🕌', 'general' => '📿'];
                        echo ($icons[$category] ?? '📿') . ' ' . $categoryLabel;
                    @endphp
                </h1>
                <p class="mt-1 text-sm font-medium" style="color: var(--text-muted);">
                    {{ $adhkar->count() }} dhikr — touchez le compteur pour chacun
                </p>
            </div>
            <a href="{{ route('dhikr.index') }}" class="secondary-button px-5 py-2.5 text-sm font-bold whitespace-nowrap">
                ← Tous les Adhkar
            </a>
        </div>
    </div>

    <!-- Dhikr Cards -->
    @foreach($adhkar as $index => $item)
        <div class="dhikr-card indie-panel p-6">

            <!-- Number -->
            <div class="flex justify-between items-center mb-4">
                <span class="text-xs font-black px-3 py-1 rounded-full"
                      style="background: var(--accent-soft); color: var(--accent);">
                    {{ $index + 1 }} / {{ $adhkar->count() }}
                </span>
                @if($item->repeat > 1)
                    <span class="text-xs font-semibold" style="color: var(--text-muted);">
                        × {{ $item->repeat }}
                    </span>
                @endif
            </div>

            <!-- Arabic -->
            <p class="arabic-text mb-4">{{ $item->text }}</p>

            <!-- Translation -->
            @if($item->translation)
                <p class="translation-text mb-5">{{ $item->translation }}</p>
            @endif

            <!-- Counter -->
            <div>
                <button class="counter-btn"
                        onclick="dhikrCount(this, {{ $item->repeat }})"
                        data-count="0"
                        data-max="{{ $item->repeat }}">
                    <span class="btn-label">× {{ $item->repeat }} restants</span>
                </button>
                @if($item->repeat > 1)
                    <div class="counter-track">
                        <div class="counter-fill" style="width: 0%;"></div>
                    </div>
                @endif
            </div>

            <!-- Audio -->
            @if($item->audio)
                <audio controls>
                    <source src="{{ $item->audio }}">
                </audio>
            @endif

        </div>
    @endforeach

</div>

<script>
function dhikrCount(btn, max) {
    let current = parseInt(btn.dataset.count) + 1;
    btn.dataset.count = current;

    const label = btn.querySelector('.btn-label');
    const remaining = max - current;
    const track = btn.closest('div').querySelector('.counter-fill');

    if (track) {
        track.style.width = ((current / max) * 100) + '%';
    }

    if (current >= max) {
        label.textContent = '✅ Terminé !';
        btn.classList.add('done');
    } else {
        label.textContent = '× ' + remaining + ' restants';
    }
}
</script>
</body>
</html>
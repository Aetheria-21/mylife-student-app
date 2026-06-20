<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Choix du genre - MyLife </title>
    @include('partials.indie-theme')
    <style>
        :root {
            --glass-bg-male: rgba(255, 255, 255, 0.22);
            --glass-border-male: rgba(255, 255, 255, 0.38);
            --shadow-male: 0 25px 60px rgba(14, 165, 233, 0.26);
            --glow-male: 0 20px 45px rgba(56, 189, 248, 0.28);
            --gradient-male: linear-gradient(135deg, #86d7ff 0%, #6ebef7 45%, #bde7f8 100%);

            --glass-bg-female: rgba(255, 255, 255, 0.2);
            --glass-border-female: rgba(255, 255, 255, 0.42);
            --shadow-female: 0 25px 60px rgba(236, 72, 153, 0.24);
            --glow-female: 0 20px 45px rgba(244, 114, 182, 0.28);
            --gradient-female: linear-gradient(135deg, #ffd1e6 0%, #ff9cca 45%, #ffc2df 100%);
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            min-height: 100vh;
            color: #10233d;
            background: var(--page-gradient);
            transition: background 0.35s ease, color 0.35s ease;
            position: relative;
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: fixed;
            inset: auto;
            width: 28rem;
            height: 28rem;
            border-radius: 9999px;
            filter: blur(70px);
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
        }

        body::before {
            top: -6rem;
            left: -4rem;
            background: rgba(255, 255, 255, 0.55);
        }

        body::after {
            right: -6rem;
            bottom: -8rem;
            background: rgba(255, 255, 255, 0.35);
        }

        .theme-male {
            --page-gradient: var(--gradient-male);
            --glass-bg: var(--glass-bg-male);
            --glass-border: var(--glass-border-male);
            --shadow-main: var(--shadow-male);
            --glow-main: var(--glow-male);
            --accent: #0284c7;
            --accent-strong: #0369a1;
            --accent-soft: rgba(14, 165, 233, 0.14);
        }

        .theme-female {
            --page-gradient: var(--gradient-female);
            --glass-bg: var(--glass-bg-female);
            --glass-border: var(--glass-border-female);
            --shadow-main: var(--shadow-female);
            --glow-main: var(--glow-female);
            --accent: #db2777;
            --accent-strong: #be185d;
            --accent-soft: rgba(236, 72, 153, 0.14);
        }

        .glass-panel {
            position: relative;
            z-index: 1;
            background: var(--glass-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--shadow-main);
            border-radius: 2rem;
        }

        .theme-card {
            position: relative;
            overflow: hidden;
            text-align: left;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 1.75rem;
            padding: 2rem;
            min-height: 20rem;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease, background 0.25s ease;
        }

        .theme-card:hover,
        .theme-card.is-selected {
            transform: translateY(-6px);
            box-shadow: var(--glow-main);
            border-color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.68);
        }

        .theme-card.is-selected {
            outline: 3px solid var(--accent-soft);
        }

        .theme-badge {
            background: var(--accent-soft);
            color: var(--accent-strong);
            border: 1px solid rgba(255, 255, 255, 0.65);
        }

        .theme-icon {
            width: 5.5rem;
            height: 5.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 1.5rem;
            font-size: 2rem;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5), 0 18px 35px rgba(15, 23, 42, 0.18);
        }

        .male-icon {
            background: linear-gradient(135deg, #38bdf8 0%, #3b82f6 100%);
        }

        .female-icon {
            background: linear-gradient(135deg, #f472b6 0%, #ec4899 100%);
        }

        .accent-button,
        .secondary-button {
            border-radius: 9999px;
            padding: 0.95rem 1.5rem;
            font-weight: 800;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .accent-button {
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-strong) 100%);
            color: white;
            box-shadow: 0 16px 30px color-mix(in srgb, var(--accent) 35%, transparent);
        }

        .accent-button:hover,
        .secondary-button:hover {
            transform: translateY(-2px);
        }

        .secondary-button {
            background: rgba(255, 255, 255, 0.66);
            color: #1f2937;
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .helper-text {
            background: rgba(255, 255, 255, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.55);
            color: #334155;
        }

        @media (prefers-reduced-motion: no-preference) {
            .theme-card,
            .accent-button,
            .secondary-button,
            body {
                transition-duration: 0.35s;
            }
        }
    </style>
</head>
<body class="indie-page theme-{{ ($user->gender ?? 'male') === 'female' ? 'female' : 'male' }} min-h-screen flex items-center justify-center p-4 md:p-8">
    @php
        $emotionMeta = [
            'happy' => ['label' => 'Heureux', 'emoji' => '😊'],
            'sad' => ['label' => 'Triste', 'emoji' => '😢'],
            'angry' => ['label' => 'En colère', 'emoji' => '😠'],
            'neutral' => ['label' => 'Neutre', 'emoji' => '😐'],
        ];
    @endphp
    <div class="max-w-5xl w-full space-y-8 relative z-10">
        <div class="glass-panel p-8 md:p-12 text-center">
            <p class="inline-flex items-center gap-2 px-4 py-2 rounded-full theme-badge text-sm font-bold mb-5">
                ✨ Personnalisez votre tableau de bord
            </p>
            <h1 class="text-4xl md:text-6xl font-black mb-5 text-slate-900">
                Bienvenue {{ $user->name ?? 'à vous' }}
            </h1>
            <p class="text-lg md:text-xl text-slate-700 max-w-3xl mx-auto leading-relaxed">
                Choisissez le thème qui correspond à votre style. Votre choix de genre sera sauvegardé et réutilisé à l'ouverture de votre tableau de bord.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            <button type="button" id="maleCard" class="theme-card" onclick="selectTheme('male')">
                <div class="absolute inset-0 bg-gradient-to-br from-sky-300/10 to-cyan-300/10 pointer-events-none"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="theme-icon male-icon text-white">♂️</div>
                        <span id="maleBadge" class="theme-badge px-3 py-1 rounded-full text-sm font-bold">Aperçu</span>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 mb-4">Thème Masculin</h2>
                    <div class="space-y-3 text-slate-700 font-semibold">
                        <p>🌊  bleu ciel theme </p>
                    </div>
                    <p class="mt-auto pt-6 text-slate-600">Un style Frutiger Aero plus froid avec un contraste net et des touches énergiques.</p>
                </div>
            </button>

            <button type="button" id="femaleCard" class="theme-card" onclick="selectTheme('female')">
                <div class="absolute inset-0 bg-gradient-to-br from-pink-300/10 to-rose-300/10 pointer-events-none"></div>
                <div class="relative z-10 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-6">
                        <div class="theme-icon female-icon text-white">♀️</div>
                        <span id="femaleBadge" class="theme-badge px-3 py-1 rounded-full text-sm font-bold">Aperçu</span>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 mb-4">Thème Féminin</h2>
                    <div class="space-y-3 text-slate-700 font-semibold">
                        <p>🌸 rose bébé doux</p>
                    </div>
                    <p class="mt-auto pt-6 text-slate-600">Un look premium plus doux avec des reflets lumineux et des tons féminins fluides.</p>
                </div>
            </button>
        </div>

        <div class="glass-panel p-8 md:p-10 space-y-6">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-3">Écrivez vos propres conseils d'humeur</h2>
                <p class="text-slate-700 text-base md:text-lg">
                    Ajoutez 5 conseils personnels pour chaque émotion. Ces conseils seront enregistrés sur votre compte et affichés plus tard dans le suivi d'humeur de la page d'accueil.
                </p>
            </div>

            <div class="grid xl:grid-cols-2 gap-6">
                @foreach ($emotionMeta as $emotion => $meta)
                    <div class="rounded-[1.75rem] bg-white/50 border border-white/60 shadow-lg p-6">
                        <h3 class="text-2xl font-black text-slate-900 mb-4 flex items-center gap-3">
                            <span class="text-3xl">{{ $meta['emoji'] }}</span>
                            Conseils {{ strtolower($meta['label']) }}
                        </h3>

                        <div class="space-y-3">
                            @for ($i = 0; $i < 5; $i++)
                                <label class="block">
                                    <span class="block text-sm font-bold text-slate-700 mb-2">Conseil {{ $i + 1 }}</span>
                                    <textarea
                                        rows="2"
                                        data-emotion="{{ $emotion }}"
                                        data-tip-index="{{ $i + 1 }}"
                                        class="w-full rounded-2xl border border-white/70 bg-white/75 px-4 py-3 text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[var(--accent)]/35"
                                        placeholder="Écrivez votre conseil « {{ strtolower($meta['label']) }} » n°{{ $i + 1 }} ici..."
                                    >{{ $emotionTips[$emotion][$i] ?? '' }}</textarea>
                                </label>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="glass-panel p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-5">
            <div class="helper-text rounded-2xl px-5 py-4 w-full md:w-auto">
                <p class="font-bold text-base md:text-lg">Thème sélectionné : <span id="selectedThemeText">Masculin</span></p>
                <p class="text-sm md:text-base text-slate-600">Ce choix sera enregistré sur votre compte et utilisé sur la page d'accueil.</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <button type="button" id="saveThemeButton" onclick="saveTheme()" class="accent-button w-full sm:w-auto">
                    Enregistrer et continuer
                </button>
                <button type="button" onclick="skipTheme()" class="secondary-button w-full sm:w-auto">
                    Passer pour l'instant
                </button>
            </div>
        </div>
    </div>

    <script>
        let selectedGender = @json($user->gender ?? 'male');
        const emotionKeys = ['happy', 'sad', 'angry', 'neutral'];

        function applyPreview(gender) {
            const isFemale = gender === 'female';

            document.body.classList.remove('theme-male', 'theme-female');
            document.body.classList.add(isFemale ? 'theme-female' : 'theme-male');

            document.getElementById('maleCard').classList.toggle('is-selected', !isFemale);
            document.getElementById('femaleCard').classList.toggle('is-selected', isFemale);

            document.getElementById('maleBadge').textContent = isFemale ? 'Aperçu' : 'Sélectionné';
            document.getElementById('femaleBadge').textContent = isFemale ? 'Sélectionné' : 'Aperçu';
            document.getElementById('selectedThemeText').textContent = isFemale ? 'Féminin' : 'Masculin';
            document.getElementById('saveThemeButton').textContent = `Enregistrer le thème ${isFemale ? 'féminin' : 'masculin'} et continuer`;
        }

        function selectTheme(gender) {
            selectedGender = gender;
            applyPreview(selectedGender);
        }

        function collectEmotionTips() {
            const emotionTips = {};

            emotionKeys.forEach((emotion) => {
                emotionTips[emotion] = Array.from(document.querySelectorAll(`[data-emotion="${emotion}"]`))
                    .sort((first, second) => Number(first.dataset.tipIndex) - Number(second.dataset.tipIndex))
                    .map((field) => field.value.trim());
            });

            return emotionTips;
        }

        function validateEmotionTips(emotionTips) {
            for (const emotion of emotionKeys) {
                const tips = emotionTips[emotion] || [];

                if (tips.length !== 5 || tips.some((tip) => tip.length === 0)) {
                    return `Veuillez écrire 5 conseils pour l'émotion « ${emotion} ».`;
                }
            }

            return null;
        }

        async function saveTheme() {
            const button = document.getElementById('saveThemeButton');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const emotionTips = collectEmotionTips();
            const validationError = validateEmotionTips(emotionTips);

            if (validationError) {
                alert(validationError);
                return;
            }

            button.disabled = true;
            button.textContent = 'Enregistrement...';

            try {
                const response = await fetch('{{ route('set.gender') }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        gender: selectedGender,
                        emotion_tips: emotionTips,
                    })
                });

                if (!response.ok) {
                    throw new Error('Impossible d\'enregistrer votre thème pour le moment.');
                }

                window.location.href = '{{ route('home') }}';
            } catch (error) {
                alert(error.message || 'Erreur réseau. Veuillez réessayer.');
                button.disabled = false;
                applyPreview(selectedGender);
            }
        }

        function skipTheme() {
            window.location.href = '{{ route('home') }}';
        }

        document.addEventListener('DOMContentLoaded', function () {
            applyPreview(selectedGender);
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>🎯 Mes Passions — MyLife</title>
    @include('partials.indie-theme')
</head>
<body class="indie-page theme-{{ (auth()->user()->gender ?? 'male') === 'female' ? 'female' : 'male' }}">
<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-6">

    {{-- ── Header ──────────────────────────────── --}}
    <div class="indie-panel p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="indie-kicker mb-1">Lifestyle</p>
                <h1 class="indie-title text-4xl font-black">🎯 Mes Passions</h1>
                <p class="mt-1" style="color:var(--text-muted); font-style:italic;">Suis tes hobbies, ton niveau et ta progression.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="indie-soft-panel px-5 py-3 text-center">
                    <p class="text-xs font-bold" style="color:var(--text-muted);">Total</p>
                    <p class="text-2xl font-black" style="color:var(--accent);">{{ $hobbies->count() }}</p>
                </div>
                <div class="indie-soft-panel px-5 py-3 text-center">
                    <p class="text-xs font-bold" style="color:var(--text-muted);">Actives</p>
                    <p class="text-2xl font-black text-emerald-600">{{ $hobbies->where('status','active')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Flash ───────────────────────────────── --}}
    @if(session('success'))
        <div class="indie-soft-panel px-6 py-4 text-emerald-700 font-bold">✅ {{ session('success') }}</div>
    @endif

    {{-- ── Add form + Quick suggestions ──────── --}}
    <div class="grid xl:grid-cols-[1.2fr_1fr] gap-6">

        {{-- Form --}}
        <div class="indie-panel p-6">
            <h2 class="text-xl font-black mb-5" style="color:var(--text-heading);">➕ Nouvelle Passion</h2>
            <form action="{{ route('hobbies.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold mb-1" style="color:var(--accent-text);">🎯 Nom</label>
                    <input type="text" name="name" id="passionInput" required autocomplete="off"
                           class="indie-input w-full px-4 py-3"
                           placeholder="Piano 🎹, Football ⚽, Coding 💻…">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color:var(--accent-text);">📍 Niveau</label>
                        <select name="level" class="indie-input w-full px-4 py-3">
                            <option value="">Choisir…</option>
                            <option value="🎯 Débutant">🎯 Débutant</option>
                            <option value="⭐ Intermédiaire">⭐ Intermédiaire</option>
                            <option value="🏆 Expert">🏆 Expert</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color:var(--accent-text);">⏰ Fréquence</label>
                        <select name="frequency" class="indie-input w-full px-4 py-3">
                            <option value="">Choisir…</option>
                            <option value="📅 Quotidien">📅 Quotidien</option>
                            <option value="📆 Hebdomadaire">📆 Hebdo</option>
                            <option value="🗓️ Mensuel">🗓️ Mensuel</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="indie-button w-full py-3 font-black text-base">
                    🚀 Ajouter la Passion
                </button>
            </form>

            {{-- Quick-add chips --}}
            <div class="mt-5 pt-5" style="border-top:1px solid var(--line);">
                <p class="section-title mb-3">Suggestions express</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Piano 🎹','Football ⚽','Coding 💻','Cuisine 👨‍🍳','Lecture 📚','Yoga 🧘'] as $s)
                    <button type="button" onclick="quickAdd('{{ $s }}')"
                            class="secondary-button px-3 py-1.5 text-xs font-bold">{{ $s }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Suggestion categories --}}
        <div class="indie-panel p-6 space-y-4 overflow-y-auto" style="max-height:520px;">
            <p class="section-title">Idées par catégorie</p>
            @foreach([
                ['📚','Livres','book-suggestions'],
                ['🎵','Musique','music-suggestions'],
                ['⚽','Sports','sport-suggestions'],
                ['💻','Tech','tech-suggestions'],
                ['👨‍🍳','Créativité','creative-suggestions'],
                ['✈️','Aventures','travel-suggestions'],
            ] as [$icon,$label,$id])
            <div>
                <p class="text-xs font-bold mb-2" style="color:var(--text-muted);">{{ $icon }} {{ $label }}</p>
                <div id="{{ $id }}" class="grid grid-cols-3 gap-2"></div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── Hobby cards ─────────────────────────── --}}
    <div class="indie-panel p-6">
        <h2 class="text-xl font-black mb-5" style="color:var(--text-heading);">🗂️ Tes Passions</h2>

        @if($hobbies->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($hobbies as $hobby)
            <div class="indie-soft-panel p-5 group hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-3">
                    <span class="text-3xl group-hover:scale-110 transition-transform inline-block">{{ $hobby->emoji ?? '🎯' }}</span>
                    <div class="flex gap-2">
                        <form action="{{ route('hobbies.toggle', $hobby->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="secondary-button px-3 py-1 text-xs font-bold">
                                {{ $hobby->status == 'active' ? '✅ Active' : '⏸️ Pause' }}
                            </button>
                        </form>
                        <form action="{{ route('hobbies.destroy', $hobby->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Supprimer {{ $hobby->name }} ?')">
                            @csrf @method('DELETE')
                            <button class="secondary-button px-3 py-1 text-xs font-bold text-red-600 border-red-300">🗑️</button>
                        </form>
                    </div>
                </div>

                <h3 class="text-base font-black mb-2" style="color:var(--text-heading);">{{ $hobby->name }}</h3>

                <div class="flex flex-wrap gap-2 mb-3">
                    @if($hobby->level)
                        <span class="indie-pill text-xs">{{ $hobby->level }}</span>
                    @endif
                    @if($hobby->frequency)
                        <span class="indie-pill text-xs">{{ $hobby->frequency }}</span>
                    @endif
                </div>

                @if($hobby->progress)
                <div class="indie-progress mb-2">
                    <div class="indie-progress-bar" style="width:{{ $hobby->progress }}%"></div>
                </div>
                <p class="text-xs font-bold" style="color:var(--text-muted);">{{ $hobby->progress }}% progression</p>
                @endif

                @if($hobby->description)
                <p class="text-xs mt-2 italic" style="color:var(--text-muted);">{{ $hobby->description }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <div class="text-5xl mb-4 opacity-30">🎯</div>
            <p class="font-bold" style="color:var(--text-muted);">Aucune passion ajoutée.</p>
            <p class="text-sm mt-1" style="color:var(--text-muted);">Utilise le formulaire ci-dessus pour commencer !</p>
        </div>
        @endif
    </div>

</div>{{-- /indie-shell --}}

    <!-- SCRIPT COMPLET -->
    <script>
        // Quick Add
        function quickAdd(passion) {
            const input = document.getElementById('passionInput');
            input.value = passion;
            input.focus();
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Effet visuel
            input.style.background = '#fef3ff';
            input.style.borderColor = 'var(--accent)';
            setTimeout(() => {
                input.style.background = '';
                input.style.borderColor = '';
            }, 1000);
        }
        document.addEventListener("DOMContentLoaded", function () {
            const msg = document.getElementById("flash-message");
            if (msg) {
                setTimeout(() => {
                    msg.style.transition = "opacity 0.5s ease";
                    msg.style.opacity = "0";
                    setTimeout(() => msg.remove(), 500);
                }, 3000); // 3 secondes
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
            loadAllSuggestions();
        });

        // Toutes les suggestions
        function loadAllSuggestions() {
            loadBookSuggestions();
            loadMusicSuggestions();
            loadSportSuggestions();
            loadTechSuggestions();
            loadCreativeSuggestions();
            loadTravelSuggestions();
        }

        // 📚 Livres
        function loadBookSuggestions() {
            const books = [
                {title: 'L\'Alchimiste', desc: 'Paulo Coelho', emoji: '📖'},
                {title: 'Atomic Habits', desc: 'James Clear', emoji: '⚛️'},
                {title: 'Clean Code', desc: 'Robert Martin', emoji: '💻'},
                {title: 'Sapiens', desc: 'Yuval Harari', emoji: '🧠'},
                {title: '1984', desc: 'George Orwell', emoji: '🔥'},
                {title: 'Le Petit Prince', desc: 'Saint-Ex', emoji: '⭐'}
            ];
            renderSuggestions(books, 'book-suggestions');
        }

        // 🎵 Musique
        function loadMusicSuggestions() {
            const music = [
                {title: 'Guitare 🎸', desc: 'Acoustique', emoji: '🎸'},
                {title: 'Piano 🎹', desc: 'Classique', emoji: '🎹'},
                {title: 'Batterie 🥁', desc: 'Rock', emoji: '🥁'},
                {title: 'Chant 🎤', desc: 'Pop', emoji: '🎤'},
                {title: 'Saxophone 🎷', desc: 'Jazz', emoji: '🎷'},
                {title: 'Violon 🎻', desc: 'Classique', emoji: '🎻'}
            ];
            renderSuggestions(music, 'music-suggestions');
        }

        // ⚽ Sports
        function loadSportSuggestions() {
            const sports = [
                {title: 'Football ⚽', desc: 'Compétition', emoji: '⚽'},
                {title: 'Course 🏃', desc: 'Endurance', emoji: '🏃‍♂️'},
                {title: 'Yoga 🧘', desc: 'Bien-être', emoji: '🧘‍♂️'},
                {title: 'Boxe 🥊', desc: 'Combat', emoji: '🥊'},
                {title: 'Natation 🏊', desc: 'Cardio', emoji: '🏊‍♂️'},
                {title: 'Vélo 🚴', desc: 'Exploration', emoji: '🚴‍♂️'}
            ];
            renderSuggestions(sports, 'sport-suggestions');
        }

        // 💻 Tech
        function loadTechSuggestions() {
            const tech = [
                {title: 'Coding 💻', desc: 'Python/Laravel', emoji: '💻'},
                {title: 'IA 🤖', desc: 'Machine Learning', emoji: '🤖'},
                {title: 'Blockchain ⛓️', desc: 'Web3', emoji: '⛓️'},
                {title: 'Design 🎨', desc: 'UI/UX Figma', emoji: '🎨'},
                {title: 'Cyberséc 🔒', desc: 'Hacking', emoji: '🔒'},
                {title: 'DevOps ☁️', desc: 'Docker/AWS', emoji: '☁️'}
            ];
            renderSuggestions(tech, 'tech-suggestions');
        }

        // 👨‍🍳 Créativité
        function loadCreativeSuggestions() {
            const creative = [
                {title: 'Cuisine 👨‍🍳', desc: 'Marocaine', emoji: '👨‍🍳'},
                {title: 'Peinture 🎨', desc: 'Aquarelle', emoji: '🎨'},
                {title: 'Photo 📸', desc: 'Portrait', emoji: '📸'},
                {title: 'Jardin 🌱', desc: 'Potager', emoji: '🌱'},
                {title: 'Poterie 🏺', desc: 'Modelage', emoji: '🏺'},
                {title: 'Tricot 🧶', desc: 'Pulls', emoji: '🧶'}
            ];
            renderSuggestions(creative, 'creative-suggestions');
        }

        // ✈️ Voyage
        function loadTravelSuggestions() {
            const travel = [
                {title: 'Maroc 🇲🇦', desc: 'Marrakech', emoji: '🇲🇦'},
                {title: 'Turquie 🇹🇷', desc: 'Istanbul', emoji: '🇹🇷'},
                {title: 'Espagne 🇪🇸', desc: 'Barcelone', emoji: '🇪🇸'},
                {title: 'Égypte 🇪🇬', desc: 'Pyramides', emoji: '🇪🇬'},
                {title: 'Italie 🇮🇹', desc: 'Rome', emoji: '🇮🇹'},
                {title: 'Japon 🇯🇵', desc: 'Tokyo', emoji: '🇯🇵'}
            ];
            renderSuggestions(travel, 'travel-suggestions');
        }

        // Render générique — editorial chip style
        function renderSuggestions(items, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            container.innerHTML = '';
            items.forEach(item => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'secondary-button text-left px-3 py-2 text-xs font-bold truncate';
                btn.title = item.desc;
                btn.textContent = item.emoji + ' ' + item.title;
                btn.onclick = () => quickAdd(item.title);
                container.appendChild(btn);
            });
        }
    </script>

</body>
</html>
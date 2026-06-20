<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des études — MyLife</title>
    @include('partials.indie-theme')
    <style>
        .lesson-card {
            border: 1.5px solid var(--line);
            transition: box-shadow 0.2s ease;
        }
        .lesson-card:hover { box-shadow: var(--shadow-hover); }

        .hw-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            width: 100%;
            text-align: left;
            padding: 0.55rem 0.9rem;
            border-radius: 0.9rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--line);
            background: rgba(255,255,255,0.5);
            color: var(--text-main);
            transition: all 0.18s ease;
        }
        .hw-btn:hover { background: var(--accent-soft); border-color: var(--accent); }
        .hw-btn.done { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.4); color: var(--text-muted); text-decoration: line-through; }

        /* Due date badges */
        .due-badge {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            white-space: nowrap;
        }
        .due-overdue { background: rgba(239,68,68,0.12);  color: #dc2626; border: 1px solid rgba(239,68,68,0.3); }
        .due-today   { background: rgba(245,158,11,0.12); color: #d97706; border: 1px solid rgba(245,158,11,0.3); }
        .due-future  { background: rgba(59,130,246,0.10); color: #2563eb; border: 1px solid rgba(59,130,246,0.3); }
        .remind-badge { font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 999px;
                        background: rgba(139,92,246,0.1); color: #7c3aed; border: 1px solid rgba(139,92,246,0.3); white-space: nowrap; }

        /* Homework add form expand */
        .hw-extra { display: none; }
        .hw-extra.open { display: grid; }

        /* Pomodoro */
        #timer-display {
            font-family: 'Space Grotesk', monospace;
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            color: var(--accent);
            line-height: 1;
        }
    </style>
</head>
<body class="indie-page min-h-screen {{ (auth()->user()->gender ?? 'male') === 'female' ? 'theme-female' : 'theme-male' }}">
<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-8">

    <!-- Header -->
    <div class="indie-panel p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="indie-kicker mb-1">Études</p>
                <h1 class="indie-title text-3xl font-black">📚 Gestion des études</h1>
                <p class="mt-2 font-medium" style="color: var(--text-muted);">
                    Organisez vos leçons, suivez vos devoirs et restez concentré avec Pomodoro.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left col: Add lesson + lessons list -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Add Lesson Form -->
            <div class="indie-panel p-6">
                <h2 class="text-lg font-black mb-4" style="color: var(--text-main);">➕ Ajouter une leçon</h2>
                <form method="POST" action="{{ route('study.lesson.store') }}" class="space-y-3">
                    @csrf
                    <input name="title" required placeholder="Titre de la leçon"
                           class="glass-input w-full px-4 py-3 rounded-2xl text-sm" style="color: var(--text-main);">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input name="date" type="date"
                               class="glass-input w-full px-4 py-3 rounded-2xl text-sm" style="color: var(--text-main);">
                        <textarea name="description" rows="1" placeholder="Notes (facultatif)"
                                  class="glass-input w-full px-4 py-3 rounded-2xl text-sm resize-none" style="color: var(--text-main);"></textarea>
                    </div>
                    <button type="submit" class="glass-button px-6 py-2.5 text-sm font-black text-white">
                        + Ajouter la leçon
                    </button>
                </form>
            </div>

            <!-- Lessons List -->
            @forelse($lessons as $lesson)
                <div class="lesson-card indie-panel p-6">
                    <!-- Lesson header -->
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <h3 class="text-lg font-black" style="color: var(--text-main);">{{ $lesson->title }}</h3>
                            @if($lesson->date)
                                <p class="text-xs font-semibold mt-0.5" style="color: var(--text-muted);">
                                    📅 {{ \Carbon\Carbon::parse($lesson->date)->locale('fr')->isoFormat('ddd D MMM YYYY') }}
                                </p>
                            @endif
                            @if($lesson->description)
                                <p class="text-sm mt-1" style="color: var(--text-muted);">{{ $lesson->description }}</p>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('study.lesson.delete', $lesson->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-full transition"
                                    style="background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.3);"
                                    onclick="return confirm('Supprimer cette leçon et tous ses devoirs ?')">
                                🗑 Supprimer
                            </button>
                        </form>
                    </div>

                    <!-- Homework list -->
                    @if($lesson->homeworks->count())
                        <div class="space-y-2 mb-4">
                            @foreach($lesson->homeworks as $hw)
                                @php
                                    $today = \Carbon\Carbon::today();
                                    $dueClass = '';
                                    $dueLabel = '';
                                    if ($hw->due_date && !$hw->is_done) {
                                        if ($hw->due_date->lt($today))       { $dueClass = 'due-overdue'; $dueLabel = '⚠️ En retard · ' . $hw->due_date->locale('fr')->isoFormat('D MMM'); }
                                        elseif ($hw->due_date->eq($today))   { $dueClass = 'due-today';   $dueLabel = '📌 À rendre aujourd\'hui'; }
                                        else                                  { $dueClass = 'due-future';  $dueLabel = '📅 ' . $hw->due_date->locale('fr')->isoFormat('D MMM'); }
                                    }
                                @endphp
                                <div>
                                    <form method="POST" action="{{ route('study.homework.toggle', $hw->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="hw-btn {{ $hw->is_done ? 'done' : '' }}">
                                            <span>{{ $hw->is_done ? '✅' : '⬜' }}</span>
                                            <span class="flex-1">{{ $hw->title }}</span>
                                            @if($dueLabel)
                                                <span class="due-badge {{ $dueClass }}">{{ $dueLabel }}</span>
                                            @endif
                                            @if($hw->remind_at && !$hw->is_done)
                                                <span class="remind-badge">🔔 {{ $hw->remind_at->locale('fr')->isoFormat('D MMM, HH:mm') }}</span>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Add Homework Form -->
                    <form method="POST" action="{{ route('study.homework.store') }}" class="space-y-2 mt-3">
                        @csrf
                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                        <div class="flex gap-2">
                            <input name="title" required placeholder="Ajouter un devoir…"
                                   class="glass-input flex-1 px-3 py-2 rounded-xl text-sm" style="color: var(--text-main);">
                            <button type="button"
                                    onclick="this.closest('form').querySelector('.hw-extra').classList.toggle('open')"
                                    class="secondary-button px-3 py-2 text-xs font-bold whitespace-nowrap">
                                📅 Date
                            </button>
                            <button type="submit" class="glass-button px-4 py-2 text-xs font-black text-white whitespace-nowrap">
                                + Ajouter
                            </button>
                        </div>
                        <div class="hw-extra grid-cols-1 sm:grid-cols-2 gap-2">
                            @php($minDue = \Carbon\Carbon::today()->format('Y-m-d'))
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--text-muted);">📅 Date d'échéance</label>
                                {{-- Non cliquable / non sélectionnable: min = aujourd'hui --}}
                                <input name="due_date" type="date" min="{{ $minDue }}"
                                       class="glass-input w-full px-3 py-2 rounded-xl text-sm" style="color: var(--text-main);">
                            </div>
                            @php($minRemind = \Carbon\Carbon::now()->format('Y-m-d\TH:i'))
                            <div>
                                <label class="block text-xs font-bold mb-1" style="color: var(--text-muted);">🔔 Me rappeler à</label>
                                {{-- Non cliquable / non sélectionnable: min = maintenant --}}
                                <input name="remind_at" type="datetime-local" min="{{ $minRemind }}"
                                       class="glass-input w-full px-3 py-2 rounded-xl text-sm" style="color: var(--text-main);">
                            </div>
                        </div>
                    </form>
                </div>
            @empty
                <div class="indie-panel p-8 text-center" style="color: var(--text-muted);">
                    <p class="text-3xl mb-3">📖</p>
                    <p class="font-semibold">Aucune leçon pour l'instant. Ajoutez la première ci-dessus !</p>
                </div>
            @endforelse
        </div>

        <!-- Right col: Pomodoro Timer -->
        <div class="indie-panel p-6 text-center self-start sticky top-8">
            <p class="indie-kicker mb-1">Outil de concentration</p>
            <h2 class="text-lg font-black mb-5" style="color: var(--text-main);">⏱️ Pomodoro</h2>

            <!-- Timer display without circle -->
            <div class="mb-5">
                <div id="timer-display">25:00</div>
                <p id="timer-label" class="text-xs font-bold mt-1" style="color: var(--text-muted);">Concentration</p>
                <p id="pomodoro-count" class="text-xs font-bold mt-1" style="color: var(--accent);">🍅 Pomodoro n°1 / 4</p>
            </div>

            <!-- Mode selector -->
            <div class="flex gap-2 justify-center mb-5 flex-wrap">
                <button onclick="setMode(25,'Concentration')" id="mode-focus"
                        class="px-3 py-1.5 rounded-full text-xs font-black transition"
                        style="background: var(--accent-soft); color: var(--accent);">25 min</button>
                <button onclick="setMode(5,'Courte pause')" id="mode-short"
                        class="px-3 py-1.5 rounded-full text-xs font-bold transition secondary-button">5 min</button>
                <button onclick="setMode(15,'Longue pause')" id="mode-long"
                        class="px-3 py-1.5 rounded-full text-xs font-bold transition secondary-button">15 min</button>
            </div>

            <div class="flex gap-2 justify-center flex-wrap">
                <button onclick="togglePomodoro()" id="start-btn" class="glass-button px-5 py-2 font-black text-white text-sm">
                    ▶ Démarrer
                </button>
                <button onclick="resetPomodoro()" class="secondary-button px-4 py-2 font-bold text-sm">
                    🔄 Réinitialiser
                </button>
            </div>
        </div>

    </div>
    </div>
</div>

<script>
let totalSeconds = 25 * 60;
let remaining = totalSeconds;
let interval = null;
let running = false;

function updateDisplay() {
    const min = String(Math.floor(remaining / 60)).padStart(2, '0');
    const sec = String(remaining % 60).padStart(2, '0');
    document.getElementById('timer-display').textContent = `${min}:${sec}`;
}

function setMode(minutes, label) {
    resetPomodoro();
    totalSeconds = minutes * 60;
    remaining = totalSeconds;
    document.getElementById('timer-label').textContent = label;
    updateDisplay();
}

function togglePomodoro() {
    if (running) {
        clearInterval(interval);
        interval = null;
        running = false;
        document.getElementById('start-btn').textContent = '▶ Reprendre';
    } else {
        running = true;
        document.getElementById('start-btn').textContent = '⏸ Pause';
        interval = setInterval(() => {
            remaining--;
            updateDisplay();
            if (remaining <= 0) {
                clearInterval(interval);
                running = false;
                document.getElementById('start-btn').textContent = '▶ Démarrer';
                document.getElementById('timer-display').textContent = '00:00';
                alert('🎉 Session terminée ! Faites une pause.');
            }
        }, 1000);
    }
}

function resetPomodoro() {
    clearInterval(interval);
    interval = null;
    running = false;
    remaining = totalSeconds;
    document.getElementById('start-btn').textContent = '▶ Démarrer';
    updateDisplay();
}

updateDisplay();
</script>

<script>
// ── Homework Reminder Engine (Browser Notifications) ─────────────────────────
const reminders = @json($reminders);

const fired = new Set();// track already-shown notifications

function checkReminders() {
    if (!reminders.length) return;
    const now = new Date();

    reminders.forEach(hw => {
        if (fired.has(hw.id) || !hw.remind_at) return;
        const remindTime = new Date(hw.remind_at);
        const diffMs = remindTime - now;

        // Fire uniquement si le reminder est "aujourd'hui" (ou futur).
        // Objectif: ne pas afficher/blur/traîter les jours passés.
        const remindDate = new Date(remindTime);
        const today = new Date();
        today.setHours(0,0,0,0);
        remindDate.setHours(0,0,0,0);
        if (diffMs >= -60000 && diffMs <= 60000 && remindDate >= today) {
            fired.add(hw.id);

            const dueText = hw.due_date ? ` — À rendre le ${hw.due_date}` : '';

            if (Notification.permission === 'granted') {

                new Notification('📚 Rappel de devoir', {
                    body: hw.title + dueText,
                    icon: '/favicon.ico',
                    tag:  'hw-' + hw.id,
                });
            } else {
                alert('🔔 Rappel : ' + hw.title + dueText);
            }
        }
    });
}

// Request notification permission, then start checking every 30 seconds
document.addEventListener('DOMContentLoaded', () => {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
    if (reminders.length) {
        checkReminders();
        setInterval(checkReminders, 30000);
    }
});
</script>
</body>
</html>

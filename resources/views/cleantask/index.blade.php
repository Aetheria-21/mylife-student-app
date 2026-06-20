<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Routine de ménage — MyLife</title>
    @include('partials.indie-theme')
</head>
<body class="indie-page theme-{{ (auth()->user()->gender ?? 'male') === 'female' ? 'female' : 'male' }}">

<div class="page-layout" style="max-width:1000px;margin:0 auto;padding:0 1rem 2rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-5">

    <!-- Header -->
    <div class="glass-card p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <p class="indie-kicker">Routine quotidienne</p>
                <h1 class="indie-title text-2xl font-black">🧼 Routine de ménage</h1>
                <p class="mt-1 text-sm text-[var(--text-muted)]">Vos tâches de ménage récurrentes et celles dues aujourd'hui.</p>
            </div>
        </div>
    </div>

    <!-- 🎡 Spinning Wheel Section -->
    <div class="glass-card p-5 text-center">
        <h2 class="text-xl font-black text-[var(--accent)] mb-4">🎡 Roue des tâches</h2>

        <div class="relative w-44 h-44 mx-auto mb-4">
            <div id="wheel"
                 class="w-full h-full rounded-full border-[6px] border-white shadow-lg transition-transform duration-[3000ms] ease-out"
                 style="background: conic-gradient(#60a5fa, #34d399, #fbbf24, #f472b6, #a78bfa);">
            </div>
            <div class="absolute top-[-8px] left-1/2 -translate-x-1/2 text-2xl">🔻</div>
        </div>

        <button onclick="spinWheel()" class="glass-button px-5 py-2 text-sm">Tourner 🎯</button>
        <p id="wheelResult" class="text-lg font-bold mt-4 text-[var(--accent)]"></p>
    </div>

    <!-- ✅ Task List Section -->
    <div class="glass-card p-4">
        <h2 class="text-lg font-black text-[var(--accent)] mb-3">📝 Tâches</h2>

        @forelse($tasks as $task)
        <div class="p-2 mb-1.5 bg-white/40 rounded-lg flex items-center gap-2">
            <input type="checkbox"
                   class="w-4 h-4 accent-[var(--accent)] cursor-pointer"
                   onchange="toggleTask({{ $task->id }}, this)"
                   {{ $task->status === 'completed' ? 'checked' : '' }}>
            <span class="text-sm font-semibold {{ $task->status === 'completed' ? 'line-through text-gray-400' : 'text-[var(--text-main)]' }}">
                {{ $task->title }}
            </span>
        </div>
        @empty
        <p class="text-sm text-[var(--text-muted)] font-semibold text-center py-4">
            Aucune tâche de ménage pour aujourd'hui. Ajoutez des tâches récurrentes ou dues aujourd'hui via la
            <a href="{{ route('tasks.index') }}" class="indie-link">Gestion des tâches</a>.
        </p>
        @endforelse
    </div>

    <!-- 📊 Progress Bar Section -->
    @php
        $total     = $tasks->count();
        $completed = $tasks->where('status', 'completed')->count();
        $percent   = $total ? round(($completed / $total) * 100) : 0;
    @endphp

    <div class="glass-card p-4">
        <h3 class="text-base font-black text-[var(--accent)] mb-3">📊 Progression</h3>

        <div class="flex justify-between mb-1.5 text-sm font-semibold text-[var(--text-main)]">
            <span>{{ $completed }} / {{ $total }} terminées</span>
            <span>{{ $percent }}%</span>
        </div>

        <div class="indie-progress">
            <div class="indie-progress-bar" style="width: {{ $percent }}%"></div>
        </div>
    </div>
    </div>
</div>

<script>
let currentRotation = 0;

function spinWheel() {
    const tasks = @json($tasks->where('status', '!=', 'completed')->pluck('title'));
    if (!tasks.length) {
        document.getElementById('wheelResult').textContent = "Aucune tâche !";
        return;
    }

    const randomIndex = Math.floor(Math.random() * tasks.length);
    const degreesPerItem = 360 / tasks.length;
    const finalDeg = (5 * 360) + (360 - (randomIndex * degreesPerItem));

    currentRotation += finalDeg;
    document.getElementById('wheel').style.transform = `rotate(${currentRotation}deg)`;

    setTimeout(() => {
        document.getElementById('wheelResult').textContent = "🎯 " + tasks[randomIndex];
    }, 3000);
}

function toggleTask(id, checkbox) {
    fetch(`/cleantask/${id}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(() => location.reload())
    .catch(() => {
        alert('Échec de la mise à jour de la tâche. Veuillez réessayer.');
        checkbox.checked = !checkbox.checked; // revert on error
    });
}
</script>

</body>
</html>
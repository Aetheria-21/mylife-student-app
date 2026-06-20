<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des tâches</title>
    @include('partials.indie-theme')
    <style>
        /* ── Task card — distinct look ─────────────── */
        .task-card {
            position: relative;
            background: linear-gradient(145deg, var(--surface) 0%, var(--accent-soft) 100%);
            border: 1px solid var(--line);
            border-left: 5px solid var(--accent-deep);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-main);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .task-card::before {
            content: "";
            position: absolute;
            top: 0; right: 0;
            width: 90px; height: 90px;
            background: var(--tile-grad);
            opacity: 0.07;
            border-radius: 50%;
            transform: translate(30px, -30px);
            transition: transform 0.4s ease, opacity 0.3s ease;
        }
        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }
        .task-card:hover::before {
            transform: translate(15px, -15px) scale(1.15);
            opacity: 0.12;
        }
        .task-card.is-done {
            border-left-color: #10b981;
            background: linear-gradient(145deg, #f8fbf9 0%, #ecf7f1 100%);
        }
        .task-card.prio-high   { border-left-color: #ef4444; }
        .task-card.prio-medium { border-left-color: #f59e0b; }
        .task-card.prio-low    { border-left-color: #3b82f6; }
        .task-card.is-done.prio-high,
        .task-card.is-done.prio-medium,
        .task-card.is-done.prio-low { border-left-color: #10b981; }

        .task-card .task-title {
            font-family: "DM Sans", sans-serif;
            font-size: 1.25rem;
            font-weight: 900;
            color: var(--text-heading);
            letter-spacing: -0.01em;
            margin: 0.35rem 0 0.15rem;
        }
        .task-card .task-title.done {
            text-decoration: line-through;
            color: var(--text-muted);
        }
        .task-card .task-desc {
            font-family: "Georgia", serif;
            color: var(--text-muted);
            font-size: 0.92rem;
            margin-bottom: 0.3rem;
        }
        .task-card .task-due {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-muted);
            font-family: "DM Sans", sans-serif;
        }
        .task-card .task-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 999px;
            font-family: "DM Sans", sans-serif;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .task-card .task-actions button {
            border-radius: 10px;
            padding: 0.5rem 0.9rem;
            font-family: "DM Sans", sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            transition: transform 0.2s ease, filter 0.2s ease;
        }
        .task-card .task-actions button:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }
    </style>
</head>
<body class="indie-page theme-{{ (auth()->user()->gender ?? 'male') === 'female' ? 'female' : 'male' }} text-slate-800">
    <div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
        @include('partials.nav-sidebar')
        <div class="main-content space-y-8">
        <div class="indie-panel p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="indie-kicker">Productivité</p>
                    <h1 class="indie-title text-4xl font-black text-slate-900">Gestion des tâches</h1>
                    <p class="mt-2" style="color: var(--text-muted);">Organisez vos tâches par catégorie : études, travail, santé et plus encore.</p>
                </div>
                <div class="flex gap-4 text-center">
                    <div class="indie-soft-panel px-5 py-4 min-w-28"><p class="text-sm" style="color: var(--text-muted);">En attente</p><p class="text-3xl font-black" style="color: var(--accent);">{{ $pendingCount }}</p></div>
                    <div class="indie-soft-panel px-5 py-4 min-w-28"><p class="text-sm" style="color: var(--text-muted);">Terminées</p><p class="text-3xl font-black text-emerald-600">{{ $completedCount }}</p></div>
                </div>
            </div>
        </div>

        @if (session('task_status'))
            <div class="indie-soft-panel text-emerald-700 px-5 py-4 font-bold">✅ {{ session('task_status') }}</div>
        @endif

        @if ($errors->any())
            <div class="indie-soft-panel text-red-700 px-5 py-4">
                <p class="font-black mb-2">Veuillez corriger ces erreurs :</p>
                <ul class="list-disc pl-6 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid xl:grid-cols-[1.1fr,1.6fr] gap-8">
            <div class="indie-panel p-8">
                <h2 class="text-2xl font-black mb-5">➕ Ajouter une nouvelle tâche</h2>
                <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
                    @csrf
                    <input name="title" value="{{ old('title') }}" placeholder="Titre de la tâche" class="indie-input w-full px-4 py-3" required>
                    <textarea name="description" rows="3" placeholder="Description (facultatif)" class="indie-input w-full px-4 py-3">{{ old('description') }}</textarea>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <select name="category" class="indie-input w-full px-4 py-3" required>
                            @foreach ($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('category', 'study') === $key ? 'selected' : '' }}>{{ ($categoryIcons[$key] ?? '🗂️') . ' ' . $label }}</option>
                            @endforeach
                        </select>
                        <select name="priority" class="indie-input w-full px-4 py-3" required>
                            @foreach ($priorities as $key => $label)
                                <option value="{{ $key }}" {{ old('priority', 'medium') === $key ? 'selected' : '' }}>Priorité {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" min="{{ now()->format('Y-m-d') }}" class="indie-input w-full px-4 py-3">
                    <button type="submit" class="indie-button w-full py-3">Enregistrer la tâche</button>
                </form>
            </div>

            <div class="space-y-6">
                <div class="indie-panel p-6 space-y-4">
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('tasks.index', ['priority' => $selectedPriority]) }}" class="px-4 py-2 rounded-full font-bold {{ $selectedCategory === 'all' ? 'indie-button text-white' : 'indie-soft-panel' }}">🗂️ Toutes</a>
                        @foreach ($categories as $key => $label)
                            <a href="{{ route('tasks.index', ['category' => $key, 'priority' => $selectedPriority]) }}" class="px-4 py-2 rounded-full font-bold {{ $selectedCategory === $key ? 'indie-button text-white' : 'indie-soft-panel' }}">{{ ($categoryIcons[$key] ?? '🗂️') . ' ' . $label }} ({{ $taskCounts[$key] ?? 0 }})</a>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm font-semibold mb-2" style="color: var(--text-muted);">Filtrer par priorité :</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('tasks.index', ['category' => $selectedCategory]) }}" class="px-4 py-2 rounded-full font-bold {{ $selectedPriority === 'all' ? 'indie-button text-white' : 'indie-soft-panel' }}">🗂️ Toutes priorités</a>
                            @foreach ($priorities as $key => $label)
                                <a href="{{ route('tasks.index', ['category' => $selectedCategory, 'priority' => $key]) }}" class="px-4 py-2 rounded-full font-bold {{ $selectedPriority === $key ? 'indie-button text-white' : 'indie-soft-panel' }}">{{ ($priorityIcons[$key] ?? '•') . ' ' . $label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse ($tasks as $task)
                        @php
                            $isDone = $task->status === 'completed';
                            $cardClass = $isDone ? 'is-done' : 'prio-' . $task->priority;
                        @endphp
                        <div class="task-card {{ $cardClass }}">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 relative" style="z-index:1;">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                        <span class="task-chip {{ $isDone ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">{{ $isDone ? '✅ Terminée' : '⏳ En attente' }}</span>
                                        <span class="task-chip bg-sky-100 text-sky-700">{{ ($categoryIcons[$task->category] ?? '🗂️') . ' ' . ($categories[$task->category] ?? ucfirst($task->category)) }}</span>
                                        <span class="task-chip {{ $priorityColors[$task->priority] ?? 'bg-gray-100 text-gray-700' }}">{{ ($priorityIcons[$task->priority] ?? '•') . ' ' . ($priorities[$task->priority] ?? ucfirst($task->priority)) }}</span>
                                    </div>
                                    <h3 class="task-title {{ $isDone ? 'done' : '' }}">{{ $task->title }}</h3>
                                    @if ($task->description)<p class="task-desc">{{ $task->description }}</p>@endif
                                    <p class="task-due">📅 Échéance : {{ $task->due_date?->format('d/m/Y') ?? 'Aucune' }}</p>
                                </div>
                                <div class="task-actions flex flex-col sm:flex-row gap-2 lg:min-w-56">
                                    <form method="POST" action="{{ route('tasks.status', $task->id) }}" class="flex-1">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="{{ $isDone ? 'pending' : 'completed' }}">
                                        <button type="submit" class="w-full whitespace-nowrap {{ $isDone ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $isDone ? '↩ Rouvrir' : '✓ Terminer' }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="flex-1" onsubmit="return confirm('Supprimer cette tâche ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full whitespace-nowrap bg-rose-100 text-rose-700">🗑 Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="indie-panel p-10 text-center font-semibold" style="color: var(--text-muted);">Aucune tâche pour cette catégorie. Ajoutez-en une ci-dessus.</div>
                    @endforelse
                </div>
            </div>
        </div>
        </div>
    </div>
</body>
</html>
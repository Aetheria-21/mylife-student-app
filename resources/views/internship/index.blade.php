<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suivi des stages — MyLife</title>
    @include('partials.indie-theme')
    <style>
        /* ── Internship card — distinct look ───────── */
        .stage-card {
            position: relative;
            background: linear-gradient(145deg, var(--surface) 0%, var(--accent-soft) 100%);
            border: 1px solid var(--line);
            border-left: 6px solid var(--accent-deep);
            border-radius: 16px;
            padding: 1.5rem 1.75rem 1.5rem 2rem;
            box-shadow: var(--shadow-main);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-left-color 0.25s ease;
        }
        .stage-card::before {
            content: "";
            position: absolute;
            top: 0; right: 0;
            width: 120px; height: 120px;
            background: var(--tile-grad);
            opacity: 0.08;
            border-radius: 50%;
            transform: translate(40px, -40px);
            transition: transform 0.4s ease, opacity 0.3s ease;
        }
        .stage-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }
        .stage-card:hover::before {
            transform: translate(20px, -20px) scale(1.15);
            opacity: 0.14;
        }
        .stage-card.is-accepted { border-left-color: #10b981; }
        .stage-card.is-rejected { border-left-color: #f43f5e; }
        .stage-card.is-pending  { border-left-color: #f59e0b; }

        .stage-card .stage-company {
            font-family: "DM Sans", sans-serif;
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--text-heading);
            letter-spacing: -0.01em;
            margin: 0.4rem 0 0.15rem;
        }
        .stage-card .stage-position {
            font-family: "Georgia", serif;
            font-style: italic;
            color: var(--text-muted);
            font-size: 0.98rem;
        }
        .stage-card .stage-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 999px;
            font-family: "DM Sans", sans-serif;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .stage-card .stage-actions a,
        .stage-card .stage-actions button {
            border-radius: 12px;
            padding: 0.55rem 1rem;
            font-family: "DM Sans", sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            transition: transform 0.2s ease, filter 0.2s ease;
        }
        .stage-card .stage-actions a:hover,
        .stage-card .stage-actions button:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
        }
    </style>
</head>
<body class="indie-page theme-{{ (auth()->user()->gender ?? 'male') === 'female' ? 'female' : 'male' }} text-slate-800">

<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-8">

        <!-- Header -->
        <div class="indie-panel p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="indie-kicker">Carrière</p>
                    <h1 class="indie-title text-4xl font-black text-slate-900">💼 Suivi des stages</h1>
                    <p class="mt-2" style="color: var(--text-muted);">Suivez vos candidatures de stage et gérez votre parcours professionnel.</p>
                </div>
                <div class="text-center">
                    <div class="indie-soft-panel px-5 py-4 min-w-28">
                        <p class="text-sm" style="color: var(--text-muted);">Candidatures</p>
                        <p class="text-3xl font-black" style="color: var(--accent);">{{ $internships->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="indie-soft-panel text-emerald-700 px-5 py-4 font-bold">✅ {{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="indie-soft-panel text-red-700 px-5 py-4">
                <p class="font-black mb-2">Veuillez corriger ces erreurs :</p>
                <ul class="list-disc pl-6 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="grid xl:grid-cols-[1.15fr,1.4fr] gap-8">
            <!-- Add Form -->
            <div class="indie-panel p-10">
                <h2 class="text-3xl font-black mb-6">➕ Ajouter une candidature</h2>
                <form action="{{ route('internship.store') }}" method="POST" class="space-y-5" id="add-form">
                    @csrf
                    <input type="text" name="company" value="{{ old('company') }}" placeholder="Nom de l'entreprise" class="indie-input w-full px-5 py-4 text-base" required>
                    <input type="text" name="position" value="{{ old('position') }}" placeholder="Poste / Fonction" class="indie-input w-full px-5 py-4 text-base" required>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Date de candidature</label>
                            <input type="date" name="date_applied" id="date_applied" value="{{ old('date_applied') }}" class="indie-input w-full px-5 py-4 text-base">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Date de réponse</label>
                            <input type="date" name="response_date" id="response_date" value="{{ old('response_date') }}" class="indie-input w-full px-5 py-4 text-base">
                        </div>
                    </div>
                    <button type="submit" class="indie-button w-full py-4 text-base">💼 Enregistrer la candidature</button>
                </form>
            </div>

            <!-- List -->
            <div class="space-y-6">
                <div class="space-y-4">
                    @forelse($internships as $internship)
                        @php
                            $statusClass = $internship->status === 'Accepted' ? 'is-accepted' : ($internship->status === 'Rejected' ? 'is-rejected' : 'is-pending');
                        @endphp
                        <div class="stage-card {{ $statusClass }}">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 relative" style="z-index:1;">
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        <span class="stage-chip
                                            {{ $internship->status === 'Accepted' ? 'bg-emerald-100 text-emerald-700' : ($internship->status === 'Rejected' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                            {{ $internship->status === 'Pending' ? '⏳ En attente' : ($internship->status === 'Accepted' ? '✅ Acceptée' : '❌ Refusée') }}
                                        </span>
                                        @if($internship->date_applied)
                                            <span class="stage-chip bg-sky-100 text-sky-700">📅 {{ $internship->date_applied->format('d/m/Y') }}</span>
                                        @endif
                                        @if($internship->response_date)
                                            <span class="stage-chip bg-violet-100 text-violet-700">⏳ {{ $internship->response_date->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="stage-company">{{ $internship->company }}</h3>
                                    <p class="stage-position">{{ $internship->position }}</p>
                                </div>
                                <div class="stage-actions flex flex-col sm:flex-row gap-2 lg:min-w-44">
                                    <a href="{{ route('internship.edit', $internship) }}" class="indie-button text-center whitespace-nowrap">✏️ Modifier</a>
                                    <form action="{{ route('internship.destroy', $internship) }}" method="POST" onsubmit="return confirm('Supprimer ce stage ?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full bg-rose-100 text-rose-700 whitespace-nowrap">🗑️ Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="indie-panel p-10 text-center">
                            <div class="text-6xl mb-4 opacity-30">💼</div>
                            <p class="font-semibold text-lg" style="color: var(--text-muted);">Aucun stage pour l'instant. Ajoutez votre première candidature ci-dessus !</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]:not([value])').forEach(input => {
            input.value = today;
        });

        const dateApplied = document.getElementById('date_applied');
        const responseDate = document.getElementById('response_date');

        function updateResponseMin() {
            if (dateApplied.value) {
                responseDate.min = dateApplied.value;
            } else {
                responseDate.removeAttribute('min');
            }
        }

        dateApplied.addEventListener('change', updateResponseMin);
        updateResponseMin();
    });
</script>

</body>
</html>


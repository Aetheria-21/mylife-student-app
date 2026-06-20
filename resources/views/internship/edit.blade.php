<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Modifier le stage — MyLife</title>
    @include('partials.indie-theme')
</head>
<body class="indie-page theme-{{ (auth()->user()->gender ?? 'male') === 'female' ? 'female' : 'male' }} text-slate-800">

<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-8">

        <!-- Header -->
        <div class="indie-panel p-8">
            <p class="indie-kicker">Carrière</p>
            <h1 class="indie-title text-4xl font-black text-slate-900">✏️ Modifier la candidature</h1>
            <p class="mt-2" style="color: var(--text-muted);">Mettez à jour les détails de votre candidature de stage.</p>
        </div>

        @if ($errors->any())
            <div class="indie-soft-panel text-red-700 px-5 py-4">
                <p class="font-black mb-2">Veuillez corriger ces erreurs :</p>
                <ul class="list-disc pl-6 space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <!-- Edit Form -->
        <div class="indie-panel p-8 max-w-3xl mx-auto">
            <form action="{{ route('internship.update', $internship) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Entreprise</label>
                    <input type="text" name="company" value="{{ old('company', $internship->company) }}" placeholder="Nom de l'entreprise" class="indie-input w-full px-4 py-3" required>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Poste</label>
                    <input type="text" name="position" value="{{ old('position', $internship->position) }}" placeholder="Poste / Fonction" class="indie-input w-full px-4 py-3" required>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Date de candidature</label>
                        <input type="date" name="date_applied" id="date_applied" value="{{ old('date_applied', $internship->date_applied?->format('Y-m-d')) }}" class="indie-input w-full px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Date de réponse</label>
                        <input type="date" name="response_date" id="response_date" value="{{ old('response_date', $internship->response_date?->format('Y-m-d')) }}" class="indie-input w-full px-4 py-3">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold mb-1" style="color: var(--text-muted);">Statut</label>
                    <select name="status" class="indie-input w-full px-4 py-3">
                        <option value="Pending" {{ old('status', $internship->status) === 'Pending' ? 'selected' : '' }}>⏳ En attente</option>
                        <option value="Accepted" {{ old('status', $internship->status) === 'Accepted' ? 'selected' : '' }}>✅ Acceptée</option>
                        <option value="Rejected" {{ old('status', $internship->status) === 'Rejected' ? 'selected' : '' }}>❌ Refusée</option>
                    </select>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <button type="submit" class="indie-button flex-1 py-3">💾 Enregistrer les modifications</button>
                    <a href="{{ route('internship.index') }}" class="text-center rounded-2xl px-4 py-3 font-black bg-slate-100 text-slate-700 flex-1">❌ Annuler</a>
                </div>
            </form>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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


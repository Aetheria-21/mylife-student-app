<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil — MyLife</title>
    @include('partials.indie-theme')
    <style>
        /* ── Avatar ring ─────────────────────────── */
        .avatar-ring {
            width: 130px; height: 130px;
            border-radius: 9999px;
            padding: 4px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            box-shadow: var(--shadow-hover);
            flex-shrink: 0;
        }
        .avatar-ring img {
            width: 100%; height: 100%;
            border-radius: 9999px;
            object-fit: cover;
            background: var(--surface-strong);
            border: 3px solid white;
        }
        /* ── Upload overlay ──────────────────────── */
        .avatar-wrap { position: relative; display: inline-block; cursor: pointer; }
        .avatar-overlay {
            position: absolute; inset: 0;
            border-radius: 9999px;
            background: rgba(0,0,0,0.42);
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.2s ease;
            font-size: 1.6rem;
        }
        .avatar-wrap:hover .avatar-overlay { opacity: 1; }
        /* ── Info badge row ──────────────────────── */
        .info-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--accent-soft);
            border: 1px solid var(--line-strong);
            border-radius: 999px;
            padding: 0.3rem 0.9rem;
            font-size: 0.85rem; font-weight: 700;
            color: var(--text-main);
        }
    </style>
</head>
<body class="indie-page theme-{{ ($user->gender ?? 'male') === 'female' ? 'female' : 'male' }}">
<div class="page-layout" style="max-width:1200px;margin:0 auto;padding:0 1rem 3rem;">
    @include('partials.nav-sidebar')
    <div class="main-content space-y-8">

    <!-- Header -->
    <div class="indie-panel p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="indie-kicker mb-1">Compte</p>
                <h1 class="indie-title text-4xl font-black">👤 Mon profil</h1>
            </div>
        </div>
    </div>

    @if (session('profile_status'))
        <div class="indie-soft-panel px-6 py-4 text-emerald-700 font-bold">
            {{ session('profile_status') }}
        </div>
    @endif

    <!-- Profile Card (read view) -->
    <div class="indie-panel p-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
            <!-- Avatar -->
            <div class="avatar-wrap" onclick="document.getElementById('avatarInput').click()">
                <div class="avatar-ring">
                    <img id="avatarPreview"
                         src="{{ $user->avatar ? asset($user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=130' }}"
                         alt="Photo de profil">
                </div>
                <div class="avatar-overlay">📷</div>
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <h2 class="indie-title text-3xl font-black mb-1">{{ $user->name }}</h2>
                <p class="text-[var(--text-muted)] mb-4">{{ $user->email }}</p>
                <div class="flex flex-wrap gap-2">
                    @if($user->age)
                        <span class="info-badge">🎂 {{ $user->age }} ans</span>
                    @endif
                    <span class="info-badge">{{ ($user->gender ?? 'male') === 'female' ? '👩 Femme' : '👨 Homme' }}</span>
                    <span class="info-badge">📅 Membre depuis {{ $user->created_at->locale('fr')->isoFormat('MMM YYYY') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="indie-panel p-8">
        <h2 class="text-2xl font-black text-[var(--accent)] mb-6">✏️ Modifier le profil</h2>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PATCH')

            <!-- Hidden file input triggered by avatar click -->
            <input type="file" id="avatarInput" name="avatar" accept="image/*" class="hidden"
                   onchange="previewAvatar(this)">

            @error('avatar') <p class="text-red-500 text-sm font-bold">{{ $message }}</p> @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-black text-[var(--accent-text)] mb-2">Nom complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="indie-input w-full px-4 py-3" placeholder="Votre nom">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Age -->
                <div>
                    <label class="block text-sm font-black text-[var(--accent-text)] mb-2">Âge</label>
                    <input type="number" name="age" value="{{ old('age', $user->age) }}" min="1" max="120"
                           class="indie-input w-full px-4 py-3" placeholder="Votre âge">
                    @error('age') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-black text-[var(--accent-text)] mb-2">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="indie-input w-full px-4 py-3" placeholder="votre@email.com">
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Gender / Theme -->
                <div>
                    <label class="block text-sm font-black text-[var(--accent-text)] mb-2">Thème / Genre</label>
                    <select name="gender" class="indie-input w-full px-4 py-3" required>
                        <option value="male"   {{ old('gender', $user->gender) === 'male'   ? 'selected' : '' }}>👨 Thème homme</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>👩 Thème femme</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="indie-button px-10 py-3 text-lg font-black">
                    💾 Enregistrer le profil
                </button>
            </div>
        </form>
    </div>

    <!-- 💭 Mood Tips -->
    <div class="indie-panel p-8">
        <h2 class="text-2xl font-black text-[var(--accent)] mb-2">💭 Conseils selon l'humeur</h2>
        <p class="text-[var(--text-muted)] font-medium mb-6">Modifiez vos 5 conseils personnels pour chaque émotion.</p>

        <form method="POST" action="{{ route('settings.profile.update') }}" class="space-y-6">
            @csrf @method('PATCH')

            {{-- WelcomeController requires gender in this request --}}
            <input type="hidden" name="gender" value="{{ $user->gender ?? 'male' }}">

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                @foreach ($emotionMeta as $emotion => $meta)
                    <div class="indie-soft-panel p-5">
                        <h4 class="text-lg font-black text-[var(--accent-text)] mb-4 flex items-center gap-2">
                            <span class="text-2xl">{{ $meta['emoji'] }}</span>
                            {{ $meta['label'] }}
                        </h4>
                        <div class="space-y-3">
                            @for ($i = 0; $i < 5; $i++)
                                <div>
                                    <label class="block text-xs font-bold text-[var(--text-muted)] mb-1 uppercase tracking-wide">Conseil {{ $i + 1 }}</label>
                                    <textarea
                                        name="emotion_tips[{{ $emotion }}][{{ $i }}]"
                                        rows="2"
                                        class="indie-input w-full px-4 py-3"
                                        placeholder="Écrivez votre conseil « {{ mb_strtolower($meta['label']) }} » n°{{ $i + 1 }}"
                                        required
                                    >{{ old("emotion_tips.$emotion.$i", $emotionAdvice[$emotion][$i] ?? '') }}</textarea>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>

            @if($errors->has('emotion_tips') || $errors->has('emotion_tips.*') || $errors->has('emotion_tips.*.*'))
                <p class="text-red-500 font-bold text-sm">Veuillez remplir les 5 conseils pour chaque émotion.</p>
            @endif

            <div class="flex justify-end">
                <button type="submit" class="indie-button px-10 py-3 text-lg font-black">
                    💾 Enregistrer les conseils
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
        // auto-submit the form after picking
        input.closest('form').submit();
    }
}
</script>
</body>
</html>

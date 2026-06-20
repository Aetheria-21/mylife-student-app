<div class="nav-sidebar">
    <div class="home-nav">
        <div class="home-nav-grid">
            <a href="{{ route('home') }}" class="home-nav-tile {{ request()->routeIs('home') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                <span class="nav-label">Accueil</span>
            </a>
            <a href="{{ route('finance.index') }}"   class="home-nav-tile {{ request()->routeIs('finance.*') ? 'active' : '' }}">
                <span class="nav-icon">💰</span>
                <span class="nav-label">Finances</span>
            </a>
            <a href="{{ route('tasks.index') }}"     class="home-nav-tile {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <span class="nav-icon">🗂️</span>
                <span class="nav-label">Tâches</span>
            </a>
            <a href="{{ route('study.index') }}"     class="home-nav-tile {{ request()->routeIs('study.*') ? 'active' : '' }}">
                <span class="nav-icon">📖</span>
                <span class="nav-label">Études</span>
            </a>
            <a href="{{ route('cleantask.index') }}" class="home-nav-tile {{ request()->routeIs('cleantask.*') ? 'active' : '' }}">
                <span class="nav-icon">🧼</span>
                <span class="nav-label">Ménage</span>
            </a>
            <a href="{{ route('muslim.index') }}"    class="home-nav-tile {{ request()->routeIs('muslim.*','quran.*','dhikr.*') ? 'active' : '' }}">
                <span class="nav-icon">🌙</span>
                <span class="nav-label">Espace Musulman</span>
            </a>
            <a href="{{ route('hobbies.index') }}"   class="home-nav-tile {{ request()->routeIs('hobbies.*') ? 'active' : '' }}">
                <span class="nav-icon">🎸</span>
                <span class="nav-label">Loisirs</span>
            </a>
            <a href="{{ route('internship.index') }}" class="home-nav-tile {{ request()->routeIs('internship.*') ? 'active' : '' }}">
                <span class="nav-icon">💼</span>
                <span class="nav-label">Stage</span>
            </a>
        </div>
        @if(isset($taskProgress))
        <div class="mt-6 pt-6 border-t border-[var(--line-strong)]/50">
            <h4 class="text-lg font-black text-[var(--accent)] mb-4 flex items-center gap-2">📊 Progression des tâches</h4>
            <div class="grid grid-cols-2 gap-3">
                <div class="glass-card p-3 text-center">
                    <p class="text-xs uppercase text-[var(--text-muted)] font-bold">Total</p>
                    <p class="text-xl font-black text-[var(--accent)]">{{ $taskProgress['total'] ?? 0 }}</p>
                </div>
                <div class="glass-card p-3 text-center">
                    <p class="text-xs uppercase text-[var(--text-muted)] font-bold">En attente</p>
                    <p class="text-xl font-black text-amber-500">{{ $taskProgress['pending'] ?? 0 }}</p>
                </div>
                <div class="glass-card p-3 text-center">
                    <p class="text-xs uppercase text-[var(--text-muted)] font-bold">Terminées</p>
                    <p class="text-xl font-black text-emerald-600">{{ $taskProgress['completed'] ?? 0 }}</p>
                </div>
                <div class="glass-card p-3 text-center bg-gradient-to-br from-green-50 to-emerald-50">
                    <p class="text-xs uppercase text-[var(--text-muted)] font-bold">Taux</p>
                    <p class="text-xl font-black text-emerald-700">{{ $taskProgress['completionRate'] ?? 0 }}%</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>


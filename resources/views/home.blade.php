<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Accueil</title>
    @include('partials.indie-theme')
    
    <style>
        /* ═══════════════════════════════════════════════
           HOME PAGE — Overrides on top of indie-theme
        ═══════════════════════════════════════════════ */

        /* ── Outer shell: clean white page ─────────── */
.home-app-shell {
            background: var(--surface);
            border-radius: 0;
            box-shadow: none;
            border: none;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
        }

        /* ── Welcome title ─────────────────────────── */
        .home-page-title {
            font-family: "Georgia", "Lora", serif;
            font-size: clamp(1.6rem, 3vw, 2rem);
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.01em;
            line-height: 1.25;
        }

        /* ── Day / time tags ───────────────────────── */
        .time-tag, .day-tag {
            background: var(--accent-soft);
            color: var(--accent-deep);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-family: "DM Sans", sans-serif;
            font-weight: 700;
            display: inline-block;
        }

        /* ── Prayer card highlight ─────────────────── */
        .prayer-now {
            background: var(--accent-soft) !important;
            border: 2px solid var(--accent-deep) !important;
            transform: scale(1.04) !important;
            box-shadow: var(--shadow-hover) !important;
        }

        /* ── Mood buttons ──────────────────────────── */
        .mood-btn {
            cursor: pointer;
            border: 1.5px solid var(--line) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            background: var(--surface) !important;
        }
        .mood-btn:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent-deep) !important;
            background: var(--accent-soft) !important;
        }
        .mood-btn .mood-emoji { transition: transform 0.2s ease; }
        .mood-btn:hover .mood-emoji { transform: scale(1.15); }

        /* ── Tip buttons ───────────────────────────── */
        .tip-btn {
            font-size: 13px;
            font-family: "DM Sans", sans-serif;
            font-weight: 600;
            padding: 7px 14px;
            border-radius: 8px;
            background: var(--accent-soft);
            border: 1px solid var(--line-strong);
            color: var(--text-heading);
            cursor: pointer;
            transition: all 0.18s ease;
        }
        .tip-btn:hover {
            background: var(--accent);
            border-color: var(--accent-deep);
            color: #fff;
            transform: translateY(-2px);
        }

        /* ── Navigation cards ──────────────────────── */
        .section-nav-card {
            text-decoration: none;
            border: 1px solid var(--line) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .section-nav-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent-deep) !important;
        }
        .section-nav-card .section-icon { transition: transform 0.2s ease; }
        .section-nav-card:hover .section-icon { transform: scale(1.15); }

        /* ── Progress bar ──────────────────────────── */
        .progress-track {
            height: 8px;
            border-radius: 999px;
            background: var(--accent-soft);
            border: 1px solid var(--line);
            overflow: hidden;
            margin-top: 0.5rem;
        }
        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: var(--tile-grad);
            transition: width 0.9s ease;
        }

        /* ── Task stat mini-cards ──────────────────── */
        .task-stat {
            background: var(--surface-alt);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 1rem 0.75rem;
            text-align: center;
        }

        /* ── Entrance animation ────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.5s ease both; }

        /* ── Modal ─────────────────────────────────── */
        #eventModal { background: rgba(95,75,75,0.35); }

        /* ═══════════════════════════════════════════
           HOME LAYOUT — bento grid
           Row 1: emotions (full)
           Row 2: weather | prayer
           Row 3: calendar (full)
        ═══════════════════════════════════════════ */
        .home-main-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            grid-template-areas:
                "emotions emotions"
                "weather  prayer"
                "calendar calendar";
            gap: 24px;
            align-items: start;
        .home-emotions  { grid-area: emotions; }
        .home-weather   { grid-area: weather; }
        .home-prayer    { grid-area: prayer; }
        .home-calendar  { grid-area: calendar; }


        /* Inner card — for items inside a glass-card */
        .card-inner {
            background: var(--surface-alt);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 1rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }
        .card-inner:hover {
            transform: translateY(-2px);
            background: var(--accent-soft);
            box-shadow: var(--shadow-main);
        }

        /* Weather — bigger single-column card for clear text */
        .home-weather > h3 { font-size: 1.25rem; margin-bottom: 1rem; }
        .home-weather .text-6xl { font-size: 3.5rem; }
        .home-weather .text-2xl { font-size: 1.25rem; }
        .home-weather .text-xl  { font-size: 1.15rem; }
        .home-weather .text-lg  { font-size: 1.05rem; }
        .home-weather .text-3xl { font-size: 1.4rem; }
        .home-weather .text-sm  { font-size: 0.95rem; }
        .home-weather .text-xs  { font-size: 0.82rem; }
        .home-weather .gap-6    { gap: 1rem; }
        .home-weather .gap-4    { gap: 0.8rem; }
        .home-weather .mb-6     { margin-bottom: 1rem; }
        .home-weather .pt-6     { padding-top: 1.5rem; }
        .home-weather .space-y-6 > * + * { margin-top: 1.5rem; }
        .home-weather .p-6,.home-weather .p-8,.home-weather .p-12 { padding: 1.5rem; }

        /* Prayer — compact card for clear readable text */
        .home-prayer > h3 { font-size: 0.95rem; margin-bottom: 0.5rem; }
        .home-prayer h5 { font-size: 0.55rem !important; }
        .home-prayer .text-3xl  { font-size: 0.85rem; }
        .home-prayer .text-2xl  { font-size: 0.8rem; }
        .home-prayer .text-xl   { font-size: 0.8rem; }
        .home-prayer .text-sm   { font-size: 0.68rem; }
        .home-prayer .gap-3     { gap: 0.25rem; }
        .home-prayer .gap-4     { gap: 0.35rem; }
        .home-prayer .mb-2      { margin-bottom: 0.15rem; }
        .home-prayer .p-6,.home-prayer .p-8,.home-prayer .p-12 { padding: 0.4rem; }
        .home-prayer .mb-8,.home-prayer .mb-6 { margin-bottom: 0.4rem; }
        .home-prayer .mt-8,.home-prayer .mt-6 { margin-top: 0.4rem; }
        .home-prayer .pt-6,.home-prayer .pt-4 { padding-top: 0.4rem; }
        .home-prayer .pb-4 { padding-bottom: 0.25rem; }

        /* Nav cards — uniform bento tiles */
        .home-nav-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
        .home-nav-tile {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 14px 10px;
            text-align: center;
            text-decoration: none;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: var(--shadow-main);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            min-height: 76px;
        }
        .home-nav-tile:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--accent-deep);
            background: var(--accent-soft);
        }
        .home-nav-tile .nav-icon {
            font-size: 1.5rem;
            line-height: 1;
            transition: transform 0.2s ease;
        }
        .home-nav-tile:hover .nav-icon { transform: scale(1.18); }
        .home-nav-tile .nav-label {
            font-family: "DM Sans", sans-serif;
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-heading);
            line-height: 1.3;
        }

        /* Task tiles matching nav style */
        .task-grid {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .task-tile {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            padding: 20px 16px !important;
            text-align: center !important;
            text-decoration: none !important;
            border: 1px solid var(--line) !important;
            border-radius: 16px !important;
            background: var(--surface) !important;
            box-shadow: var(--shadow-main) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease !important;
            min-height: 76px !important;
            color: inherit !important;
        }
        .task-tile:hover {
            transform: translateY(-4px) !important;
            box-shadow: var(--shadow-hover) !important;
            border-color: var(--accent-deep) !important;
            background: var(--accent-soft) !important;
        }
        .task-tile .task-icon {
            font-size: 2rem !important;
            line-height: 1 !important;
            transition: transform 0.2s ease !important;
        }
        .task-tile:hover .task-icon {
            transform: scale(1.18) !important;
        }
        .task-tile .task-label {
            font-family: "DM Sans", sans-serif !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            color: var(--text-heading) !important;
            line-height: 1.3 !important;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .home-main-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-areas:
                    "emotions emotions"
                    "weather  prayer"
                    "calendar calendar";
            }
        }

        @media (max-width: 640px) {
            .home-main-grid {
                grid-template-columns: 1fr;
                grid-template-areas: "emotions" "weather" "prayer" "calendar";
                gap: 16px;
            }
        }


        /* ═══════════════════════════════════════════
           CALENDAR CARD — themed FullCalendar overrides
        ═══════════════════════════════════════════ */
        .home-calendar {
            padding: 1.25rem 1.5rem !important;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: linear-gradient(160deg, var(--surface) 0%, var(--accent-soft) 100%);
            box-shadow: var(--shadow-main);
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }
        .home-calendar:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }
        /* Toolbar title */
        .home-calendar .fc-toolbar-title {
            font-family: "Georgia", "Lora", serif;
            font-weight: 700;
            color: var(--text-heading);
            font-size: 1.4rem !important;
            letter-spacing: -0.01em;
        }
        /* Toolbar buttons */
        .home-calendar .fc .fc-button-primary {
            background: var(--surface) !important;
            border: 1px solid var(--line-strong) !important;
            color: var(--text-main) !important;
            font-family: "DM Sans", sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: none;
            border-radius: 10px !important;
            padding: 6px 12px !important;
            box-shadow: none !important;
            transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }
        .home-calendar .fc .fc-button-primary:hover {
            background: var(--accent-soft) !important;
            color: var(--accent-deep) !important;
            transform: translateY(-1px);
        }
        .home-calendar .fc .fc-button-primary:not(:disabled).fc-button-active,
        .home-calendar .fc .fc-button-primary:not(:disabled):active {
            background: var(--tile-grad) !important;
            color: #fff !important;
            border-color: transparent !important;
        }
        .home-calendar .fc .fc-button-primary:focus {
            box-shadow: 0 0 0 2px var(--accent-soft) !important;
        }
        /* Day grid borders */
        .home-calendar .fc-theme-standard td,
        .home-calendar .fc-theme-standard th,
        .home-calendar .fc-theme-standard .fc-scrollgrid {
            border-color: var(--line) !important;
        }
        .home-calendar .fc-col-header-cell {
            background: var(--accent-soft);
        }
        .home-calendar .fc-col-header-cell-cushion {
            color: var(--accent-deep);
            font-family: "DM Sans", sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 8px 4px;
        }
        /* Today highlight */
        .home-calendar .fc .fc-daygrid-day.fc-day-today {
            background: var(--accent-soft) !important;
        }
        .home-calendar .fc .fc-daygrid-day-number {
            color: var(--text-main);
            font-family: "DM Sans", sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 6px 8px;
        }
        .home-calendar .fc-day-today .fc-daygrid-day-number {
            color: var(--accent-deep);
            font-weight: 900;
        }
        /* Events */
        .home-calendar .fc-event {
            background: var(--tile-grad) !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 2px 6px !important;
            font-family: "DM Sans", sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        .home-calendar .fc-event-title,
        .home-calendar .fc-event-time {
            color: #fff !important;
        }
        /* List view */
        .home-calendar .fc-list-day-cushion {
            background: var(--accent-soft) !important;
            color: var(--accent-deep) !important;
        }
        .home-calendar .fc-list-event:hover td {
            background: var(--accent-soft) !important;
        }
    </style>


    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
</head>
<body class="indie-page min-h-screen overflow-x-hidden {{ (auth()->user()->gender ?? 'male') === 'female' ? 'theme-female' : 'theme-male' }}" data-user-gender="{{ auth()->user()->gender ?? 'male' }}">

<div class="home-app-shell">

    <!-- ══ BANNER ══════════════════════════════════ -->
    <header class="page-banner-content flex items-center justify-between w-full p-6 mx-auto max-w-7xl">
            <div>
                <p class="indie-kicker mb-1">Tableau de bord MyLife</p>
                <h1 class="home-page-title animate-fade-in" style="-webkit-text-fill-color:var(--text-heading)">
                    Bon retour, {{ auth()->user()->name ?? 'ami(e)' }} ! 👋
                </h1>
            </div>
            <!-- Profile button -->
            <a href="{{ route('profile.show') }}"
               class="flex items-center gap-3 indie-panel px-4 py-2.5 transition-all group"
               style="text-decoration:none; border-radius:999px; flex-shrink:0;">
                <img src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name ?? 'U') . '&background=random&size=48' }}"
                     alt="avatar"
                     class="w-10 h-10 rounded-full object-cover border-2 group-hover:scale-105 transition-transform"
                     style="border-color: var(--line-strong);">
                <span class="text-sm font-bold hidden sm:inline" style="color:var(--text-heading); font-family:'DM Sans',sans-serif;">Mon profil</span>
            </a>
        </header>

    <!-- ══ CONTENT WRAPPER ════════════════════════ -->
    <div class="flex-1 overflow-auto max-w-7xl mx-auto px-6 py-8">
        <!-- subtitle -->
        <p class="page-subtitle mb-6" style="font-style:italic; color:var(--text-muted); border-left:3px solid var(--line-strong); padding-left:12px;">
            Votre espace personnel — tâches, météo, prières, finances et plus encore en un coup d'œil.
        </p>

    <!-- New page layout: left nav sidebar + right main content -->
    <div class="page-layout">
        @include('partials.nav-sidebar')
        <!-- Right main content -->
        <div class="main-content">
            <div class="home-main-grid">


        <!-- ══ WEATHER — grid-area: weather ══ -->
        <div class="glass-card p-8 aero-glow home-weather">
            <h3 class="text-2xl font-black text-[var(--accent)] mb-6 flex items-center gap-3">
                🌤️ Météo Tunis
            </h3>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    loadWeatherTunis();
                });

                function loadWeatherTunis() {
                    document.getElementById('weatherResult').classList.add('hidden');
                    document.getElementById('weatherError').classList.add('hidden');
                    document.getElementById('weatherDefault').classList.remove('hidden');
                    document.getElementById('weatherLoading').classList.remove('hidden');

                    fetch('/weather')
                        .then(res => {
                            if (!res.ok) throw new Error('Network response was not ok');
                            return res.json();
                        })
                        .then(data => {
                            document.getElementById('weatherLoading').classList.add('hidden');

                            if (data && data.location && data.current) {
                               document.getElementById('weatherCityName').textContent = `📍 ${data.location.name}`;
                                document.getElementById('weatherCountry').textContent = data.location.country;
                                document.getElementById('weatherLocaltime').textContent = `⏰ ${formatWeatherLocalTime(data.location.localtime)}`;
                                document.getElementById('weatherTemp').textContent = `${Math.round(data.current.temp_c)}°`;
                                document.getElementById('weatherCondition').textContent = data.current.condition.text;
                                document.getElementById('weatherWind').textContent = `${data.current.wind_kph} km/h`;
                                document.getElementById('weatherHumidity').textContent = `${data.current.humidity}%`;
                                document.getElementById('weatherFeelsLike').textContent = `${Math.round(data.current.feelslike_c)}°`;
                                document.getElementById('weatherResult').classList.remove('hidden');
                                document.getElementById('weatherDefault').classList.add('hidden');
                            } else {
                                throw new Error('Invalid weather data format');
                            }
                        })
                        .catch(error => {
                            console.error('Weather error:', error);
                            document.getElementById('weatherLoading').classList.add('hidden');
                            document.getElementById('weatherError').classList.remove('hidden');
                        });
                }

                function formatWeatherLocalTime(localtime) {
                    if (!localtime) return '--';

                    const parsedDate = new Date(localtime.replace(' ', 'T'));

                    if (Number.isNaN(parsedDate.getTime())) {
                        return localtime;
                    }

                    return parsedDate.toLocaleString('fr-FR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }).replace(',', '');
                }
            </script>

            <div id="weatherResult" class="hidden">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 id="weatherCityName" class="text-2xl font-bold text-[var(--accent-text)]">📍 Tunis</h4>
                            <p id="weatherCountry" class="text-gray-600 text-lg">Tunisie</p>
                            <p id="weatherLocaltime" class="text-gray-500 text-sm">⏰ --</p>
                        </div>
                        <div class="text-center">
                            <div id="weatherTemp" class="text-6xl font-black text-[var(--accent)]">--°</div>
                            <p id="weatherCondition" class="text-gray-700 text-xl font-semibold">--</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-white/30">
                        <div class="text-center">
                            <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Vent</p>
                            <p id="weatherWind" class="text-2xl font-bold text-[var(--accent)]">-- km/h</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Humidité</p>
                            <p id="weatherHumidity" class="text-2xl font-bold text-[var(--accent)]">--%</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-500 text-xs uppercase tracking-wide font-semibold">Ressenti</p>
                            <p id="weatherFeelsLike" class="text-2xl font-bold text-[var(--accent)]">--°</p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="weatherLoading" class="hidden">
                <div class="card-inner text-center">
                    <div class="animate-pulse flex items-center justify-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-[var(--accent)]/20 rounded-2xl animate-ping"></div>
                        <p class="text-[var(--accent)] font-bold text-xl">Chargement de la météo de Tunis...</p>
                    </div>
                    <p class="text-[var(--accent-text)] text-lg">Récupération automatique de la météo de Tunis</p>
                </div>
            </div>

            <div id="weatherError" class="hidden">
                <div class="card-inner border-2 border-red-300/50">
                    <p class="text-red-400 font-bold text-xl text-center">❌ Erreur lors du chargement de la météo</p>
                </div>
            </div>

            <div id="weatherDefault" class="card-inner border-2 border-dashed border-white/50 text-center">
                <p class="text-[var(--accent-text)] font-bold text-lg">🌤️ Chargement automatique de la météo de Tunis</p>
            </div>
        </div>

        <!-- ══ PRAYER — grid-area: prayer ══ -->
        <div class="glass-card p-6 aero-glow home-prayer">
            <h3 class="text-2xl font-black text-[var(--accent)] mb-6 flex items-center gap-3">
                🕌 Heures de prière à Tunis
            </h3>

            <script>
                // Auto-load Tunis prayer times on page load
                document.addEventListener('DOMContentLoaded', function() {
                    loadPrayerTimesTunis();
                });

                function loadPrayerTimesTunis() {
                    document.getElementById('prayerTimesResult').classList.add('hidden');
                    document.getElementById('prayerError').classList.add('hidden');
                    document.getElementById('prayerDefault').classList.remove('hidden'); // Show default briefly
                    document.getElementById('prayerLoading').classList.remove('hidden');

                    fetch('/prayer-times?city=Tunis&country=Tunisia')
                        .then(res => {
                            if (!res.ok) throw new Error('Network response was not ok');
                            return res.json();
                        })
                        .then(data => {
                            document.getElementById('prayerLoading').classList.add('hidden');
                            if (data && data.data && data.data.timings) {
                                const t = data.data.timings;
                                const date = data.data.date;
                                document.getElementById('prayerCityName').textContent = '🇹🇳 Tunis, Tunisie';
                                document.getElementById('prayerDate').textContent = `${date.readable} - ${date.hijri.day} ${date.hijri.month.en} ${date.hijri.year} H`;
                                document.getElementById('fajrTime').textContent = t.Fajr;
                                document.getElementById('dhuhrTime').textContent = t.Dhuhr;
                                document.getElementById('asrTime').textContent = t.Asr;
                                document.getElementById('maghribTime').textContent = t.Maghrib;
                                document.getElementById('ishaTime').textContent = t.Isha;
                                document.getElementById('sunriseTime').textContent = t.Sunrise || '--:--';
                                document.getElementById('midnightTime').textContent = t.Midnight || '--:--';
                                document.getElementById('imsakTime').textContent = t.Imsak || '--:--';
                                document.getElementById('prayerTimesResult').classList.remove('hidden');
                                document.getElementById('prayerDefault').classList.add('hidden');
                                highlightCurrentPrayer(t);
                            } else {
                                throw new Error('Invalid data format');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('prayerLoading').classList.add('hidden');
                            document.getElementById('prayerError').classList.remove('hidden');
                        });
                }

                function highlightCurrentPrayer(timings) {
                    const now = new Date();
                    const currentTime = now.getHours() * 60 + now.getMinutes();
                    const prayers = [
                        { name: 'fajr', time: timings.Fajr },
                        { name: 'dhuhr', time: timings.Dhuhr },
                        { name: 'asr', time: timings.Asr },
                        { name: 'maghrib', time: timings.Maghrib },
                        { name: 'isha', time: timings.Isha }
                    ];
                    prayers.forEach(prayer => {
                        const [hours, minutes] = prayer.time.split(':').map(Number);
                        const prayerTime = hours * 60 + minutes;
                        const element = document.getElementById(`${prayer.name}Time`).parentElement;
                        element.classList.remove('prayer-now');
                        if (currentTime >= prayerTime - 30 && currentTime <= prayerTime + 30) {
                            element.classList.add('prayer-now');
                        }
                    });
                }
            </script>

            <!-- Prayer Times Display -->
            <div id="prayerTimesResult" class="hidden">
                <div class="card-inner">
                    <!-- City and Date Info -->
                    <div class="text-center mb-6 pb-4 border-b-2 border-white/30">
                        <h4 id="prayerCityName" class="text-xl font-black text-[var(--accent-text)] mb-1"></h4>
                        <p id="prayerDate" class="text-gray-600 text-sm"></p>
                    </div>

                    <!-- Prayer Times Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                        <!-- Fajr -->
                        <div class="card-inner text-center hover:glass-glow">
                            <div class="text-3xl mb-2">🌅</div>
                            <h5 class="font-bold text-sm text-[var(--accent)] mb-1 uppercase tracking-wide">Fajr</h5>
                            <p id="fajrTime" class="text-2xl font-black text-[var(--accent-text)]">--:--</p>
                        </div>

                        <!-- Dhuhr -->
                        <div class="card-inner text-center hover:glass-glow">
                            <div class="text-3xl mb-2">☀️</div>
                            <h5 class="font-bold text-sm text-[var(--accent)] mb-1 uppercase tracking-wide">Dhuhr</h5>
                            <p id="dhuhrTime" class="text-2xl font-black text-[var(--accent-text)]">--:--</p>
                        </div>

                        <!-- Asr -->
                        <div class="card-inner text-center hover:glass-glow">
                            <div class="text-3xl mb-2">🌤️</div>
                            <h5 class="font-bold text-sm text-[var(--accent)] mb-1 uppercase tracking-wide">Asr</h5>
                            <p id="asrTime" class="text-2xl font-black text-[var(--accent-text)]">--:--</p>
                        </div>

                        <!-- Maghrib -->
                        <div class="card-inner text-center hover:glass-glow">
                            <div class="text-3xl mb-2">🌆</div>
                            <h5 class="font-bold text-sm text-[var(--accent)] mb-1 uppercase tracking-wide">Maghrib</h5>
                            <p id="maghribTime" class="text-2xl font-black text-[var(--accent-text)]">--:--</p>
                        </div>

                        <!-- Isha -->
                        <div class="card-inner text-center hover:glass-glow">
                            <div class="text-3xl mb-2">🌙</div>
                            <h5 class="font-bold text-sm text-[var(--accent)] mb-1 uppercase tracking-wide">Isha</h5>
                            <p id="ishaTime" class="text-2xl font-black text-[var(--accent-text)]">--:--</p>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-6 pt-4 border-t-2 border-white/30">
                        <div class="grid grid-cols-3 gap-4 text-center text-sm">
                            <div>
                                <p class="text-gray-500 uppercase tracking-wide font-semibold">Lever du soleil</p>
                                <p id="sunriseTime" class="font-bold text-[var(--accent)]">--:--</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase tracking-wide font-semibold">Minuit</p>
                                <p id="midnightTime" class="font-bold text-[var(--accent)]">--:--</p>
                            </div>
                            <div>
                                <p class="text-gray-500 uppercase tracking-wide font-semibold">Imsak</p>
                                <p id="imsakTime" class="font-bold text-[var(--accent)]">--:--</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div id="prayerLoading" class="hidden">
                <div class="card-inner text-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-4 border-[var(--accent)] border-t-transparent mx-auto mb-6"></div>
                    <p class="text-[var(--accent-text)] font-bold text-xl">Chargement des heures de prière...</p>
                </div>
            </div>

            <!-- Error State -->
            <div id="prayerError" class="hidden">
                <div class="card-inner border-2 border-red-300/50">
                    <p class="text-red-400 font-bold text-xl text-center">❌ Erreur lors du chargement des heures de prière</p>
                </div>
            </div>

            <!-- Default State -->
            <div id="prayerDefault" class="card-inner border-2 border-dashed border-white/50 text-center">
                <p class="text-[var(--accent-text)] font-bold text-lg">🕌 Chargement automatique des heures de prière de Tunis</p>
            </div>
        </div>
    </div>{{-- /home-prayer --}}

    <script>
        // Gender detection & theme application
        document.addEventListener('DOMContentLoaded', function() {
            const userGender = document.body.dataset.userGender || 'male';
            const themeClass = userGender === 'female' ? 'theme-female' : 'theme-male';

            document.body.classList.remove('theme-male', 'theme-female');
            document.body.classList.add(themeClass);
            document.body.style.background = `var(--gradient-main), linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%)`;
            document.body.style.backgroundBlendMode = 'overlay';
            
           console.log(`🎨 Aero theme applied: ${themeClass} for gender "${userGender}"`);
        });
    </script>
    <!-- End Prayer Widget -->

    <!-- ══ EMOTIONS — grid-area: emotions (LEFT) ══ -->
    <div class="home-emotions">
    <div class="glass-card p-5 aero-glow h-full">
        <h2 class="text-3xl font-black text-[var(--accent)] mb-6 flex items-center gap-3 text-center">
            💭 Comment vous sentez-vous ?
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button onclick="selectEmotion('happy')" class="card-inner text-center hover:glass-glow group transition-all cursor-pointer">
                <div class="text-5xl mb-3 group-hover:scale-110">😊</div>
                <span class="font-bold text-lg block text-[var(--accent-text)]">Heureux</span>
            </button>
            <button onclick="selectEmotion('sad')" class="card-inner text-center hover:glass-glow group transition-all cursor-pointer">
                <div class="text-5xl mb-3 group-hover:scale-110">😢</div>
                <span class="font-bold text-lg block text-[var(--accent-text)]">Triste</span>
            </button>
            <button onclick="selectEmotion('angry')" class="card-inner text-center hover:glass-glow group transition-all cursor-pointer">
                <div class="text-5xl mb-3 group-hover:scale-110">😠</div>
                <span class="font-bold text-lg block text-[var(--accent-text)]">En colère</span>
            </button>
            <button onclick="selectEmotion('neutral')" class="card-inner text-center hover:glass-glow group transition-all cursor-pointer">
                <div class="text-5xl mb-3 group-hover:scale-110">😐</div>
                <span class="font-bold text-lg block text-[var(--accent-text)]">Neutre</span>
            </button>
        </div>

        <!-- Advice Buttons -->
        <div id="adviceSection" class="mt-8 hidden">
            <h3 class="text-2xl font-black text-[var(--accent)] mb-6 text-center">
                Conseils pour <span id="currentEmotionLabel" class="text-[var(--accent-text)]">votre humeur</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
                <button onclick="showAdvice(1)" class="card-inner text-center hover:glass-glow cursor-pointer">💡 Conseil 1</button>
                <button onclick="showAdvice(2)" class="card-inner text-center hover:glass-glow cursor-pointer">💡 Conseil 2</button>
                <button onclick="showAdvice(3)" class="card-inner text-center hover:glass-glow cursor-pointer">💡 Conseil 3</button>
                <button onclick="showAdvice(4)" class="card-inner text-center hover:glass-glow cursor-pointer">💡 Conseil 4</button>
                <button onclick="showAdvice(5)" class="card-inner text-center hover:glass-glow cursor-pointer">💡 Conseil 5</button>
            </div>
            <div id="adviceDisplay" class="card-inner text-center hidden">
                <p id="adviceText" class="text-xl font-semibold text-[var(--accent-text)] mb-4"></p>
                <button onclick="resetMood()" class="glass-button px-8 py-3 text-lg">🔄 Réinitialiser</button>
            </div>
        </div>
    </div>
    </div>{{-- /home-emotions --}}

    <script>
        let currentEmotion = '';
        let currentAdviceIndex = 0;

        const advice = @json($emotionAdvice ?? []);

        function selectEmotion(emotion) {
            currentEmotion = emotion;
            currentAdviceIndex = 0;
            const emotionLabels = { happy: 'Heureux', sad: 'Triste', angry: 'En colère', neutral: 'Neutre' };
            document.getElementById('adviceSection').classList.remove('hidden');
            document.getElementById('currentEmotionLabel').textContent = emotionLabels[emotion] || emotion;
            hideAdvice();
        }

        function showAdvice(index) {
            currentAdviceIndex = index - 1;
            const adviceText = document.getElementById('adviceText');
            const adviceDisplay = document.getElementById('adviceDisplay');
            const selectedAdvice = advice[currentEmotion] || [];

            if (! selectedAdvice[currentAdviceIndex]) {
                adviceText.textContent = 'Aucun conseil enregistré pour cette émotion.';
                adviceDisplay.classList.remove('hidden');
                return;
            }
            
            adviceText.textContent = selectedAdvice[currentAdviceIndex];
            adviceDisplay.classList.remove('hidden');
            
            // Scroll to advice
            adviceDisplay.scrollIntoView({ behavior: 'smooth' });
        }

        function resetMood() {
            currentEmotion = '';
            document.getElementById('adviceSection').classList.add('hidden');
            hideAdvice();
        }

        function hideAdvice() {
            document.getElementById('adviceDisplay').classList.add('hidden');
        }
    </script>

<!-- FullCalendar Container + Modal + JS COMPLET & CORRIGÉ -->

<!-- 1️⃣ FullCalendar Container -->
<!-- ══ CALENDAR — grid-area: calendar ══ -->
<div id="calendar" class="glass-card p-5 aero-glow home-calendar" style="min-height:440px;"></div>



<!-- 2️⃣ Modal Création/Édition Événement (UNIQUE & PERFECT) -->
<div id="eventModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="glass-card w-full max-w-md max-h-[90vh] overflow-y-auto rounded-3xl shadow-3xl">
        <div class="p-6 border-b border-white/30 rounded-t-3xl">
            <h3 id="modalTitle" class="text-2xl font-black text-[var(--accent)] mb-2">Nouvel Événement</h3>
            <button onclick="closeModal()" class="float-right text-3xl hover:text-red-500 transition-colors p-1 hover:bg-red-500/20 rounded-xl">✕</button>
        </div>
        
        <form id="eventForm">
            @csrf
            <input type="hidden" id="eventId" name="id">
            
            <div class="p-6 space-y-4">
                <!-- Titre -->
                <div>
                    <label class="block text-sm font-bold text-[var(--accent-text)] mb-2 flex items-center gap-2">
                        📝 Titre *
                    </label>
                    <input id="eventTitle" type="text" name="title" required 
                           class="glass-input w-full rounded-2xl px-5 py-4 text-lg text-gray-800 border-2 border-white/30 focus:border-[var(--accent)]/50 focus:ring-4 focus:ring-[var(--accent)]/20 transition-all" 
                           placeholder="Ex: Réunion équipe dev">
                </div>
                
                <!-- Début -->
                <div>
                    <label class="block text-sm font-bold text-[var(--accent-text)] mb-2 flex items-center gap-2">
                        🕒 Début *
                    </label>
                    <input id="eventStart" type="datetime-local" name="start" required 
                           class="glass-input w-full rounded-2xl px-5 py-4 text-lg text-gray-800 border-2 border-white/30 focus:border-blue-400/50 focus:ring-4 focus:ring-blue-400/20 transition-all">
                </div>
                
                <!-- Fin -->
                <div>
                    <label class="block text-sm font-bold text-[var(--accent-text)] mb-2 flex items-center gap-2">
                        🏁 Fin
                    </label>
                    <input id="eventEnd" type="datetime-local" name="end" 
                                                      class="glass-input w-full rounded-2xl px-5 py-4 text-lg text-gray-800 border-2 border-white/30 focus:border-blue-400/50 focus:ring-4 focus:ring-blue-400/20 transition-all">
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-[var(--accent-text)] mb-2 flex items-center gap-2">
                        📄 Description
                    </label>
                    <textarea id="eventDescription" name="description" rows="3"
                              class="glass-input w-full rounded-2xl px-5 py-4 text-gray-800 resize-vertical border-2 border-white/30 focus:border-[var(--accent)]/50 focus:ring-4 focus:ring-[var(--accent)]/20 transition-all"
                              placeholder="Détails de l'événement, notes importantes..."></textarea>
                </div>
                
                <!-- Boutons Action -->
                <div class="flex gap-3 pt-6 border-t border-white/20">
                    <button type="submit" class="glass-button flex-1 py-4 px-6 font-black text-lg text-white shadow-2xl hover:shadow-3xl hover:-translate-y-1 transition-all rounded-2xl bg-gradient-to-r from-[var(--accent)] to-[var(--accent-2)]">
                        💾 Enregistrer
                    </button>
                    <button type="button" onclick="closeModal()" 
                            class="glass-input flex-1 py-4 px-6 font-bold text-lg text-gray-800 hover:bg-gray-200/50 hover:shadow-xl transition-all rounded-2xl border-2 border-gray-300/50">
                        ❌ Annuler
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>




    </div>{{-- /home-main-grid --}}

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
let calendar;

// 🔓 FONCTIONS MODAL
function openModal(eventData = null) {
    const modal = document.getElementById('eventModal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('eventForm');

    if (eventData && eventData.extendedProps?.type !== 'internship') { // Protège les stages
        // Mode édition
        modalTitle.textContent = 'Modifier Événement';
        document.getElementById('eventId').value = eventData.id;
        document.getElementById('eventTitle').value = eventData.title;
        document.getElementById('eventDescription').value = eventData.extendedProps?.description || '';
        document.getElementById('eventStart').value = formatDateForInput(eventData.start);
        document.getElementById('eventEnd').value = formatDateForInput(eventData.end || eventData.start);
    } else {
        // Mode création
        modalTitle.textContent = 'Nouvel Événement';
        form.reset();
        document.getElementById('eventId').value = '';
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('eventModal').classList.add('hidden');
    document.getElementById('eventForm').reset();
}

function formatDateForInput(date) {
    if (!date) return '';
    const d = new Date(date);
    return d.toISOString().slice(0, 16);
}

// 🚀 INITIALISATION FULLCALENDAR
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    
    // Événements depuis PHP (Google Calendar)
    const allEvents = @json($events ?? []);

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        editable: false,
        selectable: true,
        height: 700,
        locale: 'fr',
        timeZone: 'Africa/Tunis',
        events: allEvents,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            today: 'Aujourd’hui',
            month: 'Mois',
            week: 'Semaine',
            list: 'Liste'
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        eventDidMount: function(info) {
            const type = info.event.extendedProps?.type;
            let badge = '';
            
            // Badges colorés
            if (type === 'applied') {
                badge = '<span class="ml-2 px-2 py-1 bg-orange-500 text-white text-xs rounded-full font-bold">Candidature</span>';
            } else if (type === 'response') {
                badge = '<span class="ml-2 px-2 py-1 bg-green-500 text-white text-xs rounded-full font-bold">Réponse</span>';
            } else if (type === 'google') {
                badge = '<span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded-full font-bold">Google</span>';
            }
            
            info.el.innerHTML += badge;
            
            // Tooltip description
            if (info.event.extendedProps?.description) {
                info.el.title = info.event.extendedProps.description;
            }
        },
        
        // Clic date → Créer événement
        dateClick: function(info) {
            openModal();
        },
        
        // Clic événement
        eventClick: function(info) {
            const type = info.event.extendedProps?.type;
            
            if (type === 'applied' || type === 'response') {
                // Stage → Alerte info seulement
                alert(`💼 STAGE\n\n${info.event.title}\n\n${info.event.extendedProps?.description || ''}\n\n👆 Modifiez dans /internship`);
            } else {
                // Google Event → Modifier/Supprimer
                if (confirm('Que voulez-vous faire ?\n\nOK = Modifier\nAnnuler = Supprimer')) {
                    openModal(info.event);
                } else {
                    if (confirm('Supprimer définitivement ?')) {
                        deleteEvent(info.event.id);
                    }
                }
            }
        }
    });

    calendar.render();

    // Gestion formulaire
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const eventId = document.getElementById('eventId').value;
        
        const eventData = {
            title: document.getElementById('eventTitle').value,
            description: document.getElementById('eventDescription').value,
            start: document.getElementById('eventStart').value + ':00',
            end: document.getElementById('eventEnd').value + ':00'
        };

        if (eventId) {
            updateEvent(eventId, eventData);
        } else {
            createEvent(eventData);
        }
    });
});

// 💾 CREATE EVENT
function createEvent(eventData) {
    fetch('/calendar/events', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(eventData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            calendar.addEvent({
                id: data.id,
                title: data.title,
                start: data.start,
                end: data.end
            });
            closeModal();
            alert('✅ Événement créé !');
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(err => alert('❌ Erreur lors de la création'));
}

// ✏️ UPDATE EVENT
function updateEvent(eventId, eventData) {
    fetch(`/calendar/events/${eventId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(eventData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const event = calendar.getEventById(eventId);
            if (event) {
                event.setProp('title', data.title);
                event.setStart(data.start);
                event.setEnd(data.end);
            }
            closeModal();
            alert('✅ Événement modifié !');
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(err => alert('❌ Erreur lors de la modification'));
}

// 🗑️ DELETE EVENT
function deleteEvent(eventId) {
    fetch(`/calendar/events/${eventId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const event = calendar.getEventById(eventId);
            if (event) event.remove();
            alert('✅ Événement supprimé !');
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(err => alert('❌ Erreur lors de la suppression'));
}
</script>
{{-- fadeInUp + .animate-fade-in are defined in the <head> style block above --}}

    </div>{{-- /content wrapper --}}
</div>{{-- /home-app-shell --}}
</body>
</html>
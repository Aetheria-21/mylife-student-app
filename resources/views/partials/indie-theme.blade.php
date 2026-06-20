<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@400;500;700;800&display=swap" rel="stylesheet">
<style>
    /* ══════════════════════════════════════════════
       MYLIFE — Editorial Theme
       Warm cream · Rose accents · Serif elegance
    ══════════════════════════════════════════════ */

    /* ── Reset ──────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }

    /* ── CSS Variables ───────────────────────────── */
    :root {
        --page-bg:       #fffaf8;
        --surface:       #ffffff;
        --surface-alt:   #fff5f7;
        --line:          #f0dfe2;
        --line-strong:   #e8c7cd;
        --text-main:     #5f4b4b;
        --text-heading:  #4c3d3d;
        --text-muted:    #8c6f6f;
        --accent:        #e7b8c0;
        --accent-2:      #ef9fb0;
        --accent-deep:   #d4849a;
        --accent-soft:   #f8e9ec;
        --accent-contrast: #ffffff;
        --shadow-main:   0 6px 18px rgba(0,0,0,0.06);
        --shadow-hover:  0 12px 32px rgba(0,0,0,0.10);
        --tile-grad:     linear-gradient(180deg, #f6b9c4, #ef9fb0);
    }

    /* 🔵 Male theme — bleu (requested) */
    .theme-male {
        --page-bg:      #f6fbff;
        --surface-alt:  #f0f7ff;
        --line:         #d7e6ff;
        --line-strong:  #bcd4ff;
        --text-main:    #2f3a56;
        --text-heading: #25324e;
        --text-muted:   #516185;
        --accent:       #7ab0ff;
        --accent-2:     #4f93ff;
        --accent-deep:  #1f66ff;
        --accent-soft:  #e9f3ff;
        --tile-grad:    linear-gradient(180deg, #9cc9ff, #5aa0ff);
    }

    /* 🌸 Female theme — warm rose / blush pink */
    .theme-female {
        --page-bg:      #fffaf8;
        --surface-alt:  #fff5f7;
        --line:         #f0dfe2;
        --line-strong:  #e8c7cd;
        --text-main:    #5f4b4b;
        --text-heading: #4c3d3d;
        --text-muted:   #8c6f6f;
        --accent:       #e7b8c0;
        --accent-2:     #ef9fb0;
        --accent-deep:  #d4849a;
        --accent-soft:  #f8e9ec;
        --tile-grad:    linear-gradient(180deg, #f6b9c4, #ef9fb0);
    }

    /* ── Body & page wrapper ─────────────────────── */
    body, body.indie-page {
        margin: 0;
        font-family: "Georgia", "Lora", "Times New Roman", serif;
        background: var(--page-bg);
        color: var(--text-main);
        line-height: 1.6;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .indie-shell, .page {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        background: var(--surface);
        padding: 0 0 3rem;
    }

    /* ── Banner ──────────────────────────────────── */
    .page-banner {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--accent-soft) 0%, var(--line) 100%);
        border-bottom: 3px solid var(--line-strong);
        position: relative;
        display: flex;
        align-items: flex-end;
        padding: 0 40px 22px;
        overflow: hidden;
    }
    .page-banner::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(255,255,255,0.18);
    }
    .page-banner-content { position: relative; z-index: 1; }

    /* ── Page header ─────────────────────────────── */
    .page-header { padding: 30px 40px 16px; border-bottom: 2px solid var(--line); margin-bottom: 24px; }
    .page-header h1, .indie-title, h1.indie-heading, .home-page-title {
        font-family: "Georgia", "Lora", serif;
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 700;
        color: var(--text-heading);
        margin-bottom: 8px;
        letter-spacing: -0.01em;
        line-height: 1.25;
    }
    .page-header p, .page-subtitle {
        font-style: italic;
        color: var(--text-muted);
        font-size: 15px;
        border-left: 3px solid var(--line-strong);
        padding-left: 12px;
        max-width: 800px;
    }
    .indie-kicker {
        font-family: "DM Sans", sans-serif;
        color: var(--accent-deep);
        text-transform: uppercase;
        letter-spacing: 0.26em;
        font-weight: 700;
        font-size: 0.75rem;
        margin-bottom: 6px;
    }

    /* ── Cards ───────────────────────────────────── */
    .indie-panel, .glass-card, .glass-panel {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 18px;
        box-shadow: var(--shadow-main);
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .indie-panel:hover, .glass-card:hover, .glass-panel:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
    }

    /* Inner soft panel — section blocks */
    .indie-soft-panel, .side-block, .right-block {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 16px;
        padding: 16px;
        box-shadow: var(--shadow-main);
    }

    /* ── Section titles ──────────────────────────── */
    .section-title, .indie-section-title {
        font-family: "DM Sans", sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-heading);
        background: var(--accent-soft);
        padding: 6px 12px;
        border-radius: 8px;
        margin-bottom: 12px;
        display: inline-block;
    }

    /* ── Date / stat tiles ───────────────────────── */
    .date-tile, .stat-tile {
        background: var(--tile-grad);
        color: #fff;
        border-radius: 16px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        box-shadow: inset 0 1px 0 rgba(255,255,255,.35), var(--shadow-main);
        text-align: center;
    }
    .date-tile .big  { font-size: 2.4rem; line-height: 1; font-family: "Georgia", serif; }
    .date-tile .small { font-size: 11px; text-transform: uppercase; opacity: 0.9; margin-top: 6px; letter-spacing: 0.1em; }

    /* ── Buttons ─────────────────────────────────── */
    .indie-button, .glass-button, .accent-button {
        background: var(--tile-grad);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: "DM Sans", sans-serif;
        font-weight: 700;
        font-size: 14px;
        padding: 10px 22px;
        cursor: pointer;
        box-shadow: var(--shadow-main);
        transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }
    .indie-button:hover, .glass-button:hover, .accent-button:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        filter: brightness(1.05);
    }
    .indie-button-secondary, .secondary-button {
        background: var(--surface);
        color: var(--text-main);
        border: 1.5px solid var(--line-strong);
        border-radius: 10px;
        font-family: "DM Sans", sans-serif;
        font-weight: 700;
        font-size: 13px;
        padding: 8px 18px;
        cursor: pointer;
        transition: background 0.2s ease, border-color 0.2s ease;
    }
    .indie-button-secondary:hover, .secondary-button:hover {
        background: var(--accent-soft);
        border-color: var(--accent-deep);
        color: var(--text-heading);
    }

    /* ── Inputs ──────────────────────────────────── */
    .indie-input, .glass-input {
        background: var(--surface) !important;
        color: var(--text-main);
        border: 1.5px solid var(--line-strong) !important;
        border-radius: 10px;
        font-family: "Georgia", serif;
        font-size: 14px;
        padding: 10px 14px;
        width: 100%;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .indie-input:focus, .glass-input:focus {
        outline: none;
        border-color: var(--accent-deep) !important;
        box-shadow: 0 0 0 3px rgba(231,184,192,0.25);
    }
    .indie-input::placeholder, .glass-input::placeholder { color: var(--text-muted); font-style: italic; }

    /* ── Links & pills ───────────────────────────── */
    .indie-link { color: var(--accent-deep); font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
    .indie-pill, .theme-badge {
        background: var(--accent-soft);
        color: var(--text-heading);
        border: 1px solid var(--line-strong);
        border-radius: 999px;
        padding: 3px 12px;
        font-size: 12px;
        font-family: "DM Sans", sans-serif;
        font-weight: 700;
    }

    /* ── Separator ───────────────────────────────── */
    .separator, hr.indie-sep {
        height: 2px;
        background: var(--line-strong);
        border: none;
        border-radius: 999px;
        opacity: 0.7;
        margin: 20px 40px;
    }

    /* ── Progress bar ────────────────────────────── */
    .indie-progress {
        width: 100%; height: 8px;
        background: var(--accent-soft);
        border-radius: 999px;
        overflow: hidden;
        border: 1px solid var(--line);
    }
    .indie-progress-bar {
        height: 100%;
        background: var(--tile-grad);
        border-radius: 999px;
        transition: width 0.8s ease;
    }

    /* ── Checklist & lists ───────────────────────── */
    .checklist, .work-list { list-style: none; padding: 0; margin: 0; }
    .checklist li, .work-list li {
        font-size: 13px;
        color: var(--text-muted);
        margin-bottom: 9px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .checklist input[type="checkbox"] { accent-color: var(--accent-deep); }

    /* ── Tables ──────────────────────────────────── */
    .indie-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .indie-table thead { background: var(--accent-soft); }
    .indie-table th { color: var(--text-muted); font-weight: 700; padding: 10px 14px; text-align: left; font-family: "DM Sans", sans-serif; }
    .indie-table td { padding: 10px 14px; border-bottom: 1px solid var(--line); color: var(--text-main); }
    .indie-table tbody tr:hover { background: var(--surface-alt); }

    /* ── Modal card ──────────────────────────────── */
    .indie-modal-card {
        background: var(--surface);
        border: 1px solid var(--line-strong);
        border-radius: 18px;
        box-shadow: var(--shadow-hover);
    }

    /* ── Glass glow (compat) ─────────────────────── */
    .glass-glow, .hover\:glass-glow:hover {
        box-shadow: 0 0 0 2px var(--accent-deep), var(--shadow-hover) !important;
    }
    .aero-glow { box-shadow: var(--shadow-main); }

    /* ── Responsive padding ──────────────────────── */
    @media (max-width: 1024px) {
        .page-header { padding: 22px 20px 14px; }
        .separator, hr.indie-sep { margin: 16px 20px; }
    }
    @media (max-width: 640px) {
        .page-header { padding: 18px 16px 12px; }
        .page-banner { height: 130px; padding: 0 16px 14px; }
        .indie-shell, .page { padding-bottom: 2rem; }
    }

    summary::-webkit-details-marker { display: none; }

    /* ═══════════════════════════════════════════
       SHARED NAV SIDEBAR — used across all pages
    ═══════════════════════════════════════════ */
    .page-layout {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }
    .nav-sidebar {
        flex: 0 0 250px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: linear-gradient(135deg, var(--accent-soft) 0%, var(--line) 100%);
        border: 1px solid var(--line-strong);
        border-radius: 18px;
        padding: 16px;
        box-shadow: var(--shadow-main);
    }
    .main-content {
        flex: 1;
        min-width: 0;
    }
    .home-nav-grid {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .home-nav-tile {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 20px 16px;
        text-align: center;
        text-decoration: none;
        border: 1px solid var(--line);
        border-radius: 16px;
        background: var(--surface);
        box-shadow: var(--shadow-main);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        min-height: 76px;
        color: inherit;
    }
    .home-nav-tile:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-hover);
        border-color: var(--accent-deep);
        background: var(--accent-soft);
    }
    .home-nav-tile .nav-icon {
        font-size: 2rem;
        line-height: 1;
        transition: transform 0.2s ease;
    }
    .home-nav-tile:hover .nav-icon { transform: scale(1.18); }
    .home-nav-tile .nav-label {
        font-family: "DM Sans", sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-heading);
        line-height: 1.3;
    }
    .home-nav-tile.active {
        border-color: var(--accent-deep);
        background: var(--accent-soft);
    }

    @media (max-width: 1024px) {
        .page-layout { flex-direction: column; }
        .nav-sidebar {
            flex: none;
            order: -1;
            width: 100%;
        }
        .home-nav-grid {
            flex-direction: row !important;
            flex-wrap: wrap;
        }
        .home-nav-tile {
            flex: 1 1 30%;
            min-height: 68px;
        }
    }
    @media (max-width: 640px) {
        .page-layout { gap: 16px; }
        .home-nav-tile {
            flex: 1 1 45%;
            min-height: 60px;
            padding: 14px 8px;
        }
        .home-nav-tile .nav-icon { font-size: 1.5rem; }
        .home-nav-tile .nav-label { font-size: 0.8rem; }
    }
</style>

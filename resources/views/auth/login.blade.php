<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    @include('partials.indie-theme')
    <style>
        .login-page {
            background-image: linear-gradient(rgba(255,255,255,0.28), rgba(255,255,255,0.18)), url('/images/téléchargement.jpg');
            background-size: cover;
            background-position: center;
        }

        .login-card {
            max-width: 28rem;
            width: 100%;
            padding: 3rem 2.5rem;
            text-align: center;
        }

        .login-card h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: clamp(2.3rem, 4vw, 3rem);
            color: var(--text-main);
            margin-bottom: 1rem;
        }

        .login-card p,
        .login-card .footer { color: var(--text-muted); }
        .google-btn { display: flex; align-items: center; justify-content: center; gap: 1rem; text-decoration: none; padding: 1.1rem 1.5rem; width: 100%; }
        .google-icon { width: 1.8rem; }
    </style>

</head>
<body class="indie-page theme-male login-page">
    
    <div class="indie-shell min-h-screen flex items-center justify-center">
        <div class="indie-panel login-card">
            <p class="indie-kicker mb-4">MyLife • Édition Indie</p>
            <h2>Bienvenue ✨</h2>
            <p>Accédez à votre espace avec Google</p>

            <a href="{{ route('google.login') }}" class="google-btn indie-button">
                    <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" class="google-icon">
                    <span>Se connecter avec Google</span>
            </a>

            <div class="footer">
                Sécurisé • Simple • Intelligent
            </div>
        </div>
    </div>
</body>
</html>

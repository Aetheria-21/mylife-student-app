<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client; // ← important pour forcer SSL
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
        // stateless() pour éviter les problèmes de session en local
        return Socialite::driver('google')->scopes([
            'openid',
            'profile',
            'email',
            'https://www.googleapis.com/auth/calendar' // accès au Calendar
        ])->with([
            'access_type' => 'offline', // important pour obtenir refresh token
            'prompt' => 'consent'       // important pour forcer la demande de consent
        ])->redirect();
    }

    // Callback from Google
    public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')
        ->stateless()
        ->user();

    $user = User::updateOrCreate(
        ['email' => $googleUser->getEmail()],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt(Str::random(16)),
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken ?? null,
        ]
    );

    if ($googleUser->refreshToken) {
        $user->google_refresh_token = $googleUser->refreshToken;
        $user->save();
    }

    Auth::login($user);

    if (!$user->gender) {
        return redirect()->route('welcome');
    }

    session()->regenerate();

    return redirect('home');
}
}
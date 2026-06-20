<?php

namespace App\Http\Controllers;

use App\Models\UserEmotionTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load('emotionTips');

        $emotionMeta = [
            'happy'   => ['label' => 'Heureux',  'emoji' => '😊'],
            'sad'     => ['label' => 'Triste',   'emoji' => '😢'],
            'angry'   => ['label' => 'En colère','emoji' => '😠'],
            'neutral' => ['label' => 'Neutre',   'emoji' => '😐'],
        ];

        // Build the current tips array (defaults merged with user's saved tips)
        $emotionAdvice = UserEmotionTip::defaultTips();
        foreach ($user->emotionTips as $tip) {
            if (isset($emotionAdvice[$tip->emotion][$tip->tip_order - 1])) {
                $emotionAdvice[$tip->emotion][$tip->tip_order - 1] = $tip->tip_text;
            }
        }

        return view('profile.index', compact('user', 'emotionMeta', 'emotionAdvice'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'age'    => 'nullable|integer|min:1|max:120',
            'gender' => 'required|in:male,female',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Store avatar directly in public/avatars/ (no symlink needed on XAMPP)
        if ($request->hasFile('avatar')) {
            $avatarsDir = public_path('avatars');
            if (!is_dir($avatarsDir)) {
                mkdir($avatarsDir, 0755, true);
            }

            // Delete old avatar file if it exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            $ext      = $request->file('avatar')->getClientOriginalExtension();
            $filename = 'avatars/' . uniqid('avatar_', true) . '.' . $ext;
            $request->file('avatar')->move($avatarsDir, basename($filename));
            $validated['avatar'] = $filename;
        }

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('profile_status', '✅ Profil mis à jour avec succès !');
    }
}

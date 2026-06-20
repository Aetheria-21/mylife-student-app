<?php

namespace App\Http\Controllers;

use App\Models\UserEmotionTip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('emotionTips');
        $emotionTips = $this->resolveEmotionTips($user);

        return view('auth.welcome', compact('user', 'emotionTips'));
    }

    public function setGender(Request $request)
    {
        $validated = $request->validate($this->preferenceRules());

        $user = Auth::user();
        $this->persistPreferences($user, $validated);

        return response()->json([
            'success' => true,
            'gender' => $user->gender,
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate(array_merge([
            'name' => 'required|string|max:255',
        ], $this->preferenceRules()));

        $user = Auth::user();

        $this->persistPreferences($user, $validated, [
            'name' => trim((string) $validated['name']),
        ]);

        return redirect()
            ->route('profile.show')
            ->with('profile_status', '✅ Conseils d\'humeur enregistrés avec succès !');
    }

    private function resolveEmotionTips($user): array
    {
        $tips = UserEmotionTip::defaultTips();

        foreach ($user->emotionTips as $tip) {
            if (isset($tips[$tip->emotion][$tip->tip_order - 1])) {
                $tips[$tip->emotion][$tip->tip_order - 1] = $tip->tip_text;
            }
        }

        return $tips;
    }

    private function preferenceRules(): array
    {
        return [
            'gender' => 'required|in:male,female',
            'emotion_tips' => 'required|array',
            'emotion_tips.happy' => 'required|array|size:5',
            'emotion_tips.happy.*' => 'required|string|max:255',
            'emotion_tips.sad' => 'required|array|size:5',
            'emotion_tips.sad.*' => 'required|string|max:255',
            'emotion_tips.angry' => 'required|array|size:5',
            'emotion_tips.angry.*' => 'required|string|max:255',
            'emotion_tips.neutral' => 'required|array|size:5',
            'emotion_tips.neutral.*' => 'required|string|max:255',
        ];
    }

    private function persistPreferences($user, array $validated, array $extraUserData = []): void
    {
        DB::transaction(function () use ($user, $validated, $extraUserData) {
            $user->update(array_merge($extraUserData, [
                'gender' => $validated['gender'],
                'has_visited_welcome' => true,
            ]));

            $user->emotionTips()->delete();

            $tipRows = [];
            foreach (UserEmotionTip::EMOTIONS as $emotion) {
                foreach ($validated['emotion_tips'][$emotion] ?? [] as $index => $tipText) {
                    $tipRows[] = [
                        'emotion' => $emotion,
                        'tip_order' => $index + 1,
                        'tip_text' => trim((string) $tipText),
                    ];
                }
            }

            $user->emotionTips()->createMany($tipRows);
        });
    }
}


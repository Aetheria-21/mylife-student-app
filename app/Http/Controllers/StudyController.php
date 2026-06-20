<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lesson;
use App\Models\Homework;

class StudyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $lessons = Lesson::with('homeworks')
            ->latest()
            ->get();

        $upcomingReminders = Homework::where('user_id', $user->id)
            ->whereNotNull('remind_at')
            ->where('is_done', false)
            ->where('remind_at', '>=', now())
            ->get(['id', 'title', 'due_date', 'remind_at']);

        // Map upcoming reminders to a plain array for JS
        $reminders = $upcomingReminders->map(function ($h) {
            return [
                'id' => $h->id,
                'title' => $h->title,
                'due_date' => $h->due_date?->toDateString(),
                'remind_at' => $h->remind_at?->toIso8601String(),
            ];
        });

        // Pass both lessons and reminders to Blade
        return view('study.index', compact('lessons', 'reminders'));
    }

    public function storeLesson(Request $request)
    {
        $user = Auth::user();

        Lesson::create([
            'user_id' => $user->id,
            ...$request->only('title', 'description', 'date'),
        ]);

        return back();
    }

    public function storeHomework(Request $request)
    {
        $user = Auth::user();

        // Sécurité: s'assurer que lesson_id appartient à l'utilisateur
        Lesson::where('user_id', $user->id)->findOrFail($request->lesson_id);

        Homework::create([
            'lesson_id' => $request->lesson_id,
            'user_id' => $user->id,
            'title' => $request->title,
            'due_date' => $request->due_date ?: null,
            'remind_at' => $request->remind_at ?: null,
        ]);

        return back();
    }

    public function toggleHomework($id)
    {
        $user = Auth::user();

        $hw = Homework::where('user_id', $user->id)->findOrFail($id);
        $hw->is_done = !$hw->is_done;
        $hw->save();

        return back();
    }

    public function deleteLesson($id)
    {
        $user = Auth::user();

        Lesson::where('user_id', $user->id)->findOrFail($id)->delete();
        return back();
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CleanTaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()
            ->where('category', 'chores')
            ->orderBy('status')          // pending first
            ->orderBy('created_at')
            ->get();

        return view('cleantask.index', compact('tasks'));
    }

    public function importantTasks()
    {
        $tasks = Auth::user()->tasks()
            ->where('is_important', true)
            ->pluck('title');

        return response()->json($tasks);
    }

    public function toggleStatus(Request $request, $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $isCompleted = $task->status === 'completed';
        $task->status       = $isCompleted ? 'pending' : 'completed';
        $task->completed_at = $isCompleted ? null : now();
        $task->save();

        return response()->json(['success' => true, 'status' => $task->status]);
    }
}

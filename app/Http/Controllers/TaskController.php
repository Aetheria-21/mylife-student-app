<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $selectedCategory = $request->query('category', 'all');
        $selectedPriority = $request->query('priority', 'all');
        $user = Auth::user();

        $tasksQuery = $user->tasks()
            ->orderByRaw("CASE WHEN status = 'completed' THEN 1 ELSE 0 END")
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 ELSE 4 END")
            ->orderBy('due_date')
            ->latest();

        if ($selectedCategory !== 'all') {
            $tasksQuery->where('category', $selectedCategory);
        }

        if ($selectedPriority !== 'all') {
            $tasksQuery->where('priority', $selectedPriority);
        }

        return view('tasks.index', [
            'tasks' => $tasksQuery->get(),
            'selectedCategory' => $selectedCategory,
            'selectedPriority' => $selectedPriority,
            'categories' => Task::CATEGORIES,
            'categoryIcons' => Task::CATEGORY_ICONS,
            'priorities' => Task::PRIORITIES,
            'priorityColors' => Task::PRIORITY_COLORS,
            'priorityIcons' => Task::PRIORITY_ICONS,
            'taskCounts' => $user->tasks()->selectRaw('category, COUNT(*) as total')->groupBy('category')->pluck('total', 'category'),
            'pendingCount' => $user->tasks()->where('status', 'pending')->count(),
            'completedCount' => $user->tasks()->where('status', 'completed')->count(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        Auth::user()->tasks()->create($validated + [
            'status' => 'pending',
        ]);

        return redirect()
            ->route('tasks.index', ['category' => $validated['category']])
            ->with('task_status', 'Tâche créée avec succès.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed',
        ]);

        $task = Auth::user()->tasks()->findOrFail($id);
        $task->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        return redirect()->back()->with('task_status', 'Tâche mise à jour avec succès.');
    }

    public function destroy(string $id)
    {
        $task = Auth::user()->tasks()->findOrFail($id);
        $task->delete();

        return redirect()->back()->with('task_status', 'Tâche supprimée avec succès.');
    }

    private function rules(): array
    {
        $categories = implode(',', array_keys(Task::CATEGORIES));
        $priorities = implode(',', array_keys(Task::PRIORITIES));

        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => "required|in:$categories",
            'priority' => "required|in:$priorities",
            'due_date' => 'nullable|date',
        ];
    }
}
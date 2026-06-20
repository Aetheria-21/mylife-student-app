<?php
// app/Http/Controllers/HobbyController.php
namespace App\Http\Controllers;

use App\Models\Hobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HobbyController extends Controller
{
    public function index()
    {
        $hobbies = Auth::user()->hobbies()->latest()->get();
        return view('hobbies.index', compact('hobbies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'nullable|string|max:50',
            'frequency' => 'nullable|string|max:50',
        ]);

        Auth::user()->hobbies()->create([
            'name' => $request->name,
            'level' => $request->level ?? '🎯 Débutant',
            'frequency' => $request->frequency ?? '📅 Quotidien',
        ]);

        return redirect()->back()->with('success', '🎉 Passion ajoutée : ' . $request->name);
    }
        public function toggle($id)
    {
        $hobby = Hobby::findOrFail($id);

        $hobby->status = $hobby->status == 'active' ? 'paused' : 'active';

        $hobby->save();

        return redirect()->back();
    }
        public function destroy($id)
    {
        $hobby = Hobby::findOrFail($id);
        $hobby->delete();

        return redirect()->back();
    }
}
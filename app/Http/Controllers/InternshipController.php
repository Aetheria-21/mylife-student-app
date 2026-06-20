<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Internship;
use Illuminate\Support\Facades\Http;

class InternshipController extends Controller
{
    public function index()
    {
        $internships = Internship::where('user_id', auth()->id())->get();

        $careerPath = [
            ['name'=>'HTML', 'description'=>'Learn HTML basics'],
            ['name'=>'CSS', 'description'=>'Style your website'],
            ['name'=>'JS', 'description'=>'Add interactivity'],
            ['name'=>'Laravel', 'description'=>'Full-stack PHP framework']
        ];

        return view('internship.index', compact('internships','careerPath'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'date_applied' => 'nullable|date',
            'response_date' => 'nullable|date|after_or_equal:date_applied',
            'status' => 'nullable|string|in:Pending,Accepted,Rejected',
        ]);

        $validated['user_id'] = auth()->id();
        Internship::create($validated);
        return redirect()->back()->with('success', 'Stage ajouté avec succès !');
    }

    public function edit(Internship $internship)
    {
        return view('internship.edit', compact('internship'));
    }

    public function update(Request $request, Internship $internship)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'date_applied' => 'nullable|date',
            'response_date' => 'nullable|date|after_or_equal:date_applied',
            'status' => 'nullable|string|in:Pending,Accepted,Rejected',
        ]);

        $internship->update($validated);
        return redirect()->route('internship.index')->with('success', 'Stage modifié avec succès !');
    }

    public function destroy($id)
    {
        Internship::findOrFail($id)->delete();
        return redirect()->back();
    }


}
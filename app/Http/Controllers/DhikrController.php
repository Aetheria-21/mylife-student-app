<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dhikr;

class DhikrController extends Controller
{
    public function index()
    {
        $categories = Dhikr::select('category')->distinct()->get();
        return view('dhikr.index', compact('categories'));
    }

    public function show($category)
    {
        $adhkar = Dhikr::where('category', $category)->get();
        return view('dhikr.show', compact('adhkar', 'category'));
    }
}
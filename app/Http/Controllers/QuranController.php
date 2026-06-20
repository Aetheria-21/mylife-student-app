<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Cache;

class QuranController extends Controller
{
    // 📖 قائمة السور
    public function index()
    {
        $surahs = Cache::remember('surahs', 3600, function () {
            return Http::get('https://api.alquran.cloud/v1/surah')->json()['data'];
        });

        return view('quran.index', compact('surahs'));
    }

    // 📜 عرض سورة + ترجمة + صوت
    public function show($id)
    {
        $response = Http::get("https://api.alquran.cloud/v1/surah/$id/editions/quran-uthmani,fr.hamidullah");

        $data = $response->json()['data'];

        $arabic = $data[0]['ayahs'];
        $english = $data[1]['ayahs'];
        $name = $data[0]['englishName'];

        return view('quran.show', compact('arabic', 'english', 'name', 'id'));
    }
}

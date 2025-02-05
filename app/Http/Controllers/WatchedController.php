<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Watched;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WatchedController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'tmdb_id' => 'required',
            'title' => 'required',
            'poster_path' => 'nullable',
            'type' => 'required|in:movie,tvseries',
            'vote_average' => 'required|numeric',
            'release_date' => 'required|date',
        ]);

        // Log data untuk pengecekan
        Log::info($request->all()); // Pastikan data vote_average dan release_date ada

        Watched::create([
            'user_id' => Auth::id(),
            'tmdb_id' => $request->tmdb_id,
            'title' => $request->title,
            'poster_path' => $request->poster_path,
            'type' => $request->type,
            'vote_average' => $request->vote_average,  // Pastikan nilai yang diterima adalah benar
            'release_date' => $request->release_date,  // Pastikan nilai yang diterima adalah benar
        ]);

        return response()->json(['message' => 'Added to watched list']);
    }



    public function index()
    {
        $watched = Watched::where('user_id', Auth::id())->get();
        return view('profile', compact('watched'));
    }
}

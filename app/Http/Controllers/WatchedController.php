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
        $validatedData = $request->validate([
            'tmdb_id' => 'required',
            'title' => 'required',
            'poster_path' => 'nullable',
            'type' => 'required|in:movie,tv',
            'vote_average' => 'nullable|numeric',
            'release_date' => 'nullable|date',
            'rating' => 'required|integer|min:1|max:5', // Tambah validasi rating
            'review' => 'nullable|string|max:500', // Tambah validasi review
        ]);

        // Cek jika tmdb_id sudah ada di tabel watched untuk user yang sedang login
        $existing = Watched::where('tmdb_id', $validatedData['tmdb_id'])
            ->where('user_id', Auth::id()) // Pastikan hanya memeriksa data user yang sedang login
            ->first();

        // Jika sudah ada, kembalikan response dengan pesan error
        if ($existing) {
            return response()->json(['message' => 'Tontonan sudah ada dalam daftar watched.'], 400);
        }

        try {
            // Simpan data ke tabel watched
            Watched::create([
                'user_id' => Auth::id(),
                'tmdb_id' => $validatedData['tmdb_id'],
                'title' => $validatedData['title'],
                'poster_path' => $validatedData['poster_path'],
                'type' => $validatedData['type'],
                'vote_average' => $validatedData['vote_average'],
                'release_date' => $validatedData['release_date'],
                'rating' => $validatedData['rating'],
                'review' => $validatedData['review'],
            ]);

            return response()->json(['message' => 'Added to watched list']);
        } catch (\Exception $e) {
            Log::error("Error adding to watched list: " . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan'], 500);
        }
    }


    public function check(Request $request)
    {
        $tmdb_id = $request->query('tmdb_id');

        $watched = Watched::where('tmdb_id', $tmdb_id)
            ->where('user_id', Auth::id())
            ->exists();

        return response()->json(['watched' => $watched]);
    }


    public function destroy(Request $request)
    {
        $tmdb_id = $request->input('tmdb_id');

        $watched = Watched::where('tmdb_id', $tmdb_id)->first();
        if ($watched) {
            $watched->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found.']);
    }

    public function index()
    {
        $watched = Watched::where('user_id', Auth::id())->get();
        return view('profile', compact('watched'));
    }
}

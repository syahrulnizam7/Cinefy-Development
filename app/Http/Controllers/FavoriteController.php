<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tmdb_id' => 'required',
            'title' => 'required',
            'poster_path' => 'nullable',  // Tidak wajib
            'type' => 'required|in:movie,tv',
            'vote_average' => 'nullable|numeric', // Tidak wajib, tapi jika ada harus numeric
            'release_date' => 'nullable|date',    // Tidak wajib, tapi jika ada harus bertipe date
        ]);

        // Cek jika tmdb_id sudah ada di tabel favorite untuk user yang sedang login
        $existing = Favorite::where('tmdb_id', $validatedData['tmdb_id'])
            ->where('user_id', Auth::id()) // Pastikan hanya memeriksa data user yang sedang login
            ->first();

        // Jika sudah ada, kembalikan response dengan pesan error
        if ($existing) {
            return response()->json(['message' => 'Tontonan sudah ada dalam daftar favorite.'], 400);
        }

        try {
            // Simpan data ke tabel favorite
            Favorite::create([
                'user_id' => Auth::id(),
                'tmdb_id' => $validatedData['tmdb_id'],
                'title' => $validatedData['title'],
                'poster_path' => $validatedData['poster_path'],
                'type' => $validatedData['type'],
                'vote_average' => $validatedData['vote_average'],
                'release_date' => $validatedData['release_date'],
            ]);

            return response()->json(['message' => 'Added to favorite list']);
        } catch (\Exception $e) {
            Log::error("Error adding to favorite list: " . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan'], 500);
        }
    }


    public function check(Request $request)
    {
        $tmdb_id = $request->query('tmdb_id');

        $favorite = Favorite::where('tmdb_id', $tmdb_id)
            ->where('user_id', Auth::id())
            ->exists();

        return response()->json(['favorite' => $favorite]);
    }


    public function destroy(Request $request)
    {
        $tmdb_id = $request->input('tmdb_id');

        $favorite = Favorite::where('tmdb_id', $tmdb_id)->first();
        if ($favorite) {
            $favorite->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Item not found.']);
    }

    public function index()
    {
        $favorite = Favorite::where('user_id', Auth::id())->get();
        return view('profile', compact('favorite'));
    }
}

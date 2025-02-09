<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id); // Cari pengguna berdasarkan ID
    
        // Pastikan data selalu berupa array
        $watched = $user->watchedMovies ?? [];
        $watchlist = $user->watchlistMovies ?? [];
        $favorite = $user->favoriteMovies ?? [];
    
        $watchedCount = count($watched);
        $watchlistCount = count($watchlist);
        $favoriteCount = count($favorite);
    
        return view('userdetail', compact('user', 'watched', 'watchlist', 'favorite', 'watchedCount', 'watchlistCount', 'favoriteCount'));
    }
    
}

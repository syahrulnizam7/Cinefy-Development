<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Watched; // Tambahkan import model Watched
use App\Models\Watchlist; // Tambahkan import model Watched

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user(); // Get logged-in user data
        $watched = Watched::where('user_id', $user->id)->get(); // Fetch watched items based on user_id
        $watchedCount = $watched->count(); // Count the number of watched items
        
        // Fetch the watchlist items
        $watchlist = Watchlist::where('user_id', $user->id)->get(); // Fetch watchlist items based on user_id
        $watchlistCount = $watchlist->count(); // Count the number of watchlist items

        return view('profile', compact('user', 'watched', 'watchedCount', 'watchlist', 'watchlistCount'));
    }
}

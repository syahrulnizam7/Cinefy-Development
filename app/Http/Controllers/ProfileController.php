<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Watched; // Tambahkan import model Watched
use App\Models\Watchlist; // Tambahkan import model Watched
use App\Models\User;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
         // Get logged-in user data
        $watched = Watched::where('user_id', $user->id)->get(); // Fetch watched items based on user_id
        $watchedCount = $watched->count(); // Count the number of watched items

        $watchlist = Watchlist::where('user_id', $user->id)->get(); // Fetch watchlist items based on user_id
        $watchlistCount = $watchlist->count(); // Count the number of watchlist items
      
        // Fetch the watchlist items
        $favorite = Favorite::where('user_id', $user->id)->get(); // Fetch watchlist items based on user_id
        $favoriteCount = $favorite->count(); // Count the number of watchlist items

        return view('profile', compact('user', 'watched', 'watchedCount', 'watchlist', 'watchlistCount','favorite','favoriteCount'));
    }
    public function saveProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        /** @var \App\Models\User $user **/
        $user = Auth::user();


        // Simpan foto profil jika ada
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->username = $request->username;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}

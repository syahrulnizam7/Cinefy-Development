<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Watched; // Tambahkan import model Watched
use App\Models\Watchlist; // Tambahkan import model Watched
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();

        // Fetch watched items and order by the latest first
        $watched = Watched::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $watchedCount = $watched->count();

        // Fetch watchlist items and order by the latest first
        $watchlist = Watchlist::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $watchlistCount = $watchlist->count();

        // Fetch favorite items and order by the latest first
        $favorite = Favorite::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $favoriteCount = $favorite->count();
        

        return view('profile', compact('user', 'watched', 'watchedCount', 'watchlist', 'watchlistCount', 'favorite', 'favoriteCount'));
    }



    public function saveProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'cropped_image' => 'nullable|string',
        ]);

        $user = User::find(Auth::user()->id);
        $user->username = $request->username;

        if ($request->filled('cropped_image')) {
            $image = $request->cropped_image;
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'profile_photos/' . uniqid() . '.jpg';

            // Simpan gambar menggunakan Storage
            Storage::disk('public')->put($imageName, base64_decode($image));

            // Simpan path ke database
            $user->profile_photo = $imageName;
        }

        $user->save();

        return redirect('/')->with('success', 'Profile updated successfully!');
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('profile-edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->username = $request->username;

        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}

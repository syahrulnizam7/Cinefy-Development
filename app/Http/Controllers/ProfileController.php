<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Watched; // Tambahkan import model Watched

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user(); // Mengambil data pengguna yang sedang login
        $watched = Watched::where('user_id', $user->id)->get(); // Ambil data watched berdasarkan user_id
        $watchedCount = $watched->count(); // Menghitung jumlah watched items

        return view('profile', compact('user', 'watched', 'watchedCount'));
    }
}

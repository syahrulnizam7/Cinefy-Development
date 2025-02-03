<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function showProfile()
    {
        $user = Auth::user(); // Mengambil data pengguna yang sedang login
        return view('profile', compact('user'));
    }
}

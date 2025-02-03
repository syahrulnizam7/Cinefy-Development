<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

Route::post('/logout', function () {
    Auth::logout();
    Session::invalidate(); // Bersihkan sesi
    Session::regenerateToken(); // Regenerasi token CSRF untuk meningkatkan keamanan
    return redirect('/'); // Arahkan pengguna setelah logout
})->name('logout');

Route::get('login', function () {
    return view('login'); // Ganti dengan nama blade login yang sesuai
})->name('login'); // Memberi nama route 'login'

// Proses login dengan Google callback
Route::get('login/google/callback', function () {
    $user = Socialite::driver('google')->user();

    // Cek apakah pengguna sudah terdaftar di database
    $existingUser = User::where('email', $user->getEmail())->first();

    if ($existingUser) {
        // Jika sudah ada, login ke sistem
        Auth::login($existingUser);
    } else {
        // Jika tidak ada, buat pengguna baru
        $newUser = User::create([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => bcrypt(Str::random(16)), // Menggunakan Str::random()
        ]);
        Auth::login($newUser);
    }

    // Redirect ke halaman setelah login (misalnya profile atau dashboard)
    return redirect()->route('profile');
})->name('google.callback');

Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');

Route::get('/', function () {
    $apiKey = config('services.tmdb.api_key');

    // Ambil daftar trending all (baik movie maupun TV series)
    $trendingResponse = Http::get(config('services.tmdb.base_url') . "trending/all/day", [
        'api_key' => $apiKey,
        'language' => 'id-ID',
    ]);
    
    $trending = $trendingResponse->json()['results'] ?? [];
    
    // Ambil daftar film terbaru (now playing)
    $latestTrailersResponse = Http::get(config('services.tmdb.base_url') . "movie/now_playing", [
        'api_key' => $apiKey,
        'language' => 'id-ID',
        'page' => 1
    ]);
    
    $latestTrailers = $latestTrailersResponse->json()['results'] ?? [];
    
    // Ambil video trailer untuk setiap film terbaru
    foreach ($latestTrailers as &$movie) {
        $videosResponse = Http::get(config('services.tmdb.base_url') . "movie/{$movie['id']}/videos", [
            'api_key' => $apiKey,
        ]);
        
        $videos = $videosResponse->json()['results'] ?? [];
        $trailer = collect($videos)->firstWhere('type', 'Trailer');
        $movie['trailer_key'] = $trailer ? $trailer['key'] : null;
    }
    
    return view('index', compact('trending', 'latestTrailers'));
});

// Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');


// Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movie.show');

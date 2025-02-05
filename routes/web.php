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

use App\Http\Controllers\WatchedController;

Route::middleware(['auth'])->group(function () {
    Route::post('/watched', [WatchedController::class, 'store'])->name('watched.store');
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile'); // PAKAI INI
});



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


Route::get('/api/search', function () {
    $query = request('q');
    if (!$query) return response()->json(['results' => []]);

    $apiKey = config('services.tmdb.api_key');

    $response = Http::get(config('services.tmdb.base_url') . 'search/multi', [
        'api_key' => $apiKey,
        'language' => 'id-ID',
        'query' => $query,
    ]);

    $results = collect($response->json()['results'] ?? [])
        ->filter(fn($item) => isset($item['title']) || isset($item['name']))
        ->map(fn($item) => [
            'id' => $item['id'],
            'title' => $item['title'] ?? $item['name'],
            'release_date' => $item['release_date'] ?? $item['first_air_date'] ?? '',
            'type' => $item['media_type'],
            // Pastikan gambar hanya ditambahkan satu kali
            'poster_path' => isset($item['poster_path']) && !empty($item['poster_path'])
                ? 'https://image.tmdb.org/t/p/w92' . $item['poster_path']
                : asset('images/noimg.png'),
            'average_rating' => $item['vote_average'] ?? 'N/A',
        ])
        ->values();

    return response()->json(['results' => $results]);
});





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



Route::get('/detail/{type}/{id}', function ($type, $id) {
    $apiKey = config('services.tmdb.api_key');

    if (!in_array($type, ['movie', 'tv'])) {
        abort(404);
    }

    // Ambil detail utama
    $detailResponse = Http::get(config('services.tmdb.base_url') . "{$type}/{$id}", [
        'api_key' => $apiKey,
        'language' => 'id-ID',
    ]);

    if ($detailResponse->failed()) {
        // Menangani jika ada kesalahan API
        abort(500, 'Terjadi kesalahan dalam mengambil data detail.');
    }

    $detail = $detailResponse->json();



    // Jika tagline atau overview kosong, coba ambil dari bahasa Inggris
    if (empty($detail['tagline']) || empty($detail['overview'])) {
        $detailResponseEn = Http::get(config('services.tmdb.base_url') . "{$type}/{$id}", [
            'api_key' => $apiKey,
            'language' => 'en-US',
        ]);
        $detailEn = $detailResponseEn->json();

        $detail['tagline'] = $detail['tagline'] ?: $detailEn['tagline'];
        $detail['overview'] = $detail['overview'] ?: $detailEn['overview'];
    }

    // Ambil informasi pemeran utama dan kru
    $creditsResponse = Http::get(config('services.tmdb.base_url') . "{$type}/{$id}/credits", [
        'api_key' => $apiKey,
    ]);
    $credits = $creditsResponse->json();

    // Ambil hanya 10 pemeran utama
    $cast = array_slice($credits['cast'], 0, 10);

    // Ambil sutradara & penulis dari crew
    $director = collect($credits['crew'])->firstWhere('job', 'Director');
    $writers = collect($credits['crew'])->whereIn('job', ['Writer', 'Screenplay'])->pluck('name')->toArray();

    // Ambil trailer video
    $videosResponse = Http::get(config('services.tmdb.base_url') . "{$type}/{$id}/videos", [
        'api_key' => $apiKey,
    ]);
    $videos = $videosResponse->json();

    // Ambil trailer key (jika ada)
    $trailerKey = null;
    if (isset($videos['results']) && count($videos['results']) > 0) {
        $trailer = collect($videos['results'])->firstWhere('type', 'Trailer');
        if ($trailer) {
            $trailerKey = $trailer['key'];
        }
    }

    return view('detail', compact('detail', 'cast', 'director', 'writers', 'trailerKey', 'type'));
})->name('detail');

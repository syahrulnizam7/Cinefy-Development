<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Http\Controllers\CastController;
use App\Http\Controllers\WatchedController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ExploreController;

Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle']);
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);


Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');


Route::get('/explore', [ExploreController::class, 'index'])->name('explore.index');


Route::get('/cast/{id}', [CastController::class, 'show'])->name('cast.show');

Route::get('login', function () {
    return view('login'); // Ganti dengan nama blade login yang sesuai
})->name('login'); // Memberi nama route 'login'

Route::middleware(['auth'])->group(function () {
    Route::post('/watched', [WatchedController::class, 'store'])->name('watched.store');
    Route::get('/watched/check', [WatchedController::class, 'check'])->name('watched.index');
    Route::delete('/watched', [WatchedController::class, 'destroy'])->name('watched.destroy');

    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::delete('/watchlist', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

    Route::post('/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::get('/favorite/check', [FavoriteController::class, 'check'])->name('favorite.index');
    Route::delete('/favorite', [FavoriteController::class, 'destroy'])->name('favorite.destroy');

    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
});


Route::post('/logout', function () {
    Auth::logout();
    Session::invalidate(); // Bersihkan sesi
    Session::regenerateToken(); // Regenerasi token CSRF untuk meningkatkan keamanan
    return redirect('/'); // Arahkan pengguna setelah logout
})->name('logout');



// Route untuk halaman welcome
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome')->middleware('auth');

// Route untuk menyimpan data profil
Route::post('/welcome/save', [ProfileController::class, 'saveProfile'])->name('welcome.save')->middleware('auth');

Route::get('/api/search', function () {
    $query = request('q');
    if (!$query) return response()->json(['results' => []]);

    $apiKey = config('services.tmdb.api_key');
    $baseUrl = config('services.tmdb.base_url');

    // Fetch movie results
    $movieResponse = Http::get("{$baseUrl}search/movie", [
        'api_key' => $apiKey,
        'language' => 'id-ID',
        'query' => $query,
    ]);

    // Fetch TV series results
    $tvResponse = Http::get("{$baseUrl}search/tv", [
        'api_key' => $apiKey,
        'language' => 'id-ID',
        'query' => $query,
    ]);

    // URL default untuk gambar jika tidak ada poster
    $defaultPoster = asset('images/noimg.png');


    $movies = collect($movieResponse->json()['results'] ?? [])->map(fn($item) => [
        'id' => $item['id'],
        'title' => $item['title'],
        'release_date' => $item['release_date'] ?? '',
        'type' => 'movie',
        'poster_path' => !empty($item['poster_path'])
            ? "https://image.tmdb.org/t/p/w92{$item['poster_path']}"
            : url('images/noimg.png'), // Pastikan URL absolut tanpa asset()

        'average_rating' => $item['vote_average'] ?? 'N/A',
    ]);

    $tvShows = collect($tvResponse->json()['results'] ?? [])->map(fn($item) => [
        'id' => $item['id'],
        'title' => $item['name'],
        'release_date' => $item['first_air_date'] ?? '',
        'type' => 'tv',
        'poster_path' => !empty($item['poster_path'])
            ? "https://image.tmdb.org/t/p/w92{$item['poster_path']}"
            : url('images/noimg.png'), // Pastikan URL absolut tanpa asset()

        'average_rating' => $item['vote_average'] ?? 'N/A',
    ]);

    // Gabungkan hasil film dan TV series
    $results = $movies->merge($tvShows)->values();

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

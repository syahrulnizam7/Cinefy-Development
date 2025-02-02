<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    $apiKey = config('services.tmdb.api_key');

    // Ambil daftar film populer
    $moviesResponse = Http::get(config('services.tmdb.base_url') . "movie/popular", [
        'api_key' => $apiKey,
        'language' => 'id-ID',
        'page' => 1
    ]);

    $movies = $moviesResponse->json()['results'] ?? [];

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

    return view('index', compact('movies', 'latestTrailers'));
});

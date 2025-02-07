<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = env('TMDB_API_KEY');
        $page = $request->page ?? 1; // Dapatkan nomor halaman dari query string, default ke 1
        $perPage = 18;

        // Base URL untuk movies dan TV shows
        $baseUrl = "https://api.themoviedb.org/3/discover/";

        // Tentukan jenis media (movie atau tv)
        $mediaType = $request->type ?? 'movie'; // Default 'movie'

        // Bangun URL berdasarkan jenis media yang dipilih
        $url = "$baseUrl$mediaType?api_key=$apiKey&language=en-US&page=$page&per_page=$perPage";

        // Terapkan filter berdasarkan request
        if ($request->genre) {
            $url .= "&with_genres=" . $request->genre;
        }

        if ($request->year) {
            if ($mediaType == 'movie') {
                $url .= "&primary_release_year=" . $request->year;
            } else {
                $url .= "&first_air_date_year=" . $request->year;
            }
        }

        if ($request->rating) {
            $url .= "&vote_average.gte=" . $request->rating;
        }

        if ($request->search) {
            $searchQuery = urlencode($request->search);
            $url = str_replace("discover/$mediaType", "search/$mediaType", $url) . "&query=$searchQuery";
        }


        // Ambil data Movies/TV Shows berdasarkan filter yang diterapkan
        $response = Http::get($url);
        $items = array_slice($response->json()['results'] ?? [], 0, 18);

        

        // Ambil daftar genre untuk filter dropdown
        $genresResponse = Http::get("https://api.themoviedb.org/3/genre/$mediaType/list?api_key=$apiKey&language=en-US");
        $genres = $genresResponse->json()['genres'] ?? [];

        // Kirim data ke view, termasuk $page
        return view('explore', compact('items', 'genres', 'mediaType', 'page'));
    }

    public function searchAutocomplete(Request $request)
{
    $apiKey = env('TMDB_API_KEY');
    $query = $request->get('query');
    $mediaType = $request->get('type', 'movie'); // Default 'movie'

    // Bangun URL untuk pencarian berdasarkan query
    $url = "https://api.themoviedb.org/3/search/$mediaType?api_key=$apiKey&language=en-US&query=" . urlencode($query);

    $response = Http::get($url);
    $results = $response->json()['results'] ?? [];

    // Hanya ambil judul untuk auto-complete
    $titles = array_map(function ($item) {
        return $item['title'] ?? $item['name'];
    }, $results);

    return response()->json($titles);
}

}

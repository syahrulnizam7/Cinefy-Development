<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = env('TMDB_API_KEY');
        $page = $request->page ?? 1;
        $perPage = 18;
        $mediaType = $request->type ?? ''; // 'movie', 'tv', atau '' (All)
        $searchQuery = $request->search ?? '';

        
        // Persiapkan query string dasar
        $queryParams = [
            'api_key' => $apiKey,
            'language' => 'en-US',
            'page' => $page,
            'sort_by' => 'popularity.desc',
        ];

        // Filter Genre
        $selectedGenre = $request->genre ?? null;
        if ($selectedGenre) {
            $queryParams['with_genres'] = $selectedGenre;
        }

        // Filter Tahun & Rating
        $selectedYear = $request->year ?? null;
        $selectedRating = $request->rating ?? null;

        $items = [];
        
        

        // **Jika ada search query, gunakan search untuk movie & tv saja**
        if (!empty($searchQuery)) {
            $movies = [];
            $tvShows = [];

            if ($mediaType === 'movie' || $mediaType === '') {
                $movieUrl = "https://api.themoviedb.org/3/search/movie?" . http_build_query($queryParams);
                $movieUrl .= "&query=" . urlencode($searchQuery);
                $movieResponse = Http::get($movieUrl)->json();
                $movies = $movieResponse['results'] ?? [];

                foreach ($movies as &$movie) {
                    $movie['media_type'] = 'movie';
                }
            }

            if ($mediaType === 'tv' || $mediaType === '') {
                $tvUrl = "https://api.themoviedb.org/3/search/tv?" . http_build_query($queryParams);
                $tvUrl .= "&query=" . urlencode($searchQuery);
                $tvResponse = Http::get($tvUrl)->json();
                $tvShows = $tvResponse['results'] ?? [];

                foreach ($tvShows as &$tvShow) {
                    $tvShow['media_type'] = 'tv';
                }
            }

            // Gabungkan hasil pencarian dari movie dan tv
            $items = array_merge($movies, $tvShows);
        } else {
            // Jika tidak ada search query, gunakan Discover API
            if ($mediaType == 'movie' || $mediaType == '') {
                if ($selectedYear) {
                    $queryParams['primary_release_year'] = $selectedYear;
                }
                if ($selectedRating) {
                    $queryParams['vote_average.gte'] = $selectedRating;
                }

                $movieUrl = "https://api.themoviedb.org/3/discover/movie?" . http_build_query($queryParams);
                $movieResponse = Http::get($movieUrl)->json();
                $movies = $movieResponse['results'] ?? [];

                foreach ($movies as &$movie) {
                    $movie['media_type'] = 'movie';
                }

                $items = array_merge($items, $movies);
            }

            if ($mediaType == 'tv' || $mediaType == '') {
                if ($selectedYear) {
                    $queryParams['first_air_date_year'] = $selectedYear;
                }
                if ($selectedRating) {
                    $queryParams['vote_average.gte'] = $selectedRating;
                }

                $tvUrl = "https://api.themoviedb.org/3/discover/tv?" . http_build_query($queryParams);
                $tvResponse = Http::get($tvUrl)->json();
                $tvShows = $tvResponse['results'] ?? [];

                foreach ($tvShows as &$tvShow) {
                    $tvShow['media_type'] = 'tv';
                }

                $items = array_merge($items, $tvShows);
            }
        }

        // Urutkan berdasarkan popularitas agar lebih rapi
        usort($items, fn($a, $b) => $b['popularity'] <=> $a['popularity']);

        // Batasi jumlah hasil yang ditampilkan
        $items = array_slice($items, 0, $perPage);

        // Ambil daftar genre (menyesuaikan dengan type)
        $genresUrl = "https://api.themoviedb.org/3/genre/" . ($mediaType === 'tv' ? 'tv' : 'movie') . "/list?api_key={$apiKey}&language=en-US";
        $genresResponse = Http::get($genresUrl)->json();
        $genres = $genresResponse['genres'] ?? [];

        return view('explore', compact('items', 'page', 'genres', 'mediaType'));
    }

    public function searchAutocomplete(Request $request)
    {
        $apiKey = env('TMDB_API_KEY');
        $query = $request->get('query');
        $mediaType = $request->get('type', 'movie'); // Default 'movie'

        $url = "https://api.themoviedb.org/3/search/{$mediaType}?api_key={$apiKey}&language=en-US&query=" . urlencode($query);
        $response = Http::get($url);
        $results = $response->json()['results'] ?? [];

        $titles = array_map(fn($item) => $item['title'] ?? $item['name'], $results);

        return response()->json($titles);
    }
}

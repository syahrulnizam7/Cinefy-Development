<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CastController extends Controller
{
    public function show($id)
    {
        $apiKey = env('TMDB_API_KEY');
        $castDetail = Http::get("https://api.themoviedb.org/3/person/{$id}?api_key={$apiKey}&language=en-US")->json();
        $socialMedia = Http::get("https://api.themoviedb.org/3/person/{$id}/external_ids?api_key={$apiKey}")->json();
        $credits = Http::get("https://api.themoviedb.org/3/person/{$id}/combined_credits?api_key={$apiKey}")->json()['cast'];

        $socialMedia = [
            'twitter_id' => $socialMedia['twitter_id'] ?? null,
            'instagram_id' => $socialMedia['instagram_id'] ?? null,
            'facebook_id' => $socialMedia['facebook_id'] ?? null,
            'youtube_id' => $socialMedia['youtube_id'] ?? null,
            'tiktok_id' => $socialMedia['tiktok_id'] ?? null,
        ];


        // Urutkan berdasarkan tahun terbaru
        usort($credits, function ($a, $b) {
            $dateA = $a['release_date'] ?? $a['first_air_date'] ?? null;
            $dateB = $b['release_date'] ?? $b['first_air_date'] ?? null;

            if (!$dateA) return 1;
            if (!$dateB) return -1;

            return strtotime($dateB) - strtotime($dateA);
        });

        return view('cast', compact('castDetail', 'socialMedia', 'credits'));
    }
}

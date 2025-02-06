<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model {
    use HasFactory;

    protected $table = 'watchlist';

    protected $fillable = [
        'user_id', 'tmdb_id', 'title', 'poster_path', 'type', 'vote_average', 'release_date',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $table = 'favorite';

    protected $fillable = ['user_id', 'tmdb_id', 'title', 'poster_path', 'type', 'vote_average', 'release_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

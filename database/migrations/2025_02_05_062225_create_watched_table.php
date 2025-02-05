<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('watched', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tmdb_id'); // ID dari TMDB
            $table->string('title');
            $table->string('poster_path')->nullable();
            $table->string('type'); // movie atau tvseries
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watched');
    }
};

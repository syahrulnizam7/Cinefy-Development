<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('watchlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tmdb_id')->unique();
            $table->string('title');
            $table->string('poster_path')->nullable();
            $table->string('type'); // movie, tv, anime
            $table->decimal('vote_average', 3, 1)->default(0);
            $table->date('release_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('watchlist');
    }
};

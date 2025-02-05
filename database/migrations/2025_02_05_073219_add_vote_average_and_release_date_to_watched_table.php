<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('watched', function (Blueprint $table) {
            $table->float('vote_average', 8, 2)->nullable(); // Kolom vote_average
            $table->date('release_date')->nullable(); // Kolom release_date
        });
    }

    public function down()
    {
        Schema::table('watched', function (Blueprint $table) {
            $table->dropColumn(['vote_average', 'release_date']);
        });
    }
};

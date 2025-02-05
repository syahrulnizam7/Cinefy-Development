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
            $table->decimal('vote_average', 3, 1)->nullable()->change();
            $table->date('release_date')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('watched', function (Blueprint $table) {
            $table->decimal('vote_average', 3, 1)->nullable(false)->change();
            $table->date('release_date')->nullable(false)->change();
        });
    }
};

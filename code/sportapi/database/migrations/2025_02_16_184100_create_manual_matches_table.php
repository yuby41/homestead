<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('manual_matches', function (Blueprint $table) {
            $table->id();
            $table->string('league_name');
            $table->string('home_team');
            $table->string('away_team');
            $table->datetime('match_date');
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->decimal('home_odds', 5, 2)->nullable();
            $table->decimal('away_odds', 5, 2)->nullable();
            $table->decimal('draw_odds', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_matches');
    }
};

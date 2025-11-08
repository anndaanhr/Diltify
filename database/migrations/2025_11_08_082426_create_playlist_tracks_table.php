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
        Schema::create('playlist_tracks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('playlist_id');
            $table->string('track_id')->nullable();
            $table->string('track_name');
            $table->string('artist_name');
            $table->string('collection_name')->nullable();
            $table->string('collection_artist_name')->nullable();
            $table->string('primary_genre_name')->nullable();
            $table->integer('track_time_millis')->nullable();
            $table->timestamp('release_date')->nullable();
            $table->string('preview_url')->nullable();
            $table->string('artwork_url')->nullable();
            $table->timestamps();

            $table->foreign('playlist_id')
                ->references('id')
                ->on('playlists')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_tracks');
    }
};

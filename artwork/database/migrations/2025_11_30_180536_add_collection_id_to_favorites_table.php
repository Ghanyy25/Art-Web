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
        // LANGKAH 1: Bersihkan Constraint Lama (FK & Index)
        // Kita pisahkan Schema::table ini agar dijalankan terpisah
        Schema::table('favorites', function (Blueprint $table) {
            // Hapus Foreign Key dulu agar Index bisa didrop tanpa error 1553
            $table->dropForeign(['user_id']);
            $table->dropForeign(['artwork_id']);

            // Hapus Unique Index lama (user_id + artwork_id)
            $table->dropUnique(['user_id', 'artwork_id']);
        });

        // LANGKAH 2: Tambah Kolom collection_id (Cek dulu biar gak error Duplicate)
        if (!Schema::hasColumn('favorites', 'collection_id')) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->foreignId('collection_id')
                      ->nullable()
                      ->after('user_id')
                      ->constrained()
                      ->onDelete('cascade');
            });
        }

        // LANGKAH 3: Pasang Constraint Baru & Kembalikan FK
        Schema::table('favorites', function (Blueprint $table) {
            // Buat Unique baru: User + Artwork + Collection
            // Beri nama spesifik 'fav_unique_v2' biar aman dari duplikasi nama index
            $table->unique(['user_id', 'artwork_id', 'collection_id'], 'fav_unique_v2');

            // Pasang kembali Foreign Keys user_id & artwork_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('artwork_id')->references('id')->on('artworks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorites', function (Blueprint $table) {
            // Hapus index baru & FK collection
            $table->dropForeign(['collection_id']);
            $table->dropIndex('fav_unique_v2'); // Hapus index berdasarkan nama yang kita buat

            // Drop ulang FK user & artwork untuk membalikkan keadaan
            $table->dropForeign(['user_id']);
            $table->dropForeign(['artwork_id']);

            // Hapus kolom
            $table->dropColumn('collection_id');

            // Kembalikan unique constraint lama
            $table->unique(['user_id', 'artwork_id']);

            // Pasang kembali FK lama
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('artwork_id')->references('id')->on('artworks')->onDelete('cascade');
        });
    }
};

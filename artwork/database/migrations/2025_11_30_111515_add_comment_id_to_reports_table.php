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
        Schema::table('reports', function (Blueprint $table) {
            // Tambahkan kolom comment_id yang bersifat nullable
            // constrained('comments') otomatis membuat foreign key ke tabel comments
            // onDelete('cascade') artinya jika komentar dihapus, laporan terkait juga ikut terhapus
            $table->foreignId('comment_id')
                  ->nullable()
                  ->after('artwork_id') // Letakkan kolom setelah artwork_id agar rapi
                  ->constrained('comments')
                  ->onDelete('cascade');

            // Opsional: Ubah artwork_id agar menjadi nullable (karena laporan bisa untuk komentar saja)
            // Pastikan Anda sudah menginstall package `doctrine/dbal` jika mengubah kolom yang sudah ada.
            // Jika belum: composer require doctrine/dbal
            if (Schema::hasColumn('reports', 'artwork_id')) {
                $table->foreignId('artwork_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Hapus foreign key dan kolom jika migrasi dibatalkan (rollback)
            $table->dropForeign(['comment_id']);
            $table->dropColumn('comment_id');

            // Kembalikan artwork_id menjadi tidak nullable (jika sebelumnya diubah)
            // Hati-hati: ini bisa gagal jika ada data di DB yang artwork_id-nya NULL
            if (Schema::hasColumn('reports', 'artwork_id')) {
                $table->foreignId('artwork_id')->nullable(false)->change();
            }
        });
    }
};

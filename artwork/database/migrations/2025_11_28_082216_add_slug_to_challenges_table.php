<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            // Kita buat NULLABLE dulu agar data lama tidak error
            // Nanti kita isi manual lewat Tinker
            $table->string('slug')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

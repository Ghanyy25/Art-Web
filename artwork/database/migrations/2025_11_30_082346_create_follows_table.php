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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            // User yang menekan tombol follow (Pengikut)
            $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');

            // User yang di-follow (Kurator/Target)
            $table->foreignId('following_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();

            // Mencegah user follow orang yang sama berkali-kali
            $table->unique(['follower_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};

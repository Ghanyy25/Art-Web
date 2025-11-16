<?php

namespace App\Http\Controllers;

use App\Models\Artworks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArtworkDetailController extends Controller
{
    /**
     * Menampilkan halaman detail karya (public).
     */
    public function show($id)
    {
        $artwork = Artworks::with([
            'user', // Kreator
            'category',
            'comments.user', // Komentar & user yang komentar
        ])->findOrFail($id);

        // Info tambahan (Like & Favorite)
        $isLiked = false;
        $isFavorited = false;

        if (Auth::check()) {
            $userId = Auth::id();
            $isLiked = $artwork->likes()->where('user_id', $userId)->exists();
            $isFavorited = $artwork->favorites()->where('user_id', $userId)->exists();
        }

        return view('artworks.show', compact('artwork', 'isLiked', 'isFavorited'));
    }
}

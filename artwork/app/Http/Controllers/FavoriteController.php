<?php

namespace App\Http\Controllers;

use App\Models\Artworks;
use App\Models\Favorites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Handle save/unsave (toggle)
     */
    public function toggle($artwork_id)
    {
        $user = Auth::user();
        $artwork = Artworks::findOrFail($artwork_id);

        // Cek apakah user sudah memfavoritkan karya ini
        $existingFavorite = Favorites::where('user_id', $user->id)
                                     ->where('artwork_id', $artwork->id)
                                     ->first();

        if ($existingFavorite) {
            // Jika sudah ada, hapus (Un-favorite)
            $existingFavorite->delete();
            $isFavorited = false;
            $message = 'Berhasil dihapus dari favorit.';
        } else {
            // Jika belum ada, buat baru (Favorite)
            Favorites::create([
                'user_id' => $user->id,
                'artwork_id' => $artwork->id
            ]);
            $isFavorited = true;
            $message = 'Berhasil ditambahkan ke favorit.';
        }

        // Return JSON Response untuk AJAX
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'is_favorited' => $isFavorited,
        ]);
    }

    /**
     * Menampilkan galeri favorites milik user
     */
     public function index()
     {
         $favorites = Favorites::where('user_id', Auth::id())
                                ->with('artwork.user') 
                                ->latest()
                                ->paginate(20);

         return view('favorites.index', compact('favorites'));
     }
}

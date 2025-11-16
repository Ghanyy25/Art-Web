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
    public function toggle($artworkId)
    {
        $artwork = Artworks::findOrFail($artworkId);
        $userId = Auth::id();

        $favorite = Favorites::where('artwork_id', $artworkId)->where('user_id', $userId)->first();

        if ($favorite) {
            // Jika sudah save, maka unsave
            $favorite->delete();
        } else {
            // Jika belum save, maka save
            Favorites::create([
                'artwork_id' => $artworkId,
                'user_id' => $userId,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Menampilkan galeri favorites milik user
     */
     public function index()
     {
         $favorites = Favorites::where('user_id', Auth::id())
                                ->with('artwork.user') // Ambil juga data artwork & kreatornya
                                ->latest()
                                ->paginate(20);

         return view('favorites.index', compact('favorites'));
     }
}

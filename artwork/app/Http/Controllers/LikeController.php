<?php

namespace App\Http\Controllers;

use App\Models\Artworks;
use App\Models\Likes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Handle like/unlike (toggle)
     */
    public function toggle($artworkId)
    {
        $artwork = Artworks::findOrFail($artworkId);
        $userId = Auth::id();

        $like = Likes::where('artwork_id', $artworkId)->where('user_id', $userId)->first();

        if ($like) {
            // Jika sudah like, maka unlike
            $like->delete();
        } else {
            // Jika belum like, maka like
            Likes::create([
                'artwork_id' => $artworkId,
                'user_id' => $userId,
            ]);
        }

        return redirect()->back(); // Kembali ke halaman sebelumnya
    }
}

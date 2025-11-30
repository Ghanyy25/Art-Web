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
    public function toggle(Request $request, $artwork_id)
    {
        $artwork = Artworks::findOrFail($artwork_id);
        $user = auth()->user();

        // Cek apakah user sudah like
        $existingLike = Likes::where('user_id', $user->id)
                            ->where('artwork_id', $artwork->id)
                            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $isLiked = false;
        } else {
            Likes::create([
                'user_id' => $user->id,
                'artwork_id' => $artwork->id
            ]);
            $isLiked = true;
        }

        $likeCount = $artwork->likes()->count();

        return response()->json([
            'status' => 'success',
            'is_liked' => $isLiked,
            'likes_count' => $likeCount,
        ]);
    }
}

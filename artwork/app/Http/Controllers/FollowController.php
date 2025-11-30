<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle($userId)
    {
        $targetUser = User::findOrFail($userId);
        $currentUser = Auth::user();

        if ($currentUser->id === $targetUser->id) {
            return response()->json(['status' => 'error', 'message' => 'Tidak bisa follow diri sendiri'], 400);
        }

        // Cek apakah sudah follow
        if ($currentUser->isFollowing($targetUser->id)) {
            // Unfollow
            $currentUser->following()->detach($targetUser->id);
            $isFollowing = false;
        } else {
            // Follow
            $currentUser->following()->attach($targetUser->id);
            $isFollowing = true;
        }

        return response()->json([
            'status' => 'success',
            'is_following' => $isFollowing,
            'followers_count' => $targetUser->followers()->count()
        ]);
    }
}

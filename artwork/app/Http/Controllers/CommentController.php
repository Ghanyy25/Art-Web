<?php

namespace App\Http\Controllers;

use App\Models\Artworks;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Menyimpan komentar baru.
     */
    public function store(Request $request, $artworkId)
    {
        $artwork = Artworks::findOrFail($artworkId);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment = Comments::create([
            'user_id' => Auth::id(),
            'artwork_id' => $artworkId,
            'body' => $request->body,
        ]);

        // Load data user agar avatar & nama muncul di JS
        $comment->load('user');

        // RETURN JSON (Wajib untuk Axios)
        return response()->json([
            'status' => 'success',
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $comment,
            'user_avatar' => $comment->user->profile_picture ? \Storage::url($comment->user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name),
            'user_name' => $comment->user->name
        ]);
    }

    /**
     * Hapus komentar (hanya pemilik komentar).
     */
    public function destroy($commentId)
    {
        $comment = Comments::findOrFail($commentId);

        // Otorisasi: Hanya pemilik komentar yang bisa hapus
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus.');
    }
}

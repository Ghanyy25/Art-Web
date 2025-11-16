<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CreatorProfileController extends Controller
{
    /**
     * Menampilkan halaman profil kreator (public).
     */
    public function show($id)
    {
        $creator = User::where('role', 'member')->findOrFail($id);

        // Ambil semua karya milik kreator ini
        $artworks = $creator->artworks()->latest()->paginate(12);

        return view('profile.show', compact('creator', 'artworks'));
    }
}

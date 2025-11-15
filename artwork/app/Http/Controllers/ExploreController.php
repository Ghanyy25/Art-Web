<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artworks;

class ExploreController extends Controller
{
    public function index()
    {
        $artworks = Artworks::with(['user', 'likes', 'favorites'])->latest()->paginate(20);
        return view('dashboard.user.explore', compact('artworks'));
    }
}

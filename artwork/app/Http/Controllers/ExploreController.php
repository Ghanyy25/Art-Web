<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artworks;
use App\Models\Categories;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $query = Artworks::query()->with(['user', 'category']); // Eager load user & category

        // Filter berdasarkan Kategori
        if ($request->has('category')) {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter berdasarkan search (judul)
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
            ->orWhere('description', 'like', '%' . $request->search . '%')
            ->orWhereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $artworks = $query->latest()->paginate(32); // Tampilkan 24 karya per halaman
        $categories = Categories::all();

        // Tampilan untuk 'explore' yang Anda buat sebelumnya
        return view('dashboard.user.explore', compact('artworks', 'categories'));
    }
}

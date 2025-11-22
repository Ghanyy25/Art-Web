<?php

namespace App\Http\Controllers;

use App\Models\Artworks;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Penting untuk mengelola file

class ArtworkController extends Controller
{
    /**
     * Menampilkan galeri karya milik pengguna yang sedang login.
     * Sesuai dengan "My Artworks" di dokumen.
     */
    public function index()
    {
        // Ambil semua karya HANYA milik user yang sedang login
        $artworks = Artworks::where('user_id', Auth::id())
                            ->latest()
                            ->get();

        // Nanti kita buat view-nya di: resources/views/artworks/index.blade.php
        return view('artworks.index', compact('artworks'));
    }

    /**
     * Menampilkan halaman form untuk upload karya baru.
     */
    public function create()
    {
        // Kita butuh daftar kategori untuk ditampilkan di form <select>
        $categories = Categories::all();

        // Nanti kita buat view-nya di: resources/views/artworks/create.blade.php
        return view('artworks.create', compact('categories'));
    }

    /**
     * Menyimpan karya baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|string|max:255',
            'artwork_file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB Max
        ]);

        // 2. Handle File Upload
        // Simpan file di 'storage/app/public/artworks'
        // 'public/artworks' akan ter-link ke 'public/storage/artworks'
        $filePath = $request->file('artwork_file')->store('artworks', 'public');

        // 3. Simpan ke Database
        Artworks::create([
            'user_id' => Auth::id(), // Set pemiliknya
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'tags' => $validated['tags'],
            'file_path' => $filePath, // Simpan path filenya
        ]);

        // 4. Redirect ke halaman "My Artworks"
        return redirect()->route('artworks.index')->with('success', 'Karya berhasil di-upload!');
    }


    /**
     * Menampilkan form untuk mengedit karya.
     */
    public function edit($id)
    {
        $artwork = Artworks::findOrFail($id);

        // Keamanan: Cek apakah user ini adalah pemilik karya
        if ($artwork->user_id !== Auth::id()) {
            abort(403, 'Anda tidak punya akses untuk mengedit karya ini.');
        }

        $categories = Categories::all();

        // Nanti kita buat view-nya di: resources/views/artworks/edit.blade.php
        return view('artworks.edit', compact('artwork', 'categories'));
    }

    /**
     * Mengupdate karya di database.
     */
    public function update(Request $request, $id)
    {
        $artwork = Artworks::findOrFail($id);

        // Keamanan: Cek kepemilikan
        if ($artwork->user_id !== Auth::id()) {
            abort(403);
        }

        // 1. Validasi
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|string|max:255',
            // File tidak wajib di-upload ulang saat edit
            'artwork_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        // 2. Cek apakah ada file baru yang di-upload
        if ($request->hasFile('artwork_file')) {
            // Hapus file lama
            Storage::disk('public')->delete($artwork->file_path);

            // Upload file baru
            $filePath = $request->file('artwork_file')->store('artworks', 'public');
            $validated['file_path'] = $filePath; // Tambahkan path baru ke data update
        }

        // 3. Update data di database
        $artwork->update($validated);

        return redirect()->route('artworks.index')->with('success', 'Karya berhasil diperbarui!');
    }

    /**
     * Menghapus karya dari database.
     */
    public function destroy($id)
    {
        $artwork = Artworks::findOrFail($id);

        // Keamanan: Cek kepemilikan
        if ($artwork->user_id !== Auth::id()) {
            abort(403);
        }

        // 1. Hapus file dari storage
        Storage::disk('public')->delete($artwork->file_path);

        // 2. Hapus data dari database
        $artwork->delete();

        return redirect()->route('artworks.index')->with('success', 'Karya berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Untuk membuat slug otomatis

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index()
    {
        // Ambil semua data kategori, urutkan dari yang terbaru
        $categories = Categories::latest()->get();

        // Tampilkan view admin.categories.index (nanti kita buat view-nya)
        // Kita kirim data $categories ke view tersebut
        return view('dashboard.admin.categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kategori ini sudah ada.',
        ]);

        // 2. Simpan ke Database
        Categories::create([
            'name' => $request->name,
            // Buat slug otomatis dari nama (misal: "UI/UX Design" jadi "ui-ux-design")
            'slug' => Str::slug($request->name),
        ]);

        // 3. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $category = Categories::findOrFail($id);

        // 1. Validasi Input
        $request->validate([
            // Validasi unique kecuali untuk id kategori ini sendiri
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        // 2. Update Data
        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori.
     */
    public function destroy($id)
    {
        $category = Categories::findOrFail($id);

        // Hapus data
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}

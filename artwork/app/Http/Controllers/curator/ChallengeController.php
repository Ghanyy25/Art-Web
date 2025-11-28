<?php

namespace App\Http\Controllers\Curator;

use App\Http\Controllers\Controller;
use App\Models\Challenges;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChallengeController extends Controller
{
    /**
     * Menampilkan daftar challenge milik curator yang sedang login.
     */
    public function index()
    {
        $challenges = Challenges::where('curator_id', Auth::id())
                                ->latest()
                                ->paginate(10);

        return view('dashboard.curator.challenges.index', compact('challenges'));
    }

    /**
     * Menampilkan detail challenge.
     */
    public function show($id)
    {
        // Ambil challenge milik curator yang sedang login
        // withCount('submissions') -> Untuk menghitung jumlah karya yang masuk otomatis
        $challenge = Challenges::where('curator_id', Auth::id())
                                ->withCount('submissions')
                                ->findOrFail($id);

        return view('dashboard.curator.challenges.show', compact('challenge'));
    }
    
    /**
     * Menampilkan formulir pembuatan challenge baru.
     */
    public function create()
    {
        return view('dashboard.curator.challenges.create');
    }

    /**
     * Menyimpan challenge baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'rules'       => 'required|string',

            // VALIDASI BARU UNTUK ARRAY HADIAH
            'prizes'      => 'required|array|min:1|max:3',
            'prizes.*'    => 'required|string|max:255', // Tiap hadiah harus berupa text

            'start_date'  => 'required|date|after_or_equal:today',
            'end_date'    => 'required|date|after:start_date',
            'banner_image'=> 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['curator_id'] = Auth::id();
        $validated['slug'] = Str::slug($request->title) . '-' . strtolower(Str::random(6));

        if ($request->hasFile('banner_image')) {
            $filePath = $request->file('banner_image')->store('challenge-banners', 'public');
            $validated['banner_image'] = $filePath;
        }

        // Karena di Model sudah di-cast 'array', kita bisa langsung simpan
        Challenges::create($validated);

        return redirect()->route('curator.challenges.index')
                        ->with('success', 'Challenge berhasil dibuat!');
    }

    /**
     * Menampilkan formulir edit challenge.
     */
    public function edit($id)
    {
        // Pastikan hanya bisa edit milik sendiri
        $challenge = Challenges::where('curator_id', Auth::id())->findOrFail($id);

        return view('dashboard.curator.challenges.edit', compact('challenge'));
    }

    /**
     * Memperbarui data challenge.
     */
    public function update(Request $request, $id)
    {
        $challenge = Challenges::where('curator_id', Auth::id())->findOrFail($id);

        // 1. Validasi (Gambar jadi nullable/opsional saat update)
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'rules'       => 'required|string',
            'prizes'      => 'required|array|min:1|max:3',
            'prizes.*'    => 'required|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after:start_date',
            'banner_image'=> 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Cek Apakah Judul Berubah? (Update Slug jika perlu, opsional)
        // Biasanya slug tidak diubah agar link lama tidak mati, tapi jika mau diubah:
        if ($request->title !== $challenge->title) {
             $validated['slug'] = Str::slug($request->title) . '-' . strtolower(Str::random(6));
        }

        // 3. Handle Ganti Gambar
        if ($request->hasFile('banner_image')) {
            // Hapus gambar lama dari storage jika ada
            if ($challenge->banner_image) {
                Storage::disk('public')->delete($challenge->banner_image);
            }

            // Upload gambar baru
            $filePath = $request->file('banner_image')->store('challenge-banners', 'public');
            $validated['banner_image'] = $filePath;
        }

        // 4. Update Database
        $challenge->update($validated);

        return redirect()->route('curator.challenges.index')
                         ->with('success', 'Data challenge berhasil diperbarui.');
    }

    /**
     * Menghapus challenge dan gambarnya.
     */
    public function destroy($id)
    {
        $challenge = Challenges::where('curator_id', Auth::id())->findOrFail($id);

        // 1. Hapus File Gambar dari Storage
        if ($challenge->banner_image) {
            Storage::disk('public')->delete($challenge->banner_image);
        }

        // 2. Hapus Data dari Database
        // Note: Submisi terkait akan ikut terhapus jika Anda set 'onDelete cascade' di migration,
        // jika tidak, submisi akan jadi orphan.
        $challenge->delete();

        return redirect()->route('curator.challenges.index')
                         ->with('success', 'Challenge telah dihapus permanen.');
    }
}

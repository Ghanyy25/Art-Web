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
     * Menampilkan daftar challenge milik curator.
     */
    public function index()
    {
        $challenges = Challenges::where('curator_id', Auth::id())
                                ->latest()
                                ->paginate(10);

        return view('dashboard.curator.challenges.index', compact('challenges'));
    }

    /**
     * Menampilkan form buat challenge baru.
     */
    public function create()
    {
        return view('dashboard.curator.challenges.create');
    }

    /**
     * Menyimpan challenge baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rules' => 'required|string',
            'prizes' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['curator_id'] = Auth::id();
        $validated['slug'] = Str::slug($request->title) . '-' . uniqid();

        if ($request->hasFile('banner_image')) {
            $filePath = $request->file('banner_image')->store('challenge_banners', 'public');
            $validated['banner_image'] = $filePath;
        }

        Challenges::create($validated);

        return redirect()->route('curator.challenges.index')->with('success', 'Challenge berhasil dibuat.');
    }

    /**
     * Menampilkan form edit challenge.
     */
    public function edit($id)
    {
        $challenge = Challenges::where('curator_id', Auth::id())->findOrFail($id);
        return view('dashboard.curator.challenges.edit', compact('challenge'));
    }

    /**
     * Update challenge.
     */
    public function update(Request $request, $id)
    {
        $challenge = Challenges::where('curator_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rules' => 'required|string',
            'prizes' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('banner_image')) {
            // Hapus banner lama
            if ($challenge->banner_image) {
                Storage::disk('public')->delete($challenge->banner_image);
            }
            // Upload banner baru
            $filePath = $request->file('banner_image')->store('challenge_banners', 'public');
            $validated['banner_image'] = $filePath;
        }

        $challenge->update($validated);

        return redirect()->route('curator.challenges.index')->with('success', 'Challenge berhasil diperbarui.');
    }

    /**
     * Hapus challenge.
     */
    public function destroy($id)
    {
        $challenge = Challenges::where('curator_id', Auth::id())->findOrFail($id);

        if ($challenge->banner_image) {
            Storage::disk('public')->delete($challenge->banner_image);
        }

        $challenge->delete();

        return redirect()->route('curator.challenges.index')->with('success', 'Challenge berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Challenges;
use App\Models\ChallengeSubmission;
use App\Models\Artworks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChallengeSubmissionController extends Controller
{
    /**
     * Menampilkan form submit.
     */
    public function create($challengeId)
    {
        $challenge = Challenges::findOrFail($challengeId);

        // CEK: Apakah sudah pernah submit?
        $existingSubmission = ChallengeSubmission::where('challenge_id', $challengeId)
                                ->where('user_id', Auth::id())
                                ->first();

        if ($existingSubmission) {
            return redirect()->route('challenges.show', $challenge->slug ?? $challenge->id)
                ->with('error', 'Anda sudah mengirimkan karya untuk challenge ini.');
        }

        // AMBIL DATA: Karya milik user untuk opsi "Pilih dari Galeri"
        $myArtworks = Artworks::where('user_id', Auth::id())
                        ->latest()
                        ->get();

        return view('dashboard.user.submission.submit', compact('challenge', 'myArtworks'));
    }

    /**
     * Memproses submission (Baik upload baru maupun pilih yang lama).
     */
    public function store(Request $request, $challengeId)
    {
        $challenge = Challenges::findOrFail($challengeId);

        // CEK: Validasi Ganda (Mencegah Spam)
        if (ChallengeSubmission::where('challenge_id', $challengeId)->where('user_id', Auth::id())->exists()) {
            return redirect()->back()->with('error', 'Anda sudah berpartisipasi.');
        }

        // 1. VALIDASI INPUT DINAMIS
        $request->validate([
            'submission_type' => 'required|in:new,existing',

            // Validasi jika Upload Baru
            'title'       => 'required_if:submission_type,new|nullable|string|max:255',
            'description' => 'required_if:submission_type,new|nullable|string|max:1000',
            'image'       => 'required_if:submission_type,new|nullable|image|max:10240',

            // Validasi jika Pilih Existing
            'existing_artwork_id' => 'required_if:submission_type,existing|nullable|exists:artworks,id',
        ]);

        try {
            DB::transaction(function () use ($request, $challenge) {

                $artworkId = null;

                if ($request->submission_type === 'new') {
                    // --- OPSI A: UPLOAD BARU ---
                    $path = $request->file('image')->store('artworks', 'public');

                    $artwork = Artworks::create([
                        'user_id'     => Auth::id(),
                        'title'       => $request->title,
                        'description' => $request->description,
                        'file_path'   => $path,
                        // 'category_id' => $request->category_id,
                    ]);

                    $artworkId = $artwork->id;

                } else {
                    // --- OPSI B: PILIH DARI GALERI ---
                    $artworkId = $request->existing_artwork_id;

                    // Keamanan: Pastikan artwork ini BENAR milik user yang login
                    $artwork = Artworks::where('id', $artworkId)
                                ->where('user_id', Auth::id())
                                ->firstOrFail();
                }

                // SIMPAN SUBMISSION
                ChallengeSubmission::create([
                    'challenge_id' => $challenge->id,
                    'user_id'      => Auth::id(),
                    'artwork_id'   => $artworkId,
                ]);
            });

            return redirect()->route('challenges.show', $challenge->slug ?? $challenge->id)
                ->with('success', 'Karya berhasil disubmit! Semoga beruntung.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}

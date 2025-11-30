<?php

namespace App\Http\Controllers;

use App\Models\Challenges;
use App\Models\ChallengeSubmission;
use App\Models\Artworks; // Tambahkan Model Artworks
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Untuk Transaction
use Illuminate\Support\Facades\Storage; // Untuk upload file

class ChallengeSubmissionController extends Controller
{
    /**
     * Menampilkan form submit.
     * Mengecek apakah user sudah pernah submit sebelumnya.
     */
    public function create($challengeId)
    {
        $challenge = Challenges::findOrFail($challengeId);

        // CEK 1: Apakah user sudah pernah submit di challenge ini?
        $existingSubmission = ChallengeSubmission::where('challenge_id', $challengeId)
                                ->where('user_id', Auth::id())
                                ->first();

        if ($existingSubmission) {
            return redirect()->route('challenges.show', $challenge->slug ?? $challenge->id)
                ->with('error', 'Anda sudah mengirimkan karya untuk challenge ini. Hanya diperbolehkan 1 kali submit.');
        }

        return view('dashboard.user.submission.submit', compact('challenge'));
    }

    /**
     * Memproses upload artwork baru dan menyimpannya sebagai submission.
     */
    public function store(Request $request, $challengeId)
    {
        $challenge = Challenges::findOrFail($challengeId);

        // CEK 2: Double check di backend untuk mencegah spam/race condition
        $existingSubmission = ChallengeSubmission::where('challenge_id', $challengeId)
                                ->where('user_id', Auth::id())
                                ->exists();

        if ($existingSubmission) {
            return redirect()->back()->with('error', 'Anda sudah berpartisipasi dalam challenge ini.');
        }

        // 1. Validasi Input (File Gambar Wajib)
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // Max 10MB
        ]);

        try {
            // Gunakan Transaction agar data konsisten (Artwork & Submission masuk bersamaan)
            DB::transaction(function () use ($request, $challenge) {

                // A. Upload File
                $path = $request->file('image')->store('artworks', 'public');

                // B. Buat Data Artwork Baru
                $artwork = Artworks::create([
                    'user_id'     => Auth::id(),
                    'title'       => $request->title,
                    'description' => $request->description,
                    'file_path'   => $path,
                    // Tambahkan field lain jika ada (misal: category_id default atau null)
                ]);

                // C. Buat Data Submission (Hubungkan Artwork tadi ke Challenge)
                ChallengeSubmission::create([
                    'challenge_id' => $challenge->id,
                    'user_id'      => Auth::id(),
                    'artwork_id'   => $artwork->id,
                ]);
            });

            return redirect()->route('challenges.show', $challenge->slug ?? $challenge->id)
                ->with('success', 'Karya Anda berhasil dikirim untuk challenge ini!');

        } catch (\Exception $e) {
            // Hapus file jika database gagal (opsional, untuk kebersihan storage)
            // if (isset($path)) Storage::disk('public')->delete($path);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupload: ' . $e->getMessage())
                ->withInput();
        }
    }
}

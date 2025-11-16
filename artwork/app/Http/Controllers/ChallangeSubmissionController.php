<?php

namespace App\Http\Controllers;

use App\Models\Challenges;
use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChallengeSubmissionController extends Controller
{
    /**
     * Menampilkan form untuk submit karya ke challenge.
     * Member memilih dari karya yang sudah mereka upload.
     */
    public function create($challengeId)
    {
        $challenge = Challenges::where('end_date', '>', now())->findOrFail($challengeId);

        // Ambil karya milik user yang BELUM pernah di-submit ke challenge ini
        $userArtworks = Auth::user()->artworks()
            ->whereDoesntHave('challengeSubmissions', function ($query) use ($challengeId) {
                $query->where('challenge_id', $challengeId);
            })
            ->latest()
            ->get();

        return view('challenges.submit', compact('challenge', 'userArtworks'));
    }

    /**
     * Menyimpan submisi karya ke challenge.
     */
    public function store(Request $request, $challengeId)
    {
        $challenge = Challenges::where('end_date', '>', now())->findOrFail($challengeId);

        $request->validate([
            'artwork_id' => 'required|exists:artworks,id',
        ], [
            'artwork_id.required' => 'Anda harus memilih salah satu karya Anda.'
        ]);

        $artwork = Auth::user()->artworks()->findOrFail($request->artwork_id);

        // Cek duplikasi
        $isSubmitted = ChallengeSubmission::where('challenge_id', $challengeId)
                                          ->where('artwork_id', $artwork->id)
                                          ->exists();

        if ($isSubmitted) {
            return redirect()->back()->with('error', 'Karya ini sudah pernah Anda submit ke challenge ini.');
        }

        ChallengeSubmission::create([
            'challenge_id' => $challengeId,
            'artwork_id' => $artwork->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('challenges.show', $challenge->slug)->with('success', 'Karya berhasil disubmit!');
    }
}

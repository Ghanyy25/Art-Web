<?php

namespace App\Http\Controllers\Curator;

use App\Http\Controllers\Controller;
use App\Models\Challenges;
use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Menampilkan galeri submisi untuk challenge tertentu.
     */
    public function index($challengeId)
    {
        // Pastikan curator ini adalah pemilik challenge
        $challenge = Challenges::where('id', $challengeId)
                               ->where('curator_id', Auth::id())
                               ->firstOrFail();

        $submissions = ChallengeSubmission::where('challenge_id', $challenge->id)
                                          ->with(['artwork', 'user']) // eager load
                                          ->paginate(20);

        return view('dashboard.curator.submissions.index', compact('challenge', 'submissions'));
    }

    /**
     * Menandai pemenang.
     */
    public function selectWinner(Request $request, $submissionId)
    {
        $submission = ChallengeSubmission::with('challenge')->findOrFail($submissionId);

        // Cek apakah curator ini adalah pemilik challenge dari submisi ini
        if ($submission->challenge->curator_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Cek apakah challenge sudah berakhir
        if ($submission->challenge->end_date->isFuture()) {
             return redirect()->back()->with('error', 'Challenge belum berakhir. Belum bisa memilih pemenang.');
        }

        $request->validate([
            'placement' => 'nullable|integer|min:1|max:3', // Misal, 1=Juara 1, 2=Juara 2, 3=Juara 3
        ]);

        // Cek apakah placement ini sudah dipakai
        if ($request->placement) {
            $existingWinner = ChallengeSubmission::where('challenge_id', $submission->challenge_id)
                                                ->where('placement', $request->placement)
                                                ->first();
            if ($existingWinner && $existingWinner->id != $submission->id) {
                return redirect()->back()->with('error', 'Peringkat ' . $request->placement . ' sudah ada pemenangnya.');
            }
        }

        $submission->update(['placement' => $request->placement]);

        return redirect()->back()->with('success', 'Pemenang berhasil diperbarui.');
    }
}

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
        // 1. Ambil Challenge & Pastikan milik curator yang login
        $challenge = Challenges::where('id', $challengeId)
                               ->where('curator_id', Auth::id())
                               ->firstOrFail();

        // 2. Ambil Submisi (Peserta)
        // Kita urutkan pemenang di paling atas agar mudah dilihat
        $submissions = ChallengeSubmission::where('challenge_id', $challenge->id)
                                          ->with(['artwork', 'user']) // Eager load relasi
                                          ->orderByRaw('-placement DESC') // Pemenang (ada nilai placement) di atas
                                          ->latest()
                                          ->paginate(20);

        return view('dashboard.curator.submission.index', compact('challenge', 'submissions'));
    }

    /**
     * Menyimpan/Update Pemenang.
     */
    public function selectWinner(Request $request, $submissionId)
    {
        $submission = ChallengeSubmission::with('challenge')->findOrFail($submissionId);

        // 1. Validasi Akses (Hanya pemilik challenge)
        if ($submission->challenge->curator_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak menilai challenge ini.');
        }

        // 2. Validasi Waktu (Opsional: Hanya bisa nilai jika challenge sudah selesai)
        // Hapus blok if ini jika Anda ingin bisa menilai kapan saja
        if ($submission->challenge->end_date->isFuture()) {
             return redirect()->back()->with('error', 'Challenge belum berakhir. Tunggu tanggal selesai untuk memilih pemenang.');
        }

        // 3. Hitung Jumlah Hadiah (Untuk batas maksimal ranking)
        // Jika prizes kosong, default max 3 juara
        $prizes = $submission->challenge->prizes ?? [];
        $maxRank = count($prizes) > 0 ? count($prizes) : 3;

        $request->validate([
            'placement' => 'nullable|integer|min:1|max:' . $maxRank,
        ]);

        // 4. Logika "Geser Pemenang" (Auto-Swap)
        // Jika user memilih Juara X, cek apakah sudah ada orang lain di posisi itu?
        if ($request->placement) {
            $existingWinner = ChallengeSubmission::where('challenge_id', $submission->challenge_id)
                                                ->where('placement', $request->placement)
                                                ->where('id', '!=', $submission->id) // Bukan diri sendiri
                                                ->first();

            // Jika ada pemenang lama di posisi itu, copot gelarnya
            if ($existingWinner) {
                $existingWinner->update(['placement' => null]);
            }
        }

        // 5. Simpan Status Baru
        $submission->update(['placement' => $request->placement]);

        return redirect()->back()->with('success', 'Status pemenang berhasil diperbarui.');
    }
}

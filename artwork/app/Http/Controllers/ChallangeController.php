<?php

namespace App\Http\Controllers;

use App\Models\Challenges;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChallengeController extends Controller
{
    /**
     * Menampilkan daftar challenge yang sedang berlangsung (public).
     */
    public function index()
    {
        $activeChallenges = Challenges::where('end_date', '>', Carbon::now())
                                     ->latest()
                                     ->paginate(10);

        return view('challenges.index', compact('activeChallenges'));
    }

    /**
     * Menampilkan halaman detail challenge (public).
     */
    public function show($slug)
    {
        $challenge = Challenges::where('slug', $slug)->firstOrFail();

        // Ambil galeri submisi
        $submissions = $challenge->submissions()
                                 ->with('artwork.user') // eager load
                                 ->paginate(18);

        // Ambil pemenang (jika ada)
        $winners = $challenge->submissions()
                            ->whereNotNull('placement')
                            ->orderBy('placement', 'asc')
                            ->with('artwork.user')
                            ->get();

        return view('challenges.show', compact('challenge', 'submissions', 'winners'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Challenges;
use App\Models\ChallengeSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ChallengeController extends Controller
{
    /**
     * Menampilkan daftar challenge yang sedang berlangsung (public).
     */
    public function index(Request $request)
    {
        $query = Challenges::query();

        // --- 1. Logika Filter (Status) ---
        $filter = $request->input('filter', 'active');

        if ($filter === 'active') {
        // Logic: Waktu Mulai <= Sekarang DAN Waktu Selesai > Sekarang
        $query->where('start_date', '<=', Carbon::now())
              ->where('end_date', '>', Carbon::now());
        } elseif ($filter === 'upcoming') {
            // HANYA yang akan datang
            $query->where('start_date', '>', Carbon::now());
        } elseif ($filter === 'ended') {
            // HANYA yang sudah selesai
            $query->where('end_date', '<=', Carbon::now());
        }
        
        // --- 2. Logika Search ---
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('curator', function ($q2) use ($search) {
                      $q2->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        // --- 3. Eksekusi ---
        $challenges = $query->latest() // Urutkan dari yang terbaru dibuat
                            ->paginate(12)
                            ->withQueryString();



        return view('challenges.index', compact('challenges', 'filter'));
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

        $existingSubmission = ChallengeSubmission::where('challenge_id', $challenge-> id)
                                ->where('user_id', Auth::id())
                                ->first();

        return view('challenges.show', compact('challenge', 'submissions', 'winners', 'existingSubmission'));
    }


}

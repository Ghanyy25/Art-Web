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
    public function index(Request $request)
    {
        $query = Challenges::query();

        // --- 1. Logika Filter (Status) ---
        // Default ke 'active' (Berlangsung) agar user melihat yang relevan dulu
        // Tapi jika user klik 'Semua', filter akan berisi 'all'
        $filter = $request->input('filter', 'active');

        if ($filter === 'active') {
            // HANYA yang sedang berjalan
            $query->where('end_date', '>', Carbon::now());
        } elseif ($filter === 'ended') {
            // HANYA yang sudah selesai
            $query->where('end_date', '<=', Carbon::now());
        }
        // Jika filter === 'all', kita TIDAK menambahkan where date (ambil semua)

        // --- 2. Logika Search ---
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
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

        return view('challenges.show', compact('challenge', 'submissions', 'winners'));
    }
}

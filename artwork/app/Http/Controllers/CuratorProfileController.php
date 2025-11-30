<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Challenges;
use Illuminate\Http\Request;

class CuratorProfileController extends Controller
{
    public function show($id)
    {
        // 1. Ambil data User berdasarkan ID
        $curator = User::findOrFail($id);

        // 2. Pastikan user ini benar-benar Curator (Opsional, tapi bagus untuk keamanan)
        if ($curator->role !== 'curator') {
            return redirect()->route('profile.show', $id); // Lempar ke profil biasa jika bukan curator
        }

        // 3. Ambil Challenge yang dibuat oleh curator ini
        $challenges = Challenges::where('curator_id', $id)
            ->latest()
            ->get();

        // 4. Hitung statistik sederhana
        $totalChallenges = $challenges->count();
        $activeChallenges = $challenges->where('end_date', '>=', now())->count();

        return view('profile.curator-show', compact('curator', 'challenges', 'totalChallenges', 'activeChallenges'));
    }
}

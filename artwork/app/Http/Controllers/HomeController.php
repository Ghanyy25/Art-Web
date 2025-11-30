<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Challenges;
use App\Models\Artworks;
use App\Models\Reports;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Logika Admin (Tetap seperti sebelumnya)
            $stats = [
                'total_users'       => User::count(),
                'total_creators'    => User::where('role', 'member')->count(),
                'total_curators'    => User::where('role', 'curator')->count(),
                'total_artworks'    => Artworks::count(),
                'active_challenges' => Challenges::where('end_date', '>=', now())->count(),
                'pending_reports'   => Reports::where('status', 'pending')->count(),
            ];
            return view('dashboard.admin.home', compact('stats'));

        } elseif ($user->role === 'curator') {
            // Logika Curator (Tetap)
            if ($user->status === 'pending') {
                return view('dashboard.curator.pending');
            }
            return view('dashboard.curator.home');

        } else {
            $followingIds = $user->following()->pluck('users.id');

            $feedArtworks = Artworks::whereIn('user_id', $followingIds)
                                    ->with('user')
                                    ->latest()
                                    ->paginate(10);

            $activeChallenges = Challenges::where('end_date', '>=', now())
                                          ->latest()
                                          ->take(3)
                                          ->get();

            return view('dashboard.user.home', compact('feedArtworks', 'activeChallenges'));
        }
    }
}

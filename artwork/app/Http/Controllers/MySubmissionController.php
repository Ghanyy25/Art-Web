<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChallengeSubmission;
use Illuminate\Support\Facades\Auth;

class MySubmissionController extends Controller
{
    /**
     * Menampilkan daftar submission user.
     */
    public function index()
    {
        // Ambil submission milik user yang sedang login
        // 'challenge' dan 'artwork' di-eager load untuk efisiensi
        $submissions = ChallengeSubmission::with(['challenge', 'artwork'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('dashboard.user.submission.index', compact('submissions'));
    }
}

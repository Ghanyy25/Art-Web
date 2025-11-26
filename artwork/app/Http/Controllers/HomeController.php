<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return view('dashboard.admin.home');
        }

        if ($user->role === 'curator') {
            // Tambahan Cek Status
            if ($user->status === 'pending') {
                return redirect()->route('curator.pending');
            }
            return view('dashboard.curator.home');
        }

        return view('dashboard.user.home');
    }
}

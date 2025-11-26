<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Input (termasuk Role)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:member,curator'], // Wajib pilih role yang valid
        ]);

        // 2. Tentukan Status berdasarkan Role
        // Jika Curator -> Pending. Jika Member -> Active.
        $status = $request->role === 'curator' ? 'pending' : 'active';

        // 3. Simpan ke Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,   // Simpan Role
            'status' => $status,        // Simpan Status
        ]);

        event(new Registered($user));

        // 4. Logika Login / Redirect

        // Jika status Pending (Curator), JANGAN login otomatis.
        if ($user->status === 'pending') {
            return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Akun Curator Anda sedang menunggu persetujuan Admin.');
        }

        // Jika Active (Member), Login otomatis.
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}

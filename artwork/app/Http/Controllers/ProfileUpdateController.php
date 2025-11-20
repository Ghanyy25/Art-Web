<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request; // Pastikan pakai Request bawaan ini
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Tambahkan ini untuk validasi unique email
use App\Models\User;

class ProfileUpdateController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.update', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // 1. LAKUKAN VALIDASI LANGSUNG DI SINI
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($request->user()->id),
            ],
            // Validasi tambahan untuk fitur baru Anda
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'external_links.instagram' => ['nullable', 'string', 'url'],
            'external_links.behance' => ['nullable', 'string', 'url'],
            'external_links.website' => ['nullable', 'string', 'url'],
        ]);
        
        $user = $request->user();

        // 2. LOGIKA UPLOAD GAMBAR
        if ($request->hasFile('profile_picture')) {
            // Hapus foto lama jika ada
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            // Simpan foto baru
            $path = $request->file('profile_picture')->store('profile-photos', 'public');

            // Masukkan path ke array data yang akan disimpan
            $validatedData['profile_picture'] = $path;
        }

        // 3. UPDATE DATA USER
        $user->fill($validatedData);

        // Reset verifikasi email jika email berubah
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.show', Auth::user()->id)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');

    }
}

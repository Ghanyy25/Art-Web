<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna (Member dan Curator).
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['member', 'curator']);

        // Filter berdasarkan status (misal: 'pending' untuk curator)
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20);

        return view('dashboard.admin.index', compact('users'));
    }

    /**
     * Menyetujui Curator yang statusnya 'pending'.
     */
    public function approveCurator($id)
    {
        $curator = User::where('id', $id)->where('role', 'curator')->where('status', 'pending')->firstOrFail();
        $curator->update(['status' => 'active']);

        // Nanti bisa tambahkan kirim email notifikasi ke Curator

        return redirect()->route('admin.users.index', ['status' => 'pending'])->with('success', 'Curator berhasil disetujui.');
    }

    /**
     * Menghapus pengguna (Member atau Curator).
     */
    public function destroy($id)
    {
        $user = User::whereIn('role', ['member', 'curator'])->findOrFail($id);

        // Tambahan keamanan: Admin tidak bisa menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Hapus file profile picture jika ada
        // Storage::disk('public')->delete($user->profile_picture);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Artworks;
use App\Models\Reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Menyimpan laporan konten dari Member.
     * Laporan ini akan dikirim ke Moderation Queue milik Admin.
     */
    public function store(Request $request, $artworkId)
    {
        // 1. Validasi Artwork
        $artwork = Artworks::findOrFail($artworkId);

        // 2. Validasi Input (Alasan Laporan)
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // 3. Cek apakah user ini sudah pernah melaporkan karya ini (mencegah spam)
        $isReported = Reports::where('artwork_id', $artworkId)
                             ->where('reporter_user_id', Auth::id())
                             ->exists();

        if ($isReported) {
             return redirect()->back()->with('error', 'Anda sudah pernah melaporkan karya ini.');
        }

        // 4. Simpan laporan baru ke database
        Reports::create([
            'reporter_user_id' => Auth::id(),
            'artwork_id' => $artworkId,
            'reason' => $request->reason,
            'status' => 'pending', // Status default untuk ditinjau Admin
        ]);

        // 5. Kirim respon sukses
        return redirect()->back()->with('success', 'Laporan berhasil dikirim dan akan ditinjau oleh Admin.');
    }
}

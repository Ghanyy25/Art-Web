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
    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'artwork_id' => 'nullable|exists:artworks,id', // Opsional
            'comment_id' => 'nullable|exists:comments,id', // Opsional (Baru)
        ]);

        // Pastikan salah satu (artwork_id atau comment_id) terisi
        if (!$request->artwork_id && !$request->comment_id) {
            return response()->json(['status' => 'error', 'message' => 'Target laporan tidak valid.'], 400);
        }

        Reports::create([
            'user_id' => Auth::id(), // Pelapor
            'artwork_id' => $request->artwork_id,
            'comment_id' => $request->comment_id, // Kolom baru di tabel reports (perlu migrasi jika belum ada)
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil dikirim. Terima kasih atas bantuan Anda menjaga komunitas ini.'
        ]);
    }
}

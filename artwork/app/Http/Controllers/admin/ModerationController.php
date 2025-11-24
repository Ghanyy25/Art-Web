<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reports;
use App\Models\Artworks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModerationController extends Controller
{
    /**
     * Menampilkan antrian moderasi (laporan 'pending').
     */
    public function index()
    {
        $reports = Reports::where('status', 'pending')
                          ->with(['artwork.user', 'reporteruser']) // Eager load relasi
                          ->latest()
                          ->paginate(15);

        return view('dashboard.admin.moderation.index', compact('reports'));
    }

    /**
     * Menolak laporan (laporan tidak valid).
     */
    public function dismiss($reportId)
    {
        $report = Reports::findOrFail($reportId);
        $report->update(['status' => 'dismissed']);

        return redirect()->back()->with('success', 'Laporan berhasil ditolak (dismissed).');
    }

    /**
     * Menerima laporan dan menghapus konten (Take Down).
     */
    public function takeDown($reportId)
    {
        $report = Reports::with('artwork')->findOrFail($reportId);

        if ($report->artwork) {
            $artwork = $report->artwork;

            // 1. Hapus file karya dari storage
            Storage::disk('public')->delete($artwork->file_path);

            // 2. Hapus karya (akan otomatis menghapus likes, comments, dll via cascade)
            $artwork->delete();

            // 3. Update status laporan terkait
            Reports::where('artwork_id', $artwork->id)->update(['status' => 'taken_down']);
        } else {
            // Jika karya sudah terhapus tapi laporan masih ada
            $report->update(['status' => 'taken_down']);
        }

        return redirect()->back()->with('success', 'Konten berhasil dihapus (taken down).');
    }
}

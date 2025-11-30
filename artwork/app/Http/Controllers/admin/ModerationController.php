<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reports;
use App\Models\Artworks;
use App\Models\Comments; // Tambahkan ini
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function index()
    {
        // Ambil laporan dengan relasi artwork, comment, dan reporter
        $reports = Reports::with(['reporterUser', 'artwork', 'comment'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('dashboard.admin.moderation.index', compact('reports'));
    }

    public function dismiss($id)
    {
        $report = Reports::findOrFail($id);
        $report->update(['status' => 'dismissed']);

        return redirect()->back()->with('success', 'Laporan diabaikan.');
    }

    public function takeDown($id)
    {
        $report = Reports::findOrFail($id);

        // Cek apa yang dilaporkan: Artwork atau Comment?
        if ($report->artwork_id) {
            $artwork = Artworks::find($report->artwork_id);
            if ($artwork) {
                // Hapus Artwork (file gambar + data)
                $artwork->delete();
            }
        } elseif ($report->comment_id) {
            $comment = Comments::find($report->comment_id);
            if ($comment) {
                // Hapus Komentar
                $comment->delete();
            }
        }

        // Tandai laporan selesai
        $report->update(['status' => 'resolved']);

        return redirect()->back()->with('success', 'Konten berhasil dihapus (Take Down).');
    }
}

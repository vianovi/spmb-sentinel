<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController
 *
 * Menangani semua halaman dashboard untuk santri.
 * Satu controller, beberapa method — struktur tetap rapi.
 */
class DashboardController extends Controller
{
    /**
     * Halaman utama dashboard — Status Tracker + ringkasan.
     */
    public function index()
    {
        $user   = Auth::user();
        $santri = $user->santri;

        // Ambil jadwal aktif untuk ditampilkan di status tracker
        $activeSchedule = Schedule::where('is_active', true)->first();

        // Hitung kelengkapan dokumen per kategori
        $dokumenStats = [];
        if ($santri) {
            $dokumenStats = $santri->documents()
                ->selectRaw('category, count(*) as total, sum(status = "approved") as approved')
                ->groupBy('category')
                ->get()
                ->keyBy('category');
        }

        return view('santri.dashboard.index', compact(
            'user',
            'santri',
            'activeSchedule',
            'dokumenStats',
        ));
    }

    /**
     * Halaman kelengkapan data (non-berkas).
     * Santri melengkapi data yang belum terisi dari proses draft.
     */
    public function profil()
    {
        $user   = Auth::user();
        $santri = $user->santri;

        return view('santri.dashboard.profil', compact('user', 'santri'));
    }

    /**
     * Halaman upload berkas.
     * Dokumen dikelompokkan per kategori.
     */
    public function berkas()
    {
        $user   = Auth::user();
        $santri = $user->santri;

        // Eager load dokumen per kategori
        $dokumen = $santri
            ? $santri->documents()->orderBy('category')->orderBy('created_at')->get()->groupBy('category')
            : collect();

        return view('santri.dashboard.berkas', compact('user', 'santri', 'dokumen'));
    }

    /**
     * Halaman jadwal & informasi gelombang.
     */
    public function jadwal()
    {
        $user   = Auth::user();
        $santri = $user->santri;

        // Tampilkan semua jadwal (aktif maupun arsip) untuk transparansi
        $schedules = Schedule::orderByDesc('is_active')->orderByDesc('created_at')->get();

        return view('santri.dashboard.jadwal', compact('user', 'santri', 'schedules'));
    }

    /**
     * Halaman setting akun.
     */
    public function setting()
    {
        $user   = Auth::user();
        $santri = $user->santri;

        return view('santri.dashboard.setting', compact('user', 'santri'));
    }
}
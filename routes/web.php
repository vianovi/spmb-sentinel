<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Models\Schedule;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini tempat mendaftarkan semua route untuk aplikasi.
| Struktur: Public Routes -> Utility Routes -> Authenticated Routes
|
*/

// =========================================================================
// 1. PUBLIC ROUTES (Bisa diakses tanpa login)
// =========================================================================

Route::get('/', function () {
    // Logic: Mengambil data Gelombang Pendaftaran yang sedang aktif (is_active = 1)
    // Menggunakan try-catch agar tidak error "Table not found" jika belum dimigrate
    try {
        $activeWave = Schedule::where('is_active', true)->first();
    } catch (\Exception $e) {
        $activeWave = null;
    }

    return view('welcome', compact('activeWave'));
})->name('home');

Route::get('/cek-pendaftaran', function () {
    // Placeholder untuk halaman cek status pendaftaran
    return "<div style='display:flex; justify-content:center; align-items:center; height:100vh; font-family:sans-serif;'>
                <h1>🚧 Form Pendaftaran Awal Sedang Dibangun...</h1>
            </div>";
})->name('pendaftaran.cek');


// =========================================================================
// 2. UTILITY ROUTES (Download, Image serving, dll)
// =========================================================================

Route::get('/download-brosur', function () {
    // Tentukan lokasi file (pastikan file ada di folder public/assets/)
    // Kamu bisa ganti 'brosur.pdf' sesuai nama file aslimu (misal .jpg atau .png)
    $filePath = public_path('assets/brosur.jpg');

    // Validasi sederhana: Cek apakah file benar-benar ada
    if (!file_exists($filePath)) {
        // Jika file belum di-upload developer, kembalikan ke home dengan pesan (opsional)
        // atau tampilkan error 404
        abort(404, 'File brosur belum tersedia di server.');
    }

    // Force Download: Browser akan memaksa file untuk didownload, bukan dipreview
    return Response::download($filePath, 'Brosur-Resmi-Sentinel.pdf');
})->name('download.brosur');


// =========================================================================
// 3. AUTHENTICATED ROUTES (Wajib Login)
// =========================================================================

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =========================================================================
// 4. AUTH ROUTES (Bawaan Laravel Breeze)
// =========================================================================
require __DIR__.'/auth.php';
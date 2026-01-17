<?php

use App\Http\Controllers\PreRegistrationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use App\Models\Schedule;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Structure:
| 1. Public (Home)
| 2. Pendaftaran Awal (Guest Logic) -> PreRegistrationController
| 3. Utility (Download, etc)
| 4. Authenticated Area (Dashboard, Profile)
| 5. Auth Logic (Breeze)
*/

// =========================================================================
// 1. PUBLIC HOME ROUTES
// =========================================================================

Route::get('/', function () {
    try {
        // Mengambil gelombang aktif agar Hero Section dinamis
        $activeWave = Schedule::where('is_active', true)->first();
    } catch (\Exception $e) {
        // Fallback jika database belum migrate/kosong
        $activeWave = null;
    }

    return view('welcome', compact('activeWave'));
})->name('home');


// =========================================================================
// 2. MODUL PENDAFTARAN AWAL (GUEST / PRA-LOGIN)
// =========================================================================

// Halaman Form Wizard (Step 1, Step 2)
// Logic: Cek kuota, isi biodata singkat, return draft_id
Route::get('/daftar', [PreRegistrationController::class, 'show'])->name('pendaftaran.cek');

// Simpan Step 1 (Identitas) -> POST
Route::post('/daftar/step-1', [PreRegistrationController::class, 'storeStep1'])->name('pendaftaran.step1');

// Simpan Step 2 (Kontak) -> PUT
Route::put('/daftar/step-2/{id}', [PreRegistrationController::class, 'storeStep2'])->name('pendaftaran.step2');


// =========================================================================
// 3. UTILITY ROUTES
// =========================================================================

Route::get('/download-brosur', function () {
    // Pastikan kamu punya file ini di folder: public/assets/brosur.pdf
    $filePath = public_path('assets/brosur.pdf');

    if (!file_exists($filePath)) {
        // Redirect balik jika file belum di-upload (mencegah error merah)
        return redirect('/')->with('error', 'Maaf, brosur digital sedang diperbarui admin.');
    }

    return Response::download($filePath, 'Brosur-Resmi-Sentinel-2026.pdf');
})->name('download.brosur');


// =========================================================================
// 4. AUTHENTICATED ROUTES (Wajib Login)
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
// 5. AUTH LOGIC (Breeze)
// =========================================================================
require __DIR__.'/auth.php';
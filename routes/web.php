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
| Aku susun begini supaya alurnya jelas:
| 1. Public (Home)
| 2. Pendaftaran Awal (Guest / Token-only cookie)
| 3. Utility
| 4. Authenticated Area
| 5. Auth Logic (Breeze)
*/

// =========================================================================
// 1. PUBLIC HOME ROUTES
// =========================================================================

Route::get('/', function () {
    try {
        $activeWave = Schedule::where('is_active', true)->first();
    } catch (\Exception $e) {
        $activeWave = null;
    }

    return view('welcome', compact('activeWave'));
})->name('home');


// =========================================================================
// 2. MODUL PENDAFTARAN AWAL (GUEST / TOKEN-ONLY COOKIE)
// =========================================================================

// Halaman wizard. Aku tidak pakai draft_id di URL lagi.
// Draft diambil dari cookie token (kalau ada & masih aktif).
Route::get('/daftar', [PreRegistrationController::class, 'show'])->name('pendaftaran.cek');

// Simpan Step 1 -> POST
Route::post('/daftar/step-1', [PreRegistrationController::class, 'storeStep1'])->name('pendaftaran.step1');

// Simpan Step 2 -> PUT
// Catatan: masih pakai {id} supaya Blade kamu minim berubah.
// Controller akan validasi: {id} harus cocok dengan draft milik token cookie.
Route::put('/daftar/step-2/{id}', [PreRegistrationController::class, 'storeStep2'])->name('pendaftaran.step2');

// Step 3: bikin registration_code (human-friendly) + pastikan token cookie masih valid,
// lalu redirect ke /registrasi pakai CODE saja (token tidak ikut URL).
Route::post('/daftar/step-3/{id}/next', [PreRegistrationController::class, 'nextToRegistrasi'])
    ->name('pendaftaran.step3.next');


// =========================================================================
// 3. UTILITY ROUTES
// =========================================================================

Route::get('/download-brosur', function () {
    $filePath = public_path('assets/brosur.pdf');

    if (!file_exists($filePath)) {
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

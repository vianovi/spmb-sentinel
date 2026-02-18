<?php

use App\Http\Controllers\PreRegistrationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Santri\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use App\Models\Schedule;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Urutan:
| 1. Public (Home)
| 2. Pendaftaran Awal (Guest / Token-only cookie)
| 3. Utility
| 4. Dashboard — role-based redirect
| 5. Santri Routes (auth + role:santri)
| 6. Auth Logic (Breeze)
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

Route::get('/daftar', [PreRegistrationController::class, 'show'])->name('pendaftaran.cek');
Route::post('/daftar/step-1', [PreRegistrationController::class, 'storeStep1'])->name('pendaftaran.step1');
Route::put('/daftar/step-2/{id}', [PreRegistrationController::class, 'storeStep2'])->name('pendaftaran.step2');
Route::post('/daftar/step-3/{id}/next', [PreRegistrationController::class, 'nextToRegistrasi'])->name('pendaftaran.step3.next');


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
// 4. DASHBOARD — ROLE-BASED REDIRECT
// =========================================================================

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->isAdmin()) {
        // Nanti redirect ke Filament admin panel
        return redirect('/admin');
    }

    return redirect()->route('santri.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =========================================================================
// 5. SANTRI ROUTES
// =========================================================================

Route::middleware(['auth', 'verified'])
    ->prefix('santri')
    ->name('santri.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profil', [DashboardController::class, 'profil'])->name('profil');
        Route::get('/berkas', [DashboardController::class, 'berkas'])->name('berkas');
        Route::get('/jadwal', [DashboardController::class, 'jadwal'])->name('jadwal');
        Route::get('/setting', [DashboardController::class, 'setting'])->name('setting');
    });


// =========================================================================
// 6. PROFILE ROUTES (Breeze existing — jangan diubah)
// =========================================================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// =========================================================================
// 7. AUTH LOGIC (Breeze)
// =========================================================================
require __DIR__.'/auth.php';
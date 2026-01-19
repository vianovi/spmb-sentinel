<?php

namespace App\Services;

use App\Models\RegistrationDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class RegistrationDraftTokenService
{
    /**
     * Aku sengaja bikin cookie name yang spesifik project,
     * biar gampang dicari di browser dan nggak tabrakan.
     */
    public const COOKIE_NAME = 'spmb_draft_token';

    /**
     * TTL token draft (sesuai arahan Silvia): 24 jam.
     */
    public const TOKEN_TTL_HOURS = 24;

    /**
     * Aku pakai ini untuk ambil token dari cookie request.
     * Kalau kosong / format aneh, aku anggap invalid.
     */
    public function getTokenFromRequest(Request $request): ?string
    {
        $token = $request->cookie(self::COOKIE_NAME);

        if (!is_string($token)) {
            return null;
        }

        $token = trim($token);

        // Minimal length check biar token random "beneran token", bukan sampah.
        if ($token === '' || strlen($token) < 32) {
            return null;
        }

        return $token;
    }

    /**
     * Aku pakai ini untuk ambil draft dari cookie token.
     * Behavior simpel:
     * - token tidak ada => null
     * - token tidak ketemu => null
     * - token expired => draft aku hapus (hard delete) + return null
     */
    public function loadDraftFromRequest(Request $request): ?RegistrationDraft
    {
        $token = $this->getTokenFromRequest($request);
        if (!$token) {
            return null;
        }

        $draft = RegistrationDraft::where('registration_token', $token)->first();
        if (!$draft) {
            return null;
        }

        // Kalau token ada tapi expiry null => aku anggap invalid.
        if (!$draft->registration_token_expires_at) {
            $this->deleteDraftHard($draft);
            return null;
        }

        $expired = Carbon::parse($draft->registration_token_expires_at)->isPast();
        if ($expired) {
            $this->deleteDraftHard($draft);
            return null;
        }

        return $draft;
    }

    /**
     * Aku generate / refresh token untuk draft, sekaligus set cookie.
     * Aku sengaja refresh expiry setiap user klik lanjut,
     * supaya UX-nya santai (asal masih dipakai).
     */
    public function issueCookieForDraft(RegistrationDraft $draft): SymfonyCookie
    {
        // Pastikan draft sudah punya ID sebelum bikin registration_code.
        if (!$draft->exists) {
            $draft->save();
        }

        // Aku bikin/refresh expiry 24 jam dari sekarang.
        $expiresAt = now()->addHours(self::TOKEN_TTL_HOURS);

        // Kalau token kosong atau sudah expired (atau token tidak ada), aku generate baru.
        $mustGenerate = empty($draft->registration_token)
            || empty($draft->registration_token_expires_at)
            || Carbon::parse($draft->registration_token_expires_at)->isPast();

        if ($mustGenerate) {
            $draft->registration_token = $this->generateUniqueToken();
        }

        $draft->registration_token_expires_at = $expiresAt;

        // registration_code aku buat kalau belum ada.
        if (empty($draft->registration_code)) {
            $draft->registration_code = $this->makeRegistrationCode($draft);
        }

        $draft->save();

        // Cookie TTL pakai menit
        $minutes = self::TOKEN_TTL_HOURS * 60;

        // secure cookie: kalau session.secure true (biasanya produksi https), cookie ikut secure.
        // Local dev http tetap jalan karena secure = false.
        $secure = (bool) (config('session.secure') ?? false);

        return Cookie::make(
            self::COOKIE_NAME,
            $draft->registration_token,
            $minutes,
            '/',
            null,
            $secure,
            true,   // HttpOnly (biar JS tidak bisa baca token)
            false,
            'lax'   // SameSite Lax: aman + masih nyaman untuk redirect normal
        );
    }

    /**
     * Kalau token invalid/expired atau registrasi sukses,
     * aku bersihin cookie ini.
     */
    public function forgetCookie(): SymfonyCookie
    {
        return Cookie::forget(self::COOKIE_NAME);
    }

    /**
     * Hard delete draft biar DB nggak numpuk sampah.
     */
    public function deleteDraftHard(RegistrationDraft $draft): void
    {
        // Kalau model pakai SoftDeletes, forceDelete = beneran hapus.
        if (method_exists($draft, 'forceDelete')) {
            $draft->forceDelete();
            return;
        }

        $draft->delete();
    }

    /**
     * Aku bikin code yang human-friendly tapi tetap deterministic.
     * Ini internal aja (kita boleh nggak tampilkan ke user).
     */
    private function makeRegistrationCode(RegistrationDraft $draft): string
    {
        $year = date('Y');
        $seq = str_pad((string) $draft->id, 6, '0', STR_PAD_LEFT);

        return "SPMB-{$year}-{$seq}";
    }

    /**
     * Aku generate token random dan pastikan unik di table.
     * Ini mencegah edge-case collision (meskipun super jarang).
     */
    private function generateUniqueToken(): string
    {
        do {
            $token = Str::random(64);
            $exists = RegistrationDraft::where('registration_token', $token)->exists();
        } while ($exists);

        return $token;
    }
}

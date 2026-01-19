<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Dashboard - Sentinel School</title>

    {{-- Tailwind & Font (samain sama register) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap"
          rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#020617',
                        gold: '#fbbf24',
                        'gold-soft': '#facc15',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    boxShadow: {
                        'card-soft': '0 22px 45px rgba(15,23,42,0.45)',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; }

        .input {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            transition: all .18s ease;
            font-size: 0.95rem;
            color: #0f172a;
        }
        .input::placeholder { color:#9ca3af; font-size:.9rem; }
        .input:focus {
            outline: none;
            border-color: #facc15;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.15);
        }

        .label {
            display:block;
            font-size:.85rem;
            font-weight:800;
            color:#334155;
            margin-bottom:.35rem;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="relative w-full max-w-6xl rounded-3xl overflow-hidden shadow-card-soft bg-slate-900/80 border border-white/5">

        {{-- glow --}}
        <div class="pointer-events-none absolute -top-24 -right-16 w-72 h-72 bg-gradient-to-br from-amber-400/45 via-rose-400/35 to-sky-500/35 rounded-full blur-3xl opacity-70"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-16 w-80 h-80 bg-gradient-to-tr from-emerald-400/30 via-cyan-400/30 to-amber-300/30 rounded-full blur-3xl opacity-70"></div>

        <div class="relative grid lg:grid-cols-[360px,1fr]">
            {{-- SIDEBAR --}}
            <aside class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 px-7 py-8 border-r border-white/5">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center gap-2 text-xs font-semibold text-amber-200/80 hover:text-amber-300 transition">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Kembali ke Home</span>
                </a>

                <div class="mt-8">
                    <p class="text-xs text-slate-400 uppercase tracking-[0.2em]">Portal Santri</p>
                    <h1 class="text-3xl font-serif font-bold leading-tight mt-2">
                        Login<br>
                        <span class="text-gold-soft">Dashboard</span>
                    </h1>
                    <p class="text-xs text-slate-400 mt-3 leading-relaxed">
                        Masuk menggunakan email dan password yang kamu buat saat registrasi.
                        Kalau belum punya akun, mulai dari formulir pendaftaran dulu.
                    </p>
                </div>

                <div class="mt-8 bg-slate-800/60 border border-slate-700 rounded-2xl p-5">
                    <div class="text-[10px] uppercase tracking-[0.18em] text-slate-400 font-bold">Akses Cepat</div>

                    <a href="{{ route('pendaftaran.cek') }}"
                       class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-950/60 hover:bg-slate-950 text-amber-200 px-4 py-2.5 text-xs font-bold transition border border-slate-700">
                        <i class="fa-solid fa-file-pen"></i>
                        <span>Isi Form Pendaftaran</span>
                    </a>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-800/80 text-slate-200 px-4 py-2.5 text-xs font-bold transition border border-slate-700">
                            <i class="fa-solid fa-key"></i>
                            <span>Lupa Password</span>
                        </a>
                    @endif
                </div>

                <div class="mt-auto pt-8 text-[10px] text-slate-500">
                    &copy; {{ date('Y') }} SPMB–Sentinel Boarding School System.
                </div>
            </aside>

            {{-- CONTENT --}}
            <main class="bg-slate-50/95 px-5 sm:px-10 py-8">
                <div class="border-b border-slate-200 pb-4">
                    <h2 class="text-2xl font-serif font-extrabold text-slate-900">Masuk</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Silakan login untuk melanjutkan proses pendaftaran dan pantau status di dashboard.
                    </p>
                </div>

                {{-- Status dari Breeze (misal: reset password sukses) --}}
                <div class="mt-6">
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                </div>

                {{-- Error custom --}}
                @if (session('error'))
                    <div class="mb-5 bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-r-xl shadow-sm text-sm text-red-800 flex gap-3">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                        <div>
                            <div class="font-semibold mb-0.5">Terjadi Kesalahan</div>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-6">
                    @csrf

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                        <div class="space-y-5">
                            <div>
                                <label class="label">Email</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autofocus
                                       autocomplete="username"
                                       class="input"
                                       placeholder="nama@email.com">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <label class="label">Password</label>
                                <input type="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       class="input"
                                       placeholder="********">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                                <label for="remember_me" class="inline-flex items-center text-sm text-slate-600">
                                    <input id="remember_me" type="checkbox"
                                           class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-amber-200"
                                           name="remember">
                                    <span class="ms-2 font-semibold">Ingat saya</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="text-sm font-bold text-slate-500 hover:text-slate-900 transition"
                                       href="{{ route('password.request') }}">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2 border-t border-slate-200">
                                <a class="text-sm font-bold text-slate-600 hover:text-slate-900 transition"
                                   href="{{ route('pendaftaran.cek') }}">
                                    Belum punya akun? Isi pendaftaran
                                </a>

                                <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy hover:bg-navy/90 text-white px-6 py-3 text-sm font-extrabold shadow-lg transition">
                                    Login
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500">
                        Kalau login gagal, pastikan email sesuai yang kamu pakai saat registrasi dan password benar.
                    </p>
                </form>
            </main>
        </div>
    </div>
</div>
</body>
</html>

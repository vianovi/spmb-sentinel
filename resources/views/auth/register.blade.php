<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun - Sentinel School</title>

    {{-- Tailwind & Font (samain style sama wizard) --}}
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
        .input:focus {
            outline: none;
            border-color: #facc15;
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.15);
        }
        .input[readonly]{
            background:#f1f5f9;
            color:#334155;
            cursor:not-allowed;
        }
        .label {
            display:block;
            font-size:.85rem;
            font-weight:800;
            color:#334155;
            margin-bottom:.35rem;
        }
        .error-text { font-size:.8rem; color:#e11d48; margin-top:.35rem; display:block; }
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
                    <p class="text-xs text-slate-400 uppercase tracking-[0.2em]">Akun Calon Santri</p>
                    <h1 class="text-3xl font-serif font-bold leading-tight mt-2">
                        Registrasi<br>
                        <span class="text-gold-soft">Akun</span>
                    </h1>
                    <p class="text-xs text-slate-400 mt-3 leading-relaxed">
                        Aku bikin akun kamu berdasarkan data draft (Step 1–3).
                        Token akses disimpan di cookie (tidak ada di URL) dan aktif 24 jam sejak terakhir lanjut.
                    </p>
                </div>

                {{-- MINI SUMMARY --}}
                <div class="mt-8 bg-slate-800/60 border border-slate-700 rounded-2xl p-5">
                    <div class="text-[10px] uppercase tracking-[0.18em] text-slate-400 font-bold">Ringkasan Draft</div>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-400">Nama</span>
                            <span class="font-bold text-slate-100 text-right">{{ $draft->nama_lengkap }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-400">NIK</span>
                            <span class="font-bold text-slate-100">{{ $draft->nik }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-400">NISN</span>
                            <span class="font-bold text-slate-100">{{ $draft->nisn }}</span>
                        </div>
                        <div class="flex justify-between gap-3">
                            <span class="text-slate-400">WhatsApp</span>
                            <span class="font-bold text-slate-100">{{ $draft->no_hp }}</span>
                        </div>
                    </div>

                    <a href="{{ route('pendaftaran.cek', ['mode' => 'edit']) }}"
                       class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-slate-950/60 hover:bg-slate-950 text-amber-200 px-4 py-2.5 text-xs font-bold transition border border-slate-700">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit Draft</span>
                    </a>
                </div>

                <div class="mt-auto pt-8 text-[10px] text-slate-500">
                    &copy; {{ date('Y') }} SPMB–Sentinel Boarding School System.
                </div>
            </aside>

            {{-- CONTENT --}}
            <main class="bg-slate-50/95 px-5 sm:px-10 py-8">
                {{-- FLASH MESSAGES --}}
                @if (session('error'))
                    <div class="mb-5 bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-r-xl shadow-sm text-sm text-red-800 flex gap-3">
                        <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                        <div>
                            <div class="font-semibold mb-0.5">Terjadi Kesalahan</div>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-r-xl shadow-sm">
                        <div class="flex items-center gap-2 text-red-700 font-semibold mb-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Beberapa data perlu diperiksa:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs sm:text-sm text-red-700 space-y-0.5">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex items-end justify-between gap-4 border-b border-slate-200 pb-4">
                    <div>
                        <h2 class="text-2xl font-serif font-extrabold text-slate-900">Buat Password Login</h2>
                        <p class="text-sm text-slate-500 mt-1">
                            Setelah akun jadi, aku langsung login-in kamu dan bawa ke dashboard santri.
                        </p>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 text-xs font-bold text-slate-600">
                        <i class="fa-solid fa-shield-halved text-amber-500"></i>
                        <span>Token-only (cookie)</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-6">
                    @csrf

                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="label">Email Login</label>
                                <div class="relative">
                                    <input type="email" name="email" class="input pr-10"
                                           value="{{ $draft->email }}" readonly>
                                    <i class="fa-solid fa-lock absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">
                                    Email diambil dari draft dan tidak bisa diubah di sini (biar aman & konsisten).
                                </p>
                            </div>

                            <div>
                                <label class="label">Password <span class="text-rose-600">*</span></label>
                                <input type="password" name="password" class="input"
                                       placeholder="Minimal 8 karakter" autocomplete="new-password" required>
                                @error('password') <span class="error-text">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="label">Ulangi Password <span class="text-rose-600">*</span></label>
                                <input type="password" name="password_confirmation" class="input"
                                       placeholder="Harus sama" autocomplete="new-password" required>
                            </div>

                            <div class="md:col-span-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
                                <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900 transition">
                                    Sudah punya akun? Login
                                </a>

                                <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy hover:bg-navy/90 text-white px-6 py-3 text-sm font-extrabold shadow-lg transition">
                                    Buat Akun & Masuk
                                    <i class="fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500">
                        Dengan membuat akun, kamu menyetujui bahwa data yang diisi sudah benar dan siap diproses panitia.
                    </p>
                </form>
            </main>
        </div>
    </div>
</div>
</body>
</html>

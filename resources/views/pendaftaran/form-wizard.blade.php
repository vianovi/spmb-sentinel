<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Santri Baru - Sentinel School</title>

    {{-- Tailwind & Font --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap"
          rel="stylesheet">

    {{-- AnimeJS untuk animasi halus --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>

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
                    },
                    keyframes: {
                        'glow-move': {
                            '0%': {transform: 'translate3d(-10px, 0, 0)'},
                            '50%': {transform: 'translate3d(10px, 10px, 0)'},
                            '100%': {transform: 'translate3d(-10px, 0, 0)'},
                        },
                    },
                    animation: {
                        'glow-move': 'glow-move 14s ease-in-out infinite alternate',
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            transition: all 0.18s ease;
            font-size: 0.95rem;
            color: #0f172a;
        }

        .input-field::placeholder {
            color: #9ca3af;
            font-size: 0.85rem;
        }

        .input-field:focus {
            outline: none;
            border-color: #facc15;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(250, 204, 21, 0.15);
        }

        .input-error {
            border-color: #e11d48;
            background-color: #fff1f2;
        }

        .label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.35rem;
        }

        .label span {
            color: #e11d48;
            margin-left: 2px;
        }

        .error-text {
            font-size: 0.75rem;
            color: #e11d48;
            margin-top: 4px;
            display: block;
        }

        .hint {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 4px;
        }

        .radio-card {
            border-radius: 0.85rem;
            border: 1px solid #e2e8f0;
            background: radial-gradient(circle at top left, rgba(248, 250, 252, 0.9), #f1f5f9);
            padding: 0.8rem 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            transition: all 0.18s ease;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }

        .radio-card i {
            font-size: 1rem;
        }

        .radio-card-active {
            border-color: #facc15;
            background: radial-gradient(circle at top left, rgba(250, 204, 21, 0.25), #f9fafb);
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.35);
            color: #020617;
        }

        /* Mobile stepper */
        .m-step-active {
            color: #fbbf24;
            font-weight: 700;
            border-bottom: 2px solid #fbbf24;
        }

        .m-step-inactive {
            color: #9ca3af;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-slate-900">
@php $s = $viewStep ?? 1; @endphp

<div class="min-h-screen flex items-center justify-center py-6 px-4 md:px-8">
    <div class="relative w-full max-w-7xl 2xl:max-w-[92rem] rounded-3xl overflow-hidden shadow-card-soft bg-slate-900/80 border border-white/5 card-anim">

        {{-- dynamic glow --}}
        <div class="pointer-events-none absolute -top-24 -right-16 w-72 h-72 bg-gradient-to-br from-amber-400/50 via-rose-400/40 to-sky-500/40 rounded-full blur-3xl opacity-70 animate-glow-move"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-16 w-80 h-80 bg-gradient-to-tr from-emerald-400/35 via-cyan-400/35 to-amber-300/35 rounded-full blur-3xl opacity-70 animate-glow-move"></div>

        <div class="relative grid md:grid-cols-[300px,1fr]">

            {{-- =========================
               SIDEBAR KIRI
               ========================= --}}
            <div class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-slate-100 px-6 sm:px-8 py-6 sm:py-8 flex flex-col border-r border-white/5">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ url('/') }}"
                       class="inline-flex items-center gap-2 text-xs font-semibold text-amber-200/80 hover:text-amber-300 transition">
                        <i class="fa-solid fa-arrow-left text-[11px]"></i>
                        <span>Kembali ke Home</span>
                    </a>

                    <span class="px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700 text-[10px] uppercase tracking-wide text-slate-300 !text-green-500">
                        Gelombang Aktif
                    </span>
                </div>

                <div class="mt-8">
                    <p class="text-xs text-slate-400 uppercase tracking-[0.2em]">Formulir Online</p>
                    <h1 class="text-3xl sm:text-4xl font-serif font-bold leading-tight mt-2">
                        Formulir<br>
                        <span class="text-gold-soft">Pendaftaran</span>
                    </h1>
                    <p class="text-xs text-slate-400 mt-3">
                        Tahun Ajaran
                        <span class="font-semibold text-amber-200">
                            {{ $activeWave->academic_year ?? 'Terbaru' }}
                        </span>
                    </p>
                </div>

                {{-- Stepper desktop --}}
                <div class="space-y-6 mt-10">
                    {{-- Step 1 --}}
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $s >= 1 ? 'bg-gold-soft text-slate-900 shadow-lg shadow-amber-400/40' : 'bg-slate-800 text-slate-500' }}">
                                1
                            </div>
                            <div class="hidden md:block absolute top-8 left-1/2 -translate-x-1/2 w-px h-12
                                {{ $s >= 2 ? 'bg-gold-soft' : 'bg-slate-700' }}"></div>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] {{ $s >= 1 ? 'text-amber-300' : 'text-slate-500' }}">
                                Langkah 1
                            </p>
                            <p class="text-sm font-semibold {{ $s >= 1 ? 'text-white' : 'text-slate-400' }}">
                                Identitas Santri
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1">Data diri utama calon santri.</p>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $s >= 2 ? 'bg-gold-soft text-slate-900 shadow-lg shadow-amber-400/40' : 'bg-slate-800 text-slate-500' }}">
                                2
                            </div>
                            <div class="hidden md:block absolute top-8 left-1/2 -translate-x-1/2 w-px h-12
                                {{ $s >= 3 ? 'bg-gold-soft' : 'bg-slate-700' }}"></div>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] {{ $s >= 2 ? 'text-amber-300' : 'text-slate-500' }}">
                                Langkah 2
                            </p>
                            <p class="text-sm font-semibold {{ $s >= 2 ? 'text-white' : 'text-slate-400' }}">
                                Kontak & Alamat
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1">Domisili dan kontak orang tua.</p>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="flex items-start gap-4">
                        <div class="relative">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $s >= 3 ? 'bg-gold-soft text-slate-900 shadow-lg shadow-amber-400/40' : 'bg-slate-800 text-slate-500' }}">
                                3
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] {{ $s >= 3 ? 'text-amber-300' : 'text-slate-500' }}">
                                Langkah 3
                            </p>
                            <p class="text-sm font-semibold {{ $s >= 3 ? 'text-white' : 'text-slate-400' }}">
                                Review & Akun
                            </p>
                            <p class="text-[11px] text-slate-400 mt-1">Cek kembali data sebelum final.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-8 text-[10px] text-slate-500">
                    &copy; {{ date('Y') }} SPMB-Sentinel Boarding School System.
                </div>
            </div>

            {{-- =========================
               KONTEN KANAN
               ========================= --}}
            <div class="flex-1 bg-slate-50/95 relative flex flex-col">

                {{-- Mobile stepper --}}
                <div class="border-b border-slate-200 px-4 sm:px-8 py-3 bg-white/80 backdrop-blur">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                        <div class="flex gap-4">
                            <button type="button" class="{{ $s === 1 ? 'm-step-active' : 'm-step-inactive' }}">1. Identitas</button>
                            <button type="button" class="{{ $s === 2 ? 'm-step-active' : 'm-step-inactive' }}">2. Kontak & Alamat</button>
                            <button type="button" class="{{ $s === 3 ? 'm-step-active' : 'm-step-inactive' }}">3. Review</button>
                        </div>
                        <div class="hidden sm:block text-[11px] text-slate-400">
                            Fase Aktif: <span class="font-bold text-slate-700">{{ $activeWave->batch_name }}</span>
                        </div>
                    </div>
                </div>

                {{-- SCROLLABLE FORM AREA --}}
                <div class="flex-1 p-4 sm:p-8 md:p-10 overflow-y-auto">

                    {{-- Alert Success & Error --}}
                    @if (session('success'))
                        <div class="mb-4 sm:mb-6 bg-emerald-50 border-l-4 border-emerald-500 px-4 py-3 rounded-r-xl shadow-sm text-sm text-emerald-800 flex gap-3">
                            <i class="fa-solid fa-circle-check mt-0.5"></i>
                            <div>
                                <div class="font-semibold mb-0.5">Berhasil</div>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 sm:mb-6 bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-r-xl shadow-sm text-sm text-red-800 flex gap-3">
                            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                            <div>
                                <div class="font-semibold mb-0.5">Terjadi Kesalahan</div>
                                <p>{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 sm:mb-6 bg-red-50 border-l-4 border-red-500 px-4 py-3 rounded-r-xl shadow-sm">
                            <div class="flex items-center gap-2 text-red-700 font-semibold mb-2">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>Beberapa data perlu diperiksa lagi:</span>
                            </div>
                            <ul class="list-disc list-inside text-xs sm:text-sm text-red-700 space-y-0.5">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- =========================
                       STEP 3: REVIEW & LANJUT REGISTRASI (DI KONTEN KANAN)
                       ========================= --}}
                    @if($viewStep == 3 && $draft)
                        <div id="step3-container">
                            <div class="mb-8 border-b border-slate-200 pb-4 flex items-end justify-between gap-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-navy font-serif">Review & Lanjut Registrasi</h3>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Pastikan data sudah benar. Setelah ini kamu akan diarahkan ke halaman pembuatan akun.
                                    </p>
                                </div>

                                {{-- NOTE TOKEN-ONLY: aku sengaja hilangkan draft_id di URL supaya orang lain tidak bisa tebak-tebakan id --}}
                                <a href="{{ route('pendaftaran.cek', ['mode' => 'edit']) }}"
                                   class="text-slate-500 font-bold text-sm hover:text-navy transition flex items-center gap-2 group">
                                    <span class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center group-hover:bg-slate-300 transition">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </span>
                                    <span>Edit Data</span>
                                </a>
                            </div>

                            {{-- Kode Pendaftaran (ditampilkan) --}}
                            <div class="mb-6 bg-slate-50 border border-slate-200 rounded-xl p-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="text-xs uppercase font-bold text-slate-500 tracking-wider">Kode Pendaftaran</div>

                                        {{-- NOTE SECURITY: aku tidak menampilkan kode mentah di layar (mengurangi risiko disalin/tersebar) --}}
                                        <div class="text-xl font-extrabold text-navy mt-1">
                                            {{ $draft->registration_code ? 'Sudah disiapkan oleh sistem' : 'Akan disiapkan setelah kamu menekan tombol lanjut' }}
                                        </div>

                                        <p class="text-xs text-slate-500 mt-2">
                                            Demi keamanan, kode pendaftaran tidak ditampilkan di layar pada tahap ini.
                                        </p>
                                    </div>
                                    <div class="hidden md:flex items-center gap-2 text-gold font-bold">
                                        <i class="fa-solid fa-shield-halved"></i>
                                        <span class="text-sm">Aman & Terverifikasi</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Preview data --}}
                            <div class="grid lg:grid-cols-2 gap-6 mb-6">
                                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                                    <h4 class="font-extrabold text-navy mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-id-card text-gold"></i> Identitas
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between gap-3"><span class="text-slate-500">Nama</span><span class="font-bold text-navy text-right">{{ $draft->nama_lengkap }}</span></div>
                                        <div class="flex justify-between gap-3"><span class="text-slate-500">NISN</span><span class="font-bold text-navy">{{ $draft->nisn }}</span></div>
                                        <div class="flex justify-between gap-3"><span class="text-slate-500">NIK</span><span class="font-bold text-navy">{{ $draft->nik }}</span></div>
                                        <div class="flex justify-between gap-3"><span class="text-slate-500">Jenis Kelamin</span><span class="font-bold text-navy">{{ $draft->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span></div>
                                        <div class="flex justify-between gap-3"><span class="text-slate-500">TTL</span><span class="font-bold text-navy text-right">{{ $draft->tempat_lahir }}, {{ \Carbon\Carbon::parse($draft->tanggal_lahir)->format('d-m-Y') }}</span></div>
                                    </div>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                                    <h4 class="font-extrabold text-navy mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-location-dot text-gold"></i> Kontak & Alamat
                                    </h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between gap-3"><span class="text-slate-500">Nama Ibu</span><span class="font-bold text-navy text-right">{{ $draft->nama_ibu }}</span></div>
                                        <div class="flex justify-between gap-3"><span class="text-slate-500">WhatsApp</span><span class="font-bold text-navy">{{ $draft->no_hp }}</span></div>
                                        <div class="flex justify-between gap-3"><span class="text-slate-500">Email</span><span class="font-bold text-navy text-right">{{ $draft->email ?? '-' }}</span></div>
                                        <div class="pt-2">
                                            <div class="text-slate-500 mb-1">Alamat Lengkap</div>
                                            <div class="font-bold text-navy leading-relaxed">{{ $draft->alamat_lengkap }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Form step 3 (asal sekolah + email jika belum ada) --}}
                            <form action="{{ route('pendaftaran.step3.next', $draft->id) }}" method="POST">
                                @csrf

                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6">
                                    <h4 class="font-extrabold text-navy mb-4 flex items-center gap-2">
                                        <i class="fa-solid fa-user-check text-gold"></i> Data untuk Akun & Pendaftaran
                                    </h4>

                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div class="md:col-span-2">
                                            <label class="label">Asal Sekolah <span>*</span></label>
                                            <input type="text" name="asal_sekolah"
                                                   value="{{ old('asal_sekolah', $draft->asal_sekolah ?? '') }}"
                                                   class="input-field @error('asal_sekolah') input-error @enderror"
                                                   placeholder="Contoh: SMP Negeri 3 Semarang"
                                                   onblur="this.value = toTitleCase(this.value)">
                                            @error('asal_sekolah') <span class="error-text">{{ $message }}</span> @enderror
                                            <p class="hint">Tulis nama sekolah terakhir dengan jelas agar data rapi.</p>
                                        </div>

                                        @if(empty($draft->email))
                                            <div class="md:col-span-2">
                                                <label class="label">Email untuk Login <span>*</span></label>
                                                <input type="email" name="email"
                                                       value="{{ old('email') }}"
                                                       class="input-field @error('email') input-error @enderror"
                                                       placeholder="contoh: nama@email.com">
                                                @error('email') <span class="error-text">{{ $message }}</span> @enderror
                                                <p class="hint">Email ini dipakai untuk pembuatan akun.</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-5">
                                        <label class="flex items-start gap-3 cursor-pointer select-none">
                                            <input type="checkbox" name="agreement" value="1"
                                                   class="mt-1 w-5 h-5 rounded border-gray-300 text-navy focus:ring-gold">
                                            <div>
                                                <div class="text-sm font-bold text-navy">
                                                    Saya menyatakan data yang saya isi adalah benar.
                                                </div>
                                                <div class="text-xs text-slate-500 mt-1">
                                                    Setelah lanjut, kamu akan diarahkan ke halaman pembuatan akun.
                                                </div>
                                                @error('agreement') <span class="error-text">{{ $message }}</span> @enderror
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex justify-end mt-8">
                                    <button type="submit"
                                            class="bg-navy hover:bg-navy/90 text-white px-8 py-3.5 rounded-xl font-bold text-sm shadow-xl flex items-center gap-2 transform transition hover:-translate-y-1">
                                        Lanjut Buat Akun <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- =========================
                       STEP 1: IDENTITAS SANTRI
                       ========================= --}}
                    @if($viewStep == 1)
                        <div id="step1-container">
                            <div class="mb-6 sm:mb-8 border-b border-slate-200 pb-4">
                                <h3 class="text-xl sm:text-2xl font-bold text-slate-900">
                                    Identitas Calon Santri
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    Lengkapi data pribadi sesuai Kartu Keluarga (KK) dan Akta Kelahiran.
                                </p>
                            </div>

                            <form action="{{ route('pendaftaran.step1') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $activeWave->id ?? 1 }}">
                                @if($draft)
                                    <input type="hidden" name="existing_draft_id" value="{{ $draft->id }}">
                                @endif

                                {{-- Nama Lengkap --}}
                                <div>
                                    <label class="label">Nama Lengkap <span>*</span></label>
                                    <input type="text" name="nama_lengkap"
                                           value="{{ old('nama_lengkap', $draft->nama_lengkap ?? '') }}"
                                           class="input-field @error('nama_lengkap') input-error @enderror"
                                           placeholder="Contoh: Ahmad Zaki Pratama"
                                           onblur="normalizeNameField(this)">
                                    <p class="hint">
                                        Gunakan huruf awal kapital pada setiap kata, sesuai Akta Kelahiran / KK.
                                        Contoh: <span class="font-semibold">Ahmad Zaki Pratama</span>, bukan
                                        <span class="font-semibold lowercase">ahmad zaki pratama</span>.
                                    </p>
                                    @error('nama_lengkap')
                                    <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- NISN & NIK --}}
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="label">NISN (10 Angka) <span>*</span></label>
                                        <input type="text" name="nisn" inputmode="numeric" maxlength="10"
                                               value="{{ old('nisn', $draft->nisn ?? '') }}"
                                               class="input-field @error('nisn') input-error @enderror"
                                               placeholder="Contoh: 0012345678"
                                               oninput="onlyNumber(this, 10)">
                                        <p class="hint">
                                            Isi dengan 10 digit angka tanpa spasi atau tanda baca. Cek di ijazah SD/SMP.
                                        </p>
                                        @error('nisn')
                                        <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="label">NIK (16 Angka) <span>*</span></label>
                                        <input type="text" name="nik" inputmode="numeric" maxlength="16"
                                               value="{{ old('nik', $draft->nik ?? '') }}"
                                               class="input-field @error('nik') input-error @enderror"
                                               placeholder="Contoh: 3301123456789001"
                                               oninput="onlyNumber(this, 16)">
                                        <p class="hint">
                                            NIK harus sama persis dengan yang tertera pada KTP/KK (16 digit angka).
                                        </p>
                                        @error('nik')
                                        <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- TTL --}}
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="label">Tempat Lahir <span>*</span></label>
                                        <input type="text" name="tempat_lahir"
                                               value="{{ old('tempat_lahir', $draft->tempat_lahir ?? '') }}"
                                               class="input-field @error('tempat_lahir') input-error @enderror"
                                               placeholder="Contoh: Semarang"
                                               onblur="normalizeNameField(this)">
                                        <p class="hint">
                                            Isi nama kota/kabupaten, bukan kecamatan. Contoh:
                                            <span class="font-semibold">Semarang</span>.
                                        </p>
                                        @error('tempat_lahir')
                                        <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="label">Tanggal Lahir <span>*</span></label>
                                        <input type="date" name="tanggal_lahir"
                                               value="{{ old('tanggal_lahir', isset($draft->tanggal_lahir) ? \Carbon\Carbon::parse($draft->tanggal_lahir)->format('Y-m-d') : '') }}"
                                               class="input-field @error('tanggal_lahir') input-error @enderror">
                                        <p class="hint">
                                            Pastikan sesuai dengan yang tertera pada Akta Kelahiran/KK.
                                        </p>
                                        @error('tanggal_lahir')
                                        <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Jenis Kelamin --}}
                                @php
                                    $jk = old('jenis_kelamin', $draft->jenis_kelamin ?? '');
                                @endphp
                                <div>
                                    <label class="label">Jenis Kelamin <span>*</span></label>

                                    <div class="grid grid-cols-2 gap-4" id="jk-group">
                                        <label class="radio-card js-radio-card {{ $jk === 'L' ? 'radio-card-active' : '' }}"
                                               data-group="jenis_kelamin" data-value="L">
                                            <input type="radio" name="jenis_kelamin" value="L" class="hidden"
                                                   {{ $jk === 'L' ? 'checked' : '' }}>
                                            <i class="fa-solid fa-person"></i>
                                            <span>Laki-laki</span>
                                        </label>

                                        <label class="radio-card js-radio-card {{ $jk === 'P' ? 'radio-card-active' : '' }}"
                                               data-group="jenis_kelamin" data-value="P">
                                            <input type="radio" name="jenis_kelamin" value="P" class="hidden"
                                                   {{ $jk === 'P' ? 'checked' : '' }}>
                                            <i class="fa-solid fa-person-dress"></i>
                                            <span>Perempuan</span>
                                        </label>
                                    </div>

                                    <p class="hint">
                                        Ketuk salah satu pilihan di atas sampai berwarna kuning.
                                    </p>
                                    @error('jenis_kelamin')
                                    <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Footer Step 1 --}}
                                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                                    <p class="text-[11px] text-slate-500">
                                        Langkah 1 dari 3 • Mohon diisi dengan teliti, data akan digunakan sepanjang masa belajar.
                                    </p>
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-full bg-navy text-gold-soft px-5 py-2.5 text-sm font-semibold shadow-md shadow-slate-700/60 hover:shadow-lg hover:-translate-y-0.5 transition">
                                        Lanjut ke Langkah 2
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    {{-- =========================
                       STEP 2: KONTAK & ALAMAT
                       ========================= --}}
                    @if($viewStep == 2 && $draft)
                        <div id="step2-container">
                            <div class="mb-6 sm:mb-8 border-b border-slate-200 pb-4 flex justify-between items-end gap-3">
                                <div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900">
                                        Alamat Domisili & Kontak
                                    </h3>
                                    <p class="text-sm text-slate-500 mt-1">
                                        Pastikan alamat dan nomor kontak aktif agar panitia mudah menghubungi.
                                    </p>
                                </div>
                                <div class="hidden sm:block text-right">
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.18em]">Santri</div>
                                    <div class="font-semibold text-gold-soft text-sm">
                                        {{ $draft->nama_lengkap }}
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('pendaftaran.step2', $draft->id) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                {{-- Nama Ibu --}}
                                <div>
                                    <label class="label">Nama Ibu Kandung <span>*</span></label>
                                    <input type="text" name="nama_ibu"
                                           value="{{ old('nama_ibu', $draft->nama_ibu ?? '') }}"
                                           class="input-field @error('nama_ibu') input-error @enderror"
                                           placeholder="Contoh: Siti Nur Aisyah"
                                           onblur="normalizeNameField(this)">
                                    <p class="hint">
                                        Gunakan huruf awal kapital pada setiap kata. Contoh:
                                        <span class="font-semibold">Siti Nur Aisyah</span>.
                                    </p>
                                    @error('nama_ibu')
                                    <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Alamat --}}
                                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 sm:p-6">
                                    <h4 class="text-sm font-bold uppercase tracking-[0.18em] text-slate-500 flex items-center gap-2 mb-4 border-b border-slate-200 pb-2">
                                        <i class="fa-solid fa-map-location-dot text-gold-soft"></i>
                                        Detail Alamat Domisili
                                    </h4>

                                    <div class="space-y-4">
                                        {{-- Jalan/Dusun/Perumahan --}}
                                        <div>
                                            <label class="label text-xs">Jalan / Dusun / Perumahan <span>*</span></label>
                                            <input type="text" name="addr_jalan"
                                                   value="{{ old('addr_jalan', $draft->addr_jalan ?? '') }}"
                                                   class="input-field @error('addr_jalan') input-error @enderror"
                                                   placeholder="Contoh: Jl. Diponegoro No. 12, Perumahan Griya Asri"
                                                   onblur="normalizeAddressField(this)">
                                            <p class="hint">
                                                Tulis dengan jelas, termasuk nomor rumah dan nama perumahan bila ada.
                                            </p>
                                            @error('addr_jalan')
                                            <span class="error-text">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- RT / RW --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="label text-xs">RT <span>*</span></label>
                                                <input type="text" name="addr_rt" inputmode="numeric" maxlength="3"
                                                       value="{{ old('addr_rt', $draft->addr_rt ?? '') }}"
                                                       class="input-field @error('addr_rt') input-error @enderror"
                                                       placeholder="Contoh: 005"
                                                       oninput="onlyNumber(this, 3)">
                                                @error('addr_rt')
                                                <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="label text-xs">RW <span>*</span></label>
                                                <input type="text" name="addr_rw" inputmode="numeric" maxlength="3"
                                                       value="{{ old('addr_rw', $draft->addr_rw ?? '') }}"
                                                       class="input-field @error('addr_rw') input-error @enderror"
                                                       placeholder="Contoh: 007"
                                                       oninput="onlyNumber(this, 3)">
                                                @error('addr_rw')
                                                <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Desa & Kecamatan --}}
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="label text-xs">Kelurahan / Desa <span>*</span></label>
                                                <input type="text" name="addr_desa"
                                                       value="{{ old('addr_desa', $draft->addr_desa ?? '') }}"
                                                       class="input-field @error('addr_desa') input-error @enderror"
                                                       placeholder="Contoh: Tegalsari"
                                                       onblur="normalizeNameField(this)">
                                                @error('addr_desa')
                                                <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="label text-xs">Kecamatan <span>*</span></label>
                                                <input type="text" name="addr_kec"
                                                       value="{{ old('addr_kec', $draft->addr_kec ?? '') }}"
                                                       class="input-field @error('addr_kec') input-error @enderror"
                                                       placeholder="Contoh: Banyumanik"
                                                       onblur="normalizeNameField(this)">
                                                @error('addr_kec')
                                                <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- Kab & Prov --}}
                                        <div class="grid md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="label text-xs">Kabupaten / Kota <span>*</span></label>
                                                <input type="text" name="addr_kab"
                                                       value="{{ old('addr_kab', $draft->addr_kab ?? '') }}"
                                                       class="input-field @error('addr_kab') input-error @enderror"
                                                       placeholder="Contoh: Kab. Semarang"
                                                       onblur="normalizeNameField(this)">
                                                @error('addr_kab')
                                                <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="label text-xs">Provinsi <span>*</span></label>
                                                <input type="text" name="addr_prov"
                                                       value="{{ old('addr_prov', $draft->addr_prov ?? '') }}"
                                                       class="input-field @error('addr_prov') input-error @enderror"
                                                       placeholder="Contoh: Jawa Tengah"
                                                       onblur="normalizeNameField(this)">
                                                @error('addr_prov')
                                                <span class="error-text">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Kontak --}}
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="label">No. HP Orang Tua / Wali <span>*</span></label>
                                        <input type="tel" name="no_hp" inputmode="numeric" maxlength="14"
                                               value="{{ old('no_hp', $draft->no_hp ?? '') }}"
                                               class="input-field @error('no_hp') input-error @enderror"
                                               placeholder="Contoh: 081234567890"
                                               oninput="onlyNumber(this, 14)">
                                        <p class="hint">
                                            Gunakan nomor WhatsApp aktif, tulis tanpa spasi atau tanda hubung.
                                        </p>
                                        @error('no_hp')
                                        <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="label">Email Orang Tua / Wali</label>
                                        <input type="email" name="email"
                                               value="{{ old('email', $draft->email ?? '') }}"
                                               class="input-field @error('email') input-error @enderror"
                                               placeholder="Contoh: orangtua.santri@email.com"
                                               onblur="this.value = this.value.trim().toLowerCase();">
                                        <p class="hint">
                                            Opsional, namun sangat membantu untuk pengiriman informasi resmi.
                                        </p>
                                        @error('email')
                                        <span class="error-text">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Footer Step 2 --}}
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-6 pt-5 border-t border-slate-200">
                                    @if(isset($draft->id))
                                        {{-- NOTE TOKEN-ONLY: kembali ke /daftar tanpa bawa id agar identitas draft tidak bocor lewat URL --}}
                                        <a href="{{ route('pendaftaran.cek', ['draft_id' => $draft->id, 'mode' => 'edit']) }}"
                                           class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-navy transition group">
                                            <span class="w-8 h-8 rounded-full border border-slate-300 flex items-center justify-center group-hover:border-gold-soft group-hover:text-gold-soft transition">
                                                <i class="fa-solid fa-arrow-left text-[11px]"></i>
                                            </span>
                                            <span>Kembali ke Ringkasan Data</span>
                                        </a>
                                    @endif

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-full bg-gold-soft text-navy px-5 py-2.5 text-sm font-semibold shadow-md shadow-amber-300/50 hover:shadow-lg hover:-translate-y-0.5 transition_ATTACHhition">
                                        Simpan &amp; Lanjut
                                        <i class="fa-solid fa-check-circle"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

{{-- SCRIPT BANTUAN FORMAT INPUT --}}
<script>
    function toTitleCase(str) {
        return str
            .toLowerCase()
            .split(' ')
            .filter(Boolean)
            .map(function (word) {
                return word.charAt(0).toUpperCase() + word.slice(1);
            })
            .join(' ');
    }

    function normalizeNameField(el) {
        if (!el || !el.value) return;
        el.value = toTitleCase(el.value.trim());
    }

    function normalizeAddressField(el) {
        if (!el || !el.value) return;
        el.value = el.value.replace(/\s+/g, ' ').trim();
    }

    function onlyNumber(el, maxLength) {
        if (!el) return;
        el.value = el.value.replace(/[^0-9]/g, '');
        if (maxLength && el.value.length > maxLength) {
            el.value = el.value.slice(0, maxLength);
        }
    }

    // Radio-card interaktif (klik kartu -> otomatis terpilih + efek aktif)
    function setupRadioCards() {
        const cards = document.querySelectorAll('.js-radio-card');
        if (!cards.length) return;

        cards.forEach(card => {
            card.addEventListener('click', function () {
                const groupName = card.dataset.group;
                const value = card.dataset.value;

                document.querySelectorAll(`.js-radio-card[data-group="${groupName}"]`)
                    .forEach(c => c.classList.remove('radio-card-active'));

                card.classList.add('radio-card-active');

                const radio = card.querySelector(`input[type="radio"][name="${groupName}"][value="${value}"]`);
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupRadioCards();

        if (window.anime) {
            anime({
                targets: '.card-anim',
                opacity: [0, 1],
                translateY: [24, 0],
                duration: 800,
                easing: 'easeOutExpo'
            });
        }
    });
</script>
</body>
</html>
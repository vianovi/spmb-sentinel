<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPMB Sentinel Boarding School</title>

    <!-- TAILWIND CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- ALPINE JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#0f172a',
                        gold: '#d4a017',
                        goldhover: '#b5850b',
                        light: '#f1f5f9',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    boxShadow: {
                        'soft': '0 10px 40px -10px rgba(0,0,0,0.08)',
                        'glow': '0 0 20px rgba(212, 160, 23, 0.3)',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }

        /* Pattern Dot untuk background section Visi Misi */
        .bg-dot-pattern {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>
<body class="font-sans text-navy bg-light antialiased" x-data="{ mobileMenu: false, videoModal: false }">

    <!-- ========================================= -->
    <!-- 1. TOP BAR INFO (KONTAK ATAS)             -->
    <!-- ========================================= -->
    <div class="bg-navy text-gray-300 text-sm py-3 border-b border-gray-800 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <span class="flex items-center cursor-default">
                    <i class="fa-solid fa-phone mr-2 text-gold text-base"></i>
                    +62 812-3456-7890
                </span>
                <span class="text-gray-600">|</span>
                <a href="mailto:psb@sentinel.sch.id" class="flex items-center hover:text-white transition">
                    <i class="fa-solid fa-envelope mr-2 text-gold text-base"></i>
                    psb@sentinel.sch.id
                </a>
            </div>
            <div class="flex items-center gap-5">
                <span class="font-medium text-gray-400">Ikuti Update:</span>
                <a href="#" class="hover:text-gold transition transform hover:-translate-y-0.5"><i class="fa-brands fa-tiktok"></i></a>
                <a href="#" class="hover:text-gold transition transform hover:-translate-y-0.5"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="hover:text-gold transition transform hover:-translate-y-0.5"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- ========================================= -->
    <!-- 2. NAVBAR (MENU UTAMA)                    -->
    <!-- ========================================= -->
    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-gray-100 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-navy rounded-lg flex items-center justify-center text-gold shadow-lg">
                        <i class="fa-solid fa-mosque text-xl"></i>
                    </div>
                    <div class="leading-tight">
                        <h1 class="font-bold text-lg md:text-xl tracking-wide text-navy font-serif uppercase">Sentinel</h1>
                        <p class="text-[9px] md:text-[10px] text-gray-500 font-bold tracking-[0.2em] uppercase">Boarding School</p>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-gray-500">
                    <a href="#home" class="hover:text-navy transition">Beranda</a>
                    <a href="#visi" class="hover:text-navy transition">Profil</a>
                    <a href="#program" class="hover:text-navy transition">Fasilitas</a>
                    <a href="#alur" class="hover:text-navy transition">Alur Daftar</a>
                    <a href="#kontak" class="hover:text-navy transition">Kontak</a>
                </div>

                <!-- Desktop Actions -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 rounded-full bg-navy text-white text-sm font-bold shadow-lg shadow-navy/20 hover:scale-105 transition transform">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-navy px-4">Masuk</a>
                        <a href="{{ route('pendaftaran.cek') }}" class="px-6 py-2.5 rounded-full bg-gold text-white text-sm font-bold shadow-glow hover:bg-goldhover hover:-translate-y-0.5 transition flex items-center gap-2">
                            <i class="fa-solid fa-file-pen"></i> Form Awal
                        </a>
                    @endauth
                </div>

                <!-- Mobile Burger -->
                <div class="flex items-center gap-4 md:hidden">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center text-xs"><i class="fa-solid fa-user"></i></a>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 text-xs font-bold text-navy bg-gray-100 px-3 py-1.5 rounded-full"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                    @endauth
                    <button @click="mobileMenu = !mobileMenu" class="text-navy hover:text-gold transition"><i class="fa-solid fa-bars text-2xl"></i></button>
                </div>
            </div>
        </div>
        <!-- Mobile Dropdown -->
        <div x-show="mobileMenu" x-collapse class="md:hidden bg-white border-t border-gray-100">
            <div class="p-4 space-y-2">
                <a href="#home" class="block py-2 font-semibold text-gray-700">Beranda</a>
                <a href="#alur" class="block py-2 font-semibold text-gray-700">Alur Pendaftaran</a>
                <a href="#program" class="block py-2 font-semibold text-gray-700">Fasilitas</a>
                <a href="{{ route('pendaftaran.cek') }}" class="block w-full text-center py-3 mt-4 bg-navy text-white rounded-lg font-bold shadow-lg">Isi Formulir Pendaftaran</a>
            </div>
        </div>
    </nav>

    <!-- ========================================= -->
    <!-- 3. HERO SECTION (BANNER UTAMA)            -->
    <!-- ========================================= -->
    <header id="home" class="relative min-h-[90vh] flex items-center pt-10">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/header.jpg') }}" alt="Background" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-white via-white/85 to-transparent lg:to-white/30"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <!-- Content Kanan -->
                <div class="space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-navy/5 text-navy text-xs font-bold uppercase tracking-wider">
                        <span class="fa-solid fa-circle text-[8px] text-green-500 mr-2"></span>
                        Penerimaan Santri Baru {{ date('Y') }}/{{ date('Y')+1 }}
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-serif font-bold text-navy leading-tight">
                        Membangun Generasi <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-navy to-gold">Cerdas & Beradab</span>
                    </h1>

                    <p class="text-gray-600 text-lg leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Sentinel Boarding School memadukan kurikulum Islam klasik dengan teknologi modern.
                        Tempat di mana hafalan Al-Qur'an berjalan seiring dengan penguasaan coding dan sains.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                        <a href="{{ route('pendaftaran.cek') }}" class="w-full sm:w-auto px-8 py-3.5 bg-navy text-white rounded-xl font-semibold shadow-xl hover:-translate-y-1 transition duration-300 text-center">
                            Daftar Gelombang Ini
                        </a>
                        <!-- Trigger Video Modal -->
                        <button @click="videoModal = true" class="w-full sm:w-auto px-6 py-3.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition flex items-center justify-center gap-2 group">
                            <i class="fa-solid fa-play-circle text-gold text-xl group-hover:scale-110 transition"></i>
                            Tonton Profil
                        </button>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="pt-6 flex items-center gap-6 justify-center lg:justify-start grayscale opacity-60">
                        <!-- Pura-pura logo partner/akreditasi -->
                        <div class="flex items-center gap-2 font-bold"><i class="fa-solid fa-medal"></i> Akreditasi A</div>
                        <div class="flex items-center gap-2 font-bold"><i class="fa-solid fa-check-shield"></i> Kemdikbud</div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- RIGHT CONTENT: INFO CARD BENTO + SCHEDULE MODAL           -->
                <!-- ========================================================= -->
                <div class="lg:pl-12" x-data="{ scheduleModal: false }">

                    <!-- 1. MAIN CARD WRAPPER -->
                    <!-- max-w-sm: Membatasi lebar agar compact (tidak melar) -->
                    <div class="relative w-full max-w-sm ml-auto mr-0 md:mr-8 group mt-8 lg:mt-0">

                        <!-- A. GLOW EFFECT BACKGROUND -->
                        <!-- Memberikan efek bias cahaya di belakang kartu -->
                        <div class="absolute -inset-2 bg-gradient-to-r from-gold/40 to-blue-600/40 rounded-3xl blur-2xl opacity-40 group-hover:opacity-70 transition duration-500"></div>

                        <!-- B. GLASSMORPHISM CARD -->
                        <div class="relative bg-white/70 backdrop-blur-x15 border border-white/50 p-6 rounded-3xl shadow-2xl overflow-hidden">

                            <!-- Hiasan: Efek pantulan kaca (Glossy) -->
                            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/60 to-transparent pointer-events-none"></div>

                            <!-- LOGIC: CEK APAKAH ADA GELOMBANG AKTIF -->
                            @if($activeWave)

                                <!-- HEADER CARD -->
                                <div class="relative z-10 flex justify-between items-start mb-5 pb-4 border-b border-navy/10">
                                    <div>
                                        <!-- Badge Tahun Pelajaran -->
                                        <div class="inline-flex items-center gap-1.5 bg-navy/10 backdrop-blur-sm px-2.5 py-1 rounded-full mb-2 border border-white/20">
                                            <i class="fa-solid fa-graduation-cap text-navy text-[10px]"></i>
                                            <span class="text-[10px] font-bold text-navy tracking-wide">TP {{ $activeWave->academic_year }}</span>
                                        </div>
                                        <!-- Nama Gelombang -->
                                        <h2 class="text-2xl font-serif font-bold text-navy leading-tight">{{ $activeWave->batch_name }}</h2>
                                    </div>

                                    <!-- Badge Status: BUKA (Dengan Animasi Ping) -->
                                    <div class="bg-green-500/10 border border-green-500/20 text-green-800 font-bold px-3 py-1 rounded-full text-[10px] flex items-center gap-1.5 shadow-sm backdrop-blur-md">
                                        <span class="relative flex h-1.5 w-1.5">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-500 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-green-600"></span>
                                        </span>
                                        BUKA
                                    </div>
                                </div>

                                <!-- GRID INFO: BIAYA & KUOTA -->
                                <div class="relative z-10 grid grid-cols-2 gap-3 mb-5">
                                    <!-- Info Biaya -->
                                    <div class="bg-white/50 p-3 rounded-xl border border-white/60 shadow-sm hover:bg-white/80 transition">
                                        <span class="text-[10px] text-gray-500 font-medium block mb-1">Biaya Masuk</span>
                                        <span class="text-sm font-bold text-navy block tracking-tight">
                                            {{ $activeWave->formatted_price ?? 'Rp -' }}
                                        </span>
                                    </div>
                                    <!-- Info Kuota -->
                                    <div class="bg-white/50 p-3 rounded-xl border border-white/60 shadow-sm hover:bg-white/80 transition">
                                        <span class="text-[10px] text-gray-500 font-medium block mb-1">Sisa Kuota</span>
                                        <span class="text-sm font-bold text-navy block">
                                            {{ number_format($activeWave->quota - $activeWave->quota_filled, 0, ',', '.') }}
                                            <span class="text-[10px] font-normal text-gray-500">Kursi</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- ACTION BUTTONS -->
                                <div class="relative z-10 space-y-2">
                                    <!-- Tombol Utama: Daftar -->
                                    <a href="{{ route('pendaftaran.cek') }}" class="flex items-center justify-center gap-2 w-full py-3 bg-gradient-to-r from-gold to-yellow-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-gold/30 hover:shadow-gold/50 hover:-translate-y-0.5 transition transform duration-200">
                                        <span>Isi Formulir</span>
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>

                                    <!-- Tombol Secondary: Trigger Modal -->
                                    <button @click="scheduleModal = true" class="w-full py-2.5 text-xs font-semibold text-navy/70 hover:text-navy hover:bg-white/50 rounded-xl transition border border-transparent hover:border-white/50">
                                        <i class="fa-regular fa-calendar-alt mr-1.5"></i> Lihat Rincian Jadwal
                                    </button>
                                </div>

                                <p class="relative z-10 text-center text-[10px] text-gray-400 mt-3">*Segera kunci posisi Anda sebelum kuota penuh.</p>

                            @else
                                <!-- STATE: JIKA TIDAK ADA GELOMBANG AKTIF (TUTUP) -->
                                 <div class="relative z-10 text-center py-8">
                                     <div class="w-12 h-12 bg-white/50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400 text-lg shadow-sm border border-white/50">
                                        <i class="fa-solid fa-store-slash"></i>
                                     </div>
                                     <h3 class="text-lg font-bold text-navy">Pendaftaran Ditutup</h3>
                                     <p class="text-gray-500 text-xs mt-1">Nantikan informasi gelombang berikutnya.</p>
                                 </div>
                            @endif
                        </div>
                    </div>


                    <!-- 2. MODAL POPUP (TIMELINE JADWAL) -->
                    <!-- Menggunakan x-teleport agar modal render di luar parent (di body) -->
                    <template x-teleport="body">
                        <div x-show="scheduleModal" style="display: none;"
                            class="fixed inset-0 z-[999] flex items-center justify-center px-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0">

                            <!-- Backdrop Blur (Klik untuk tutup) -->
                            <div class="absolute inset-0 bg-navy/60 backdrop-blur-sm" @click="scheduleModal = false"></div>

                            <!-- Modal Content -->
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                                <!-- Header Modal -->
                                <div class="bg-navy p-5 flex justify-between items-center">
                                    <div>
                                        <h3 class="text-white font-bold text-lg">Timeline Pendaftaran</h3>
                                        <p class="text-gray-400 text-xs mt-1">
                                            Detail kegiatan {{ $activeWave->batch_name ?? '' }}
                                        </p>
                                    </div>
                                    <button @click="scheduleModal = false" class="w-8 h-8 rounded-full bg-white/10 text-white hover:bg-white/20 flex items-center justify-center transition">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>

                                <!-- Body Modal: Scrollable Timeline -->
                                <div class="p-6 max-h-[70vh] overflow-y-auto">
                                    @if($activeWave)
                                    <div class="relative pl-4 border-l-2 border-gray-100 space-y-8">

                                        <!-- ITEM 1: PERIODE PENDAFTARAN -->
                                        <div class="relative">
                                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-gold border-4 border-white shadow-sm"></div>
                                            <h4 class="text-sm font-bold text-navy">Periode Pendaftaran</h4>
                                            <p class="text-xs text-gray-500 mt-1 mb-2">Pengisian formulir online & pembayaran.</p>
                                            <div class="inline-block bg-green-50 text-green-700 text-xs px-2 py-1 rounded font-medium border border-green-100">
                                                {{ $activeWave->start_date->translatedFormat('d M Y') }}
                                                s/d
                                                {{ $activeWave->end_date->translatedFormat('d M Y') }}
                                            </div>
                                        </div>

                                        <!-- ITEM 2: TES SELEKSI (DINAMIS) -->
                                        <div class="relative">
                                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-gray-300 border-4 border-white"></div>
                                            <h4 class="text-sm font-bold text-navy">Tes Seleksi Masuk</h4>
                                            <p class="text-xs text-gray-500 mt-1 mb-2">Tes akademik & wawancara orang tua.</p>
                                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                                <i class="fa-regular fa-calendar"></i>
                                                <span>
                                                    @if($activeWave->exam_date)
                                                        {{ $activeWave->exam_date->translatedFormat('l, d F Y') }}
                                                        <span class="text-gray-400 ml-1">({{ $activeWave->exam_date->format('H:i') }} WIB)</span>
                                                    @else
                                                        Menunggu Jadwal
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <!-- ITEM 3: PENGUMUMAN (DINAMIS) -->
                                        <div class="relative">
                                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-gray-300 border-4 border-white"></div>
                                            <h4 class="text-sm font-bold text-navy">Pengumuman Hasil</h4>
                                            <p class="text-xs text-gray-500 mt-1 mb-2">Diumumkan via Website & WhatsApp.</p>
                                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                                <i class="fa-solid fa-bullhorn"></i>
                                                <span class="font-medium text-navy">
                                                    @if($activeWave->announcement_date)
                                                        {{ $activeWave->announcement_date->translatedFormat('d F Y') }}
                                                        <span class="text-gray-400 ml-1">({{ $activeWave->announcement_date->format('H:i') }} WIB)</span>
                                                    @else
                                                        Akan Dikonfirmasi
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <!-- ITEM 4: DAFTAR ULANG (DINAMIS) -->
                                        <div class="relative">
                                            <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full bg-gray-300 border-4 border-white"></div>
                                            <h4 class="text-sm font-bold text-navy">Daftar Ulang</h4>
                                            <p class="text-xs text-gray-500 mt-1 mb-2">Pelunasan biaya masuk & pengukuran seragam.</p>

                                            <div class="text-xs text-navy font-medium flex items-center gap-2">
                                                <i class="fa-solid fa-clipboard-check text-gold"></i>
                                                @if($activeWave->reregistration_date)
                                                   Mulai {{ $activeWave->reregistration_date->translatedFormat('d F Y') }}
                                                @else
                                                   Jadwal Menyusul
                                                @endif
                                            </div>

                                            <div class="text-[10px] text-red-500 mt-1 italic">
                                                *Wajib lapor diri untuk mengunci kuota.
                                            </div>
                                        </div>

                                    </div>
                                    @else
                                        <!-- Fallback jika data tidak terload -->
                                        <p class="text-center text-gray-500">Data jadwal tidak tersedia.</p>
                                    @endif
                                </div>

                                <!-- Footer Modal -->
                                <div class="bg-gray-50 p-4 border-t border-gray-100 text-center">
                                    <button @click="scheduleModal = false" class="text-xs text-gray-500 hover:text-navy underline">Tutup Jendela</button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

            </div>
        </div>
    </header>

    <!-- ============================================== -->
    <!-- 4. PROFILE & VIDEO (Desain: Minimalist Modern) -->
    <!-- ============================================== -->


    <section class="py-24 bg-navy text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center lg:text-left">
            <div class="flex flex-col lg:flex-row gap-16 items-center">
                <div class="lg:w-1/2 space-y-6">
                    <span class="text-gold font-bold tracking-widest text-sm">KENAPA SENTINEL?</span>
                    <h2 class="text-4xl lg:text-5xl font-serif font-bold leading-tight">Mendidik dengan Hati, <br>Menguasai Teknologi.</h2>
                    <p class="text-gray-400 leading-relaxed text-lg">
                        Sistem "Modern Boarding School" kami memastikan setiap santri mendapatkan pengawasan 24 jam yang humanis. Tidak hanya hafal Qur'an, santri kami didorong untuk memiliki nalar kritis melalui kurikulum sains & teknologi.
                    </p>

                    <!-- Poin Keunggulan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10 hover:bg-white/10 transition">
                            <i class="fa-solid fa-book-quran text-gold text-2xl mb-3"></i>
                            <h4 class="font-bold">Tahfidz Sanad</h4>
                            <p class="text-xs text-gray-400 mt-1">Hafalan bersanad ke Rasulullah SAW.</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10 hover:bg-white/10 transition">
                            <i class="fa-solid fa-laptop-code text-gold text-2xl mb-3"></i>
                            <h4 class="font-bold">IT Expert</h4>
                            <p class="text-xs text-gray-400 mt-1">Cyber Security & Web Development.</p>
                        </div>
                    </div>
                </div>

                <!-- Video Frame -->
                <div class="lg:w-1/2 relative">
                    <div class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white/10 relative group cursor-pointer" @click="videoModal = true">
                        <!-- IMAGE THUMBNAIL (Wajib Ada) -->
                        <img src="{{ asset('assets/test-thumbnail.jpg') }}" class="w-full object-cover transform group-hover:scale-105 transition duration-700" style="height: 400px;" alt="Video">
                        <div class="absolute inset-0 bg-navy/40 group-hover:bg-navy/20 transition duration-500 flex items-center justify-center">
                             <div class="w-20 h-20 rounded-full bg-gold text-white flex items-center justify-center shadow-glow animate-pulse group-hover:animate-none group-hover:scale-110 transition">
                                 <i class="fa-solid fa-play text-3xl ml-1"></i>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- 5. NEW: VISI & MISI (Compact Style)       -->
    <!-- ========================================= -->
    <section id="visi" class="py-20 bg-dot-pattern relative border-y border-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-gold font-bold text-sm tracking-widest uppercase">Komitmen Kami</span>
                <h2 class="text-3xl lg:text-4xl font-serif font-bold text-navy mt-1">Visi & Misi</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-8 items-stretch">
                <!-- VISI -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 hover:shadow-lg transition duration-300">
                    <!-- Image Card Kecil Compact -->
                    <div class="h-48 rounded-xl overflow-hidden bg-gray-200 relative">
                        <img src="{{ asset('assets/about-1.jpg') }}" alt="Visi Sentinel" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-navy/60 flex items-center justify-center">
                             <h3 class="text-2xl font-serif font-bold text-white tracking-widest uppercase">Visi</h3>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-gray-600 text-lg leading-relaxed">
                            "Menjadi Lembaga Pendidikan Islam Modern Terdepan yang Mencetak Generasi Qur'ani, Berwawasan Global, dan Menguasai Teknologi."
                        </p>
                    </div>
                </div>

                <!-- MISI -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 hover:shadow-lg transition duration-300">
                     <!-- Image Card Kecil Compact -->
                     <div class="h-48 rounded-xl overflow-hidden bg-gray-200 relative">
                        <img src="{{ asset('assets/room-2.jpg') }}" alt="Misi Sentinel" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-navy/60 flex items-center justify-center">
                             <h3 class="text-2xl font-serif font-bold text-white tracking-widest uppercase">Misi</h3>
                        </div>
                    </div>
                    <div class="p-6 text-left">
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-start gap-3"><i class="fa-solid fa-check text-gold mt-1"></i> Menyelenggarakan pendidikan tahfidz bersanad yang berkualitas.</li>
                            <li class="flex items-start gap-3"><i class="fa-solid fa-check text-gold mt-1"></i> Mengintegrasikan kurikulum nasional dengan kurikulum pesantren.</li>
                            <li class="flex items-start gap-3"><i class="fa-solid fa-check text-gold mt-1"></i> Mengembangkan potensi santri dalam bidang Cyber Security & IT.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =============================================== -->
    <!-- 6. NEW: HIGHLIGHT SECTION (ADAPTASI TEMPLATE HOTEL) -->
    <!-- =============================================== -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">

                <!-- KOLOM KIRI (Judul & Ikon) - 5 Col -->
                <div class="md:col-span-5 text-center md:text-left">
                    <div class="w-16 h-16 mx-auto md:mx-0 rounded-full bg-light border border-gray-200 text-navy flex items-center justify-center text-3xl mb-6">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <h2 class="text-3xl font-serif font-bold text-navy mb-4">Pembinaan <br>Terarah & Terukur</h2>
                    <p class="text-gray-500 mb-8 leading-relaxed">
                        Program pembinaan santri berbasis disiplin, adab, dan prestasi.
                        Kami menciptakan lingkungan yang asri dan mendukung tumbuh kembang santri secara holistik.
                    </p>
                    <a href="#alur" class="inline-block border-b-2 border-gold pb-1 font-bold text-navy hover:text-gold transition">
                        Pelajari Proses Seleksi <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </a>
                </div>

                <!-- KOLOM TENGAH (Foto Tall/Tinggi) - 3 Col -->
                <div class="md:col-span-3">
                     <!-- Image agak memanjang vertikal -->
                     <img src="{{ asset('assets/menu-7.jpg') }}" alt="Pembinaan Santri" class="w-full h-[400px] object-cover rounded-2xl shadow-xl transform md:translate-y-8 hover:translate-y-4 transition duration-500">
                </div>

                <!-- KOLOM KANAN (Card Dark + Foto) - 4 Col -->
                <div class="md:col-span-4 space-y-6">
                    <!-- Dark Box "Seleksi Jelas" -->
                    <div class="bg-navy p-8 rounded-2xl text-white text-center shadow-2xl relative overflow-hidden group">
                        <!-- Ornamen icon background -->
                        <i class="fa-solid fa-list-check absolute top-4 right-4 text-white opacity-5 text-6xl group-hover:opacity-10 transition"></i>

                        <div class="w-12 h-12 mx-auto rounded-lg bg-gold text-navy flex items-center justify-center text-xl mb-4 font-bold shadow-lg">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                        <h3 class="text-xl font-bold font-serif mb-2">Proses Seleksi <br>Jelas & Transparan</h3>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Alur seleksi, kelengkapan administrasi, dan pengumuman dibuat transparan. Tidak ada biaya tersembunyi.
                        </p>
                    </div>

                    <!-- Small Image bawahnya -->
                    <img src="{{ asset('assets/room-1.jpg') }}" alt="Fasilitas Asrama" class="w-full h-48 object-cover rounded-2xl shadow-lg border-4 border-white">
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- 7. ALUR PENDAFTARAN (REVISED STEPS)       -->
    <!-- ========================================= -->
    <section id="alur" class="py-24 bg-light relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-gold font-bold text-sm tracking-widest uppercase">Langkah Mudah</span>
                <h2 class="text-3xl lg:text-4xl font-serif font-bold text-navy mt-2">7 Tahap Pendaftaran</h2>
            </div>

            <!-- 7 STEPS FLOW LAYOUT -->
            <div class="flex flex-wrap justify-center gap-6">

                <!-- STEP 1: FORM SINGKAT -->
                <div class="w-full md:w-[30%] lg:w-[22%] bg-white rounded-xl p-6 text-center hover:shadow-xl transition duration-300 group cursor-default border border-transparent hover:border-gold">
                    <div class="w-12 h-12 mx-auto rounded-full bg-light border-2 border-navy text-navy font-bold text-lg flex items-center justify-center mb-4 shadow-sm group-hover:bg-navy group-hover:text-gold transition">1</div>
                    <h3 class="font-bold text-navy text-lg mb-2">Form Singkat</h3>
                    <p class="text-xs text-gray-500">Isi data diri awal via tombol "Form Awal" untuk registrasi.</p>
                </div>

                <!-- STEP 2: LOGIN AKUN -->
                <div class="w-full md:w-[30%] lg:w-[22%] bg-white rounded-xl p-6 text-center hover:shadow-xl transition duration-300 group cursor-default border border-transparent hover:border-gold">
                    <div class="w-12 h-12 mx-auto rounded-full bg-light border-2 border-navy text-navy font-bold text-lg flex items-center justify-center mb-4 shadow-sm group-hover:bg-navy group-hover:text-gold transition">2</div>
                    <h3 class="font-bold text-navy text-lg mb-2">Login Akun</h3>
                    <p class="text-xs text-gray-500">Masuk ke sistem menggunakan akun yang telah dibuat.</p>
                </div>

                <!-- STEP 3: LENGKAPI DATA -->
                <div class="w-full md:w-[30%] lg:w-[22%] bg-white rounded-xl p-6 text-center hover:shadow-xl transition duration-300 group cursor-default border border-transparent hover:border-gold">
                    <div class="w-12 h-12 mx-auto rounded-full bg-light border-2 border-navy text-navy font-bold text-lg flex items-center justify-center mb-4 shadow-sm group-hover:bg-navy group-hover:text-gold transition">3</div>
                    <h3 class="font-bold text-navy text-lg mb-2">Lengkapi Data</h3>
                    <p class="text-xs text-gray-500">Input data kesehatan & wali santri di Dashboard.</p>
                </div>

                <!-- STEP 4: VERIFIKASI & BAYAR -->
                <div class="w-full md:w-[30%] lg:w-[22%] bg-white rounded-xl p-6 text-center hover:shadow-xl transition duration-300 group cursor-default border border-transparent hover:border-gold">
                    <div class="w-12 h-12 mx-auto rounded-full bg-light border-2 border-navy text-navy font-bold text-lg flex items-center justify-center mb-4 shadow-sm group-hover:bg-navy group-hover:text-gold transition">4</div>
                    <h3 class="font-bold text-navy text-lg mb-2">Verifikasi & Bayar</h3>
                    <p class="text-xs text-gray-500">Lakukan pembayaran administrasi. Admin akan memverifikasi.</p>
                </div>

                 <!-- STEP 5: INFO UJIAN -->
                 <div class="w-full md:w-[30%] lg:w-[22%] bg-white rounded-xl p-6 text-center hover:shadow-xl transition duration-300 group cursor-default border border-transparent hover:border-gold">
                    <div class="w-12 h-12 mx-auto rounded-full bg-light border-2 border-navy text-navy font-bold text-lg flex items-center justify-center mb-4 shadow-sm group-hover:bg-navy group-hover:text-gold transition">5</div>
                    <h3 class="font-bold text-navy text-lg mb-2">Tunggu Jadwal</h3>
                    <p class="text-xs text-gray-500">Tunggu informasi jadwal dan kartu ujian seleksi masuk.</p>
                </div>

                 <!-- STEP 6: UJIAN -->
                 <div class="w-full md:w-[30%] lg:w-[22%] bg-white rounded-xl p-6 text-center hover:shadow-xl transition duration-300 group cursor-default border border-transparent hover:border-gold">
                    <div class="w-12 h-12 mx-auto rounded-full bg-light border-2 border-navy text-navy font-bold text-lg flex items-center justify-center mb-4 shadow-sm group-hover:bg-navy group-hover:text-gold transition">6</div>
                    <h3 class="font-bold text-navy text-lg mb-2">Ujian Seleksi</h3>
                    <p class="text-xs text-gray-500">Ikuti tes Akademik & Baca Qur'an sesuai jadwal.</p>
                </div>

                 <!-- STEP 7: PENGUMUMAN -->
                 <div class="w-full md:w-[30%] lg:w-[22%] bg-white rounded-xl p-6 text-center hover:shadow-xl transition duration-300 group cursor-default border border-transparent hover:border-gold">
                    <div class="w-12 h-12 mx-auto rounded-full bg-light border-2 border-navy text-navy font-bold text-lg flex items-center justify-center mb-4 shadow-sm group-hover:bg-navy group-hover:text-gold transition">7</div>
                    <h3 class="font-bold text-navy text-lg mb-2">Pengumuman</h3>
                    <p class="text-xs text-gray-500">Hasil kelulusan keluar. Lanjut ke proses Daftar Ulang.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================= -->
    <!-- 8. FOOTER SECTION (ROUNDED TOP & UPDATE)  -->
    <!-- ========================================= -->
    <footer id="kontak" class="bg-navy text-white pt-24 pb-10 mt-12 relative rounded-t-[3rem]"> <!-- ADDED rounded-t-[3rem] -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 border-b border-gray-700 pb-16">

                <!-- KOLOM 1: Brand (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-mosque text-4xl text-gold"></i>
                        <div>
                            <h3 class="font-serif font-bold text-2xl uppercase tracking-wide">Sentinel</h3>
                            <p class="text-xs tracking-[0.3em] opacity-60">ISLAMIC BOARDING SCHOOL</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed pr-6">
                        Menyelenggarakan pendidikan Islam terpadu yang adaptif terhadap perkembangan zaman tanpa meninggalkan nilai Salafus Shalih.
                    </p>

                    <!-- DOWNLOAD BROSUR BUTTON -->
                    <div class="pt-4">
                        <!-- Href nya panggil route yang tadi dibuat -->
                        <a href="{{ route('download.brosur') }}" class="inline-flex items-center gap-3 bg-gray-800 border border-gray-600 px-6 py-3 rounded-xl hover:border-gold hover:text-gold transition group">
                                <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center group-hover:bg-gold group-hover:text-navy transition">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] text-gray-400">Informasi Lengkap</p>
                                    <p class="font-bold text-xs">Download Brosur PDF</p>
                                </div>
                        </a>
                    </div>
                </div>

                <!-- KOLOM 2: Navigasi (2 cols) -->
                <div class="lg:col-span-2 space-y-6">
                    <h4 class="font-bold text-lg text-white font-serif">Akses Cepat</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="#home" class="hover:text-gold transition">Beranda Utama</a></li>
                        <li><a href="#about" class="hover:text-gold transition">Profil Pesantren</a></li>
                        <li><a href="#alur" class="hover:text-gold transition">Info Pendaftaran</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-gold transition">Login Santri</a></li>
                        <li><a href="{{ route('pendaftaran.cek') }}" class="hover:text-gold transition">Buat Akun Baru</a></li>
                    </ul>
                </div>

                 <!-- KOLOM 3: Kontak Sekretariat (3 cols) -->
                 <div class="lg:col-span-3 space-y-6">
                    <h4 class="font-bold text-lg text-white font-serif">Sekretariat</h4>
                    <div class="space-y-4 text-sm text-gray-400">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1 text-gold"></i>
                            <span class="leading-relaxed">Jl. Raya Pendidikan No. 404, Kota Teknologi, Jawa Tengah.</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fa-regular fa-envelope text-gold"></i>
                            <span>psb@sentinel.sch.id</span>
                        </div>
                        <!-- BUTTON WA CHAT SEKRETARIAT (NEW TEXT) -->
                        <div class="pt-2">
                             <a href="https://wa.me/628123456789" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-xs font-bold inline-flex items-center gap-2 transition shadow-lg shadow-green-900/20">
                                 <i class="fa-brands fa-whatsapp text-lg"></i>
                                 Chat Sekretariat
                             </a>
                        </div>
                    </div>
                </div>

                 <!-- KOLOM 4: PETA (3 cols) -->
                 <div class="lg:col-span-3 space-y-6">
                    <h4 class="font-bold text-lg text-white font-serif">Lokasi Kami</h4>
                    <!-- Google Maps Iframe (Rounded & Grayscale effect opitonal) -->
                    <div class="w-full h-40 rounded-xl overflow-hidden shadow-lg border border-gray-600 bg-gray-800 relative">
                         <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.298059876246!2d110.42232937587635!3d-6.974108868288599!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708c90327f2f11%3A0xc644c138c227318!2sSemarang!5e0!3m2!1sen!2sid!4v1709282384722!5m2!1sen!2sid"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            class="grayscale hover:grayscale-0 transition duration-500">
                        </iframe>
                    </div>
                    <a href="https://maps.google.com" target="_blank" class="text-xs text-gold hover:underline flex items-center gap-1">
                        Buka di Google Maps <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
            </div>

            <!-- COPYRIGHT BOTTOM -->
            <div class="pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} Sentinel Boarding School System. All rights reserved.</p>
                <div class="flex gap-4 mt-2 md:mt-0">
                    <a href="#" class="hover:text-white">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- MODAL VIDEO PLAYER -->
    <div x-show="videoModal" style="display: none;"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-navy/90 backdrop-blur-sm p-4">

    <!-- Modal Container -->
    <div @click.away="videoModal = false" class="relative w-full max-w-5xl bg-black rounded-2xl overflow-hidden shadow-2xl border border-white/10">

        <!-- Close Button -->
        <button @click="videoModal = false" class="absolute top-4 right-4 text-white hover:text-red-500 z-10 bg-black/50 hover:bg-black rounded-full w-10 h-10 flex items-center justify-center transition">
            <i class="fa-solid fa-times text-xl"></i>
        </button>

        <div class="aspect-video w-full bg-black">
            <template x-if="videoModal">
                <video class="w-full h-full object-contain" controls autoplay>
                    <source src="{{ asset('assets/test-video.mp4') }}" type="video/mp4">
                    Browser Anda tidak mendukung tag video.
                </video>
            </template>
        </div>
    </div>
</div>

</body>
</html>
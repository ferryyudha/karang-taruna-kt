<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Karang Taruna')</title>
    <meta name="description" content="@yield('description', 'Karang Taruna - Organisasi Kepemudaan yang Aktif dan Berdedikasi')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">

    <style>
        :root {
            --primary: #4154F1;
            --primary-light: #60A5FA;
            --secondary: #7C3AED;
            --accent: #F59E0B;
            --dark: #0F172A;
            --light: #F8FAFC;
        }
        * { font-family: 'Inter', sans-serif; }
        h1,h2,h3,h4,h5,h6,.brand { font-family: 'Poppins', sans-serif; }
        body { background: #FFFFFF; color: #334155; overflow-x: hidden; }

        /* Navbar */
        .navbar-custom {
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid #F1F5F9;
            padding: 16px 0;
            transition: all 0.3s ease;
        }
        .navbar-custom.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
        .brand-logo {
            width: 32px; height: 32px;
            background: #4154F1;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-name { font-family: 'Poppins', sans-serif; font-weight: 700; font-size: 1.25rem;
            color: #0F172A; line-height: 1.1; }
        .navbar-custom .nav-link {
            color: #475569 !important; font-weight: 500; padding: 6px 16px !important;
            transition: all 0.2s; font-size: 0.92rem;
            position: relative;
        }
        .navbar-custom .nav-link:hover { color: #4154F1 !important; }
        .navbar-custom .nav-link.active {
            color: #4154F1 !important;
            font-weight: 600;
        }
        .navbar-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 16px;
            right: 16px;
            height: 2px;
            background: #4154F1;
            border-radius: 2px;
        }
        .btn-login-nav {
            background: #4154F1;
            color: white !important; border-radius: 10px;
            padding: 9px 24px !important; font-weight: 600; font-size: 0.88rem;
            transition: all 0.25s;
        }
        .btn-login-nav:hover { background: #3143d9; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(65,84,241,0.25); }

        /* Section spacing */
        .section { padding: 80px 0; }
        .section-sm { padding: 50px 0; }
        .section-title { font-size: 1.85rem; font-weight: 700; color: #0F172A; margin-bottom: 8px; }
        .section-desc { color: #64748B; font-size: 0.95rem; }
        .divider-gradient {
            width: 45px; height: 3px;
            background: #4154F1;
            border-radius: 2px; margin: 10px auto 16px;
        }

        /* Cards */
        .card-modern {
            border: 1px solid #E2E8F0; border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .card-modern:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.06); }
        .badge-kategori {
            background: #EFF6FF;
            color: #2563EB; padding: 5px 12px; border-radius: 20px;
            font-size: 0.74rem; font-weight: 600;
        }
        .badge-status-upcoming  { background: #EFF6FF; color: #2563EB; }
        .badge-status-ongoing   { background: #FFFBEB; color: #D97706; }
        .badge-status-completed { background: #F0FDF4; color: #16A34A; }
        .badge-status { padding: 5px 12px; border-radius: 20px; font-size: 0.74rem; font-weight: 600; }

        /* Footer */
        .footer { background: #F8FAFC; color: #475569; border-top: 1px solid #F1F5F9; }
        .footer h5 { color: #0F172A; font-weight: 700; font-size: 0.95rem; margin-bottom: 18px; }
        .footer a { color: #64748B; text-decoration: none; transition: color 0.3s; font-size: 0.88rem; }
        .footer a:hover { color: #4154F1; }
        .footer-bottom { border-top: 1px solid #E2E8F0; padding: 20px 0; margin-top: 40px; }
        .social-btn {
            width: 36px; height: 36px; background: #E2E8F0;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: #475569; transition: all 0.3s; text-decoration: none;
        }
        .social-btn:hover { background: #4154F1; color: white; }

        /* Back to top */
        .back-to-top {
            position: fixed; bottom: 28px; right: 28px;
            width: 44px; height: 44px;
            background: #4154F1;
            color: white; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; opacity: 0; pointer-events: none;
            transition: all 0.3s; z-index: 999;
            box-shadow: 0 4px 15px rgba(65,84,241,0.3);
        }
        .back-to-top.show { opacity: 1; pointer-events: all; }
        .back-to-top:hover { transform: translateY(-3px); color: white; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <div class="brand-logo"><i class="bi bi-star-fill text-white" style="font-size:0.85rem;"></i></div>
                <span class="brand-name">Karang Taruna</span>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto gap-1">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}"><i class="bi bi-house me-1"></i>Beranda</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('pengumuman*') ? 'active' : '' }}" href="{{ route('public.pengumuman') }}"><i class="bi bi-megaphone me-1"></i>Pengumuman</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('kegiatan*') || request()->is('kalender*') ? 'active' : '' }}" href="{{ route('public.kegiatan') }}"><i class="bi bi-calendar-event me-1"></i>Kegiatan</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('lomba*') ? 'active' : '' }}" href="{{ route('public.lomba') }}"><i class="bi bi-trophy me-1"></i>Lomba</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('anggota') ? 'active' : '' }}" href="{{ route('public.anggota') }}"><i class="bi bi-people me-1"></i>Anggota</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('galeri') ? 'active' : '' }}" href="{{ route('public.galeri') }}"><i class="bi bi-images me-1"></i>Galeri</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('pengaduan*') ? 'active' : '' }}" href="{{ route('public.pengaduan') }}"><i class="bi bi-chat-square-dots me-1"></i>Pengaduan</a></li>
                </ul>
                <a href="{{ route('login') }}" class="btn-login-nav ms-2 text-decoration-none d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Login
                </a>
            </div>
        </div>
    </nav>

    <main style="min-height: 80vh;">
        @yield('content')
    </main>

    <footer class="footer pt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-logo" style="background:#4154F1;">
                            <i class="bi bi-star-fill text-white" style="font-size:0.85rem;"></i>
                        </div>
                        <span style="font-family:'Poppins',sans-serif;font-weight:700;color:#0F172A;font-size:1.25rem;">Karang Taruna</span>
                    </div>
                    <p style="font-size:0.88rem;line-height:1.8;">Organisasi kepemudaan yang aktif, kreatif, dan berdedikasi dalam membangun komunitas yang lebih baik.</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-btn"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h5>Navigasi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('public.pengumuman') }}">Pengumuman</a></li>
                        <li class="mb-2"><a href="{{ route('public.kegiatan') }}">Kegiatan & Agenda Kalender</a></li>
                        <li class="mb-2"><a href="{{ route('public.lomba') }}">Lomba</a></li>
                        <li class="mb-2"><a href="{{ route('public.anggota') }}">Anggota</a></li>
                        <li class="mb-2"><a href="{{ route('public.galeri') }}">Galeri</a></li>
                        <li class="mb-2"><a href="{{ route('public.pengaduan') }}">Pengaduan Warga</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled" style="font-size:0.88rem;">
                        <li class="mb-2"><i class="bi bi-geo-alt-fill me-2 text-primary"></i>Jl. Pemuda No. 1, RT 01/RW 02</li>
                        <li class="mb-2"><i class="bi bi-telephone-fill me-2 text-primary"></i>+62 812-3456-7890</li>
                        <li class="mb-2"><i class="bi bi-envelope-fill me-2 text-primary"></i>karangtaruna@gmail.com</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5>Tentang Kami</h5>
                    <p style="font-size:0.88rem;line-height:1.8;">Karang Taruna adalah wadah pengembangan pemuda yang berkomitmen pada kegiatan sosial, budaya, dan pemberdayaan masyarakat.</p>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6"><p class="mb-0" style="font-size:0.84rem;">&copy; {{ date('Y') }} Karang Taruna. All rights reserved.</p></div>
                    <div class="col-md-6 text-md-end"><p class="mb-0" style="font-size:0.84rem;">Dibangun dengan <i class="bi bi-heart-fill text-danger"></i> untuk kemajuan bersama</p></div>
                </div>
            </div>
        </div>
    </footer>

    <a href="#" class="back-to-top" id="backToTop"><i class="bi bi-chevron-up"></i></a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 650, easing: 'ease-out-cubic', once: true, offset: 60 });
        const nav = document.getElementById('mainNav');
        const btt = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 60) { nav.classList.add('scrolled'); btt.classList.add('show'); }
            else { nav.classList.remove('scrolled'); btt.classList.remove('show'); }
        });
        btt.addEventListener('click', e => { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });
    </script>
    @stack('scripts')
</body>
</html>

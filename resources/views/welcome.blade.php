<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPBIPER-BP - Sistem Informasi Pendataan Bidang Budidaya dan Pasca Panen Dinas Perikanan Jember</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --accent-color: #60a5fa;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
        }
        
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            overflow-x: hidden;
        }
        
        /* Navbar Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .logo-img {
            width: clamp(180px, 24vw, 230px);
            height: auto;
            object-fit: contain;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.6rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
            color: white;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            padding: 160px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,197.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero-title {
            font-size: 2.8rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.3;
            letter-spacing: 1.5px;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 2rem;
            line-height: 1.8;
        }
        
        .hero-image {
            display: block;
            width: min(78vw, 360px);
            max-width: 100%;
            height: auto;
            margin: 0 auto;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.2));
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        /* Stats Section */
        .stats-section {
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        
        .stat-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #60a5fa);
            color: white;
        }
        
        .stat-icon.green {
            background: linear-gradient(135deg, #10b981, #34d399);
            color: white;
        }
        
        .stat-icon.orange {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: white;
        }
        
        .stat-icon.red {
            background: linear-gradient(135deg, #ef4444, #f87171);
            color: white;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #64748b;
            font-weight: 500;
        }
        
        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: var(--light-color);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 4rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            height: 100%;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        
        .feature-card:hover {
            border-color: var(--secondary-color);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(59, 130, 246, 0.15);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }
        
        .feature-description {
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 0;
        }
        
        /* Footer */
        .footer {
            background: var(--dark-color);
            color: white;
            padding: 3rem 0 1.5rem;
        }
        
        .footer-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .footer-text {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }

            .hero-image {
                width: min(72vw, 260px);
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .stat-card {
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/sipbiper-logo.png') }}" alt="SIPBIPER-BP Logo" class="logo-img" onerror="this.style.display='none'">
            </a>
            
            @if (Route::has('login'))
                <div class="ms-auto">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-login">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center hero-content">
                <div class="col-lg-7 mb-5 mb-lg-0" data-aos="fade-right">
                    <h1 class="hero-title">Sistem Informasi Pendataan Bidang Budidaya dan Pasca Panen Dinas Perikanan Jember</h1>
                    <p class="hero-subtitle">
                        SIPBIPER-BP adalah platform digital terpadu untuk pengelolaan data dan informasi pelaku usaha sektor perikanan. 
                        Memudahkan monitoring, pelaporan, dan analisis data pelaku usaha perikanan di Kabupaten Jember.
                    </p>
                    @if (Route::has('login'))
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 py-3 rounded-pill shadow fw-bold" style="background: white; color: #1e40af;">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Mulai Sekarang
                            </a>
                        @endguest
                    @endif
                </div>
                <div class="col-lg-5 text-center" data-aos="fade-left">
                    <img src="{{ asset('images/dinas-perikanan-logo.png') }}" alt="Dinas Perikanan" class="hero-image" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22400%22%3E%3Crect width=%22400%22 height=%22400%22 fill=%22%2360a5fa%22 opacity=%220.2%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2224%22 fill=%22white%22%3ELogo Dinas Perikanan%3C/text%3E%3C/svg%3E'">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-number">{{ number_format($totalPembudidaya) }}</div>
                        <div class="stat-label">Total Pembudidaya</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="stat-number">{{ number_format($totalPengolah) }}</div>
                        <div class="stat-label">Total Pengolah</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i class="bi bi-shop"></i>
                        </div>
                        <div class="stat-number">{{ number_format($totalPemasar) }}</div>
                        <div class="stat-label">Total Pemasar</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <div class="stat-icon red">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stat-number">{{ number_format($totalHargaIkan) }}</div>
                        <div class="stat-label">Data Harga Ikan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title" data-aos="fade-up">Fitur Utama SIPBIPER-BP</h2>
            <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                Berbagai fitur lengkap untuk mendukung pengelolaan data perikanan yang efektif dan efisien
            </p>
            
            <div class="row g-4">
                <!-- Feature 1 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-database-fill"></i>
                        </div>
                        <h3 class="feature-title">Manajemen Data Pelaku Usaha</h3>
                        <p class="feature-description">
                            Kelola data pembudidaya, pengolah, dan pemasar ikan secara terpusat dengan sistem yang terintegrasi dan mudah digunakan.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 2 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>
                        <h3 class="feature-title">Statistik & Visualisasi Data</h3>
                        <p class="feature-description">
                            Analisis data dengan grafik interaktif untuk memantau perkembangan sektor perikanan di berbagai wilayah.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 3 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <h3 class="feature-title">Monitoring Harga Ikan</h3>
                        <p class="feature-description">
                            Pantau fluktuasi harga ikan dari berbagai pasar untuk mendukung kebijakan yang tepat sasaran.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 4 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <h3 class="feature-title">Pemetaan Wilayah</h3>
                        <p class="feature-description">
                            Visualisasi sebaran pelaku usaha perikanan berdasarkan kecamatan dan desa untuk analisis geografis.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 5 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <h3 class="feature-title">Laporan & Rekapitulasi</h3>
                        <p class="feature-description">
                            Generate laporan lengkap dan rekapitulasi data yang dapat diekspor dalam berbagai format untuk keperluan administrasi.
                        </p>
                    </div>
                </div>
                
                <!-- Feature 6 -->
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="feature-title">Keamanan Data</h3>
                        <p class="feature-description">
                            Sistem keamanan berlapis dengan autentikasi pengguna dan manajemen hak akses untuk melindungi data sensitif.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="footer-logo">SIPBIPER-BP</div>
                    <p class="footer-text">
                        Sistem Informasi Pendataan Bidang Budidaya dan Pasca Panen<br>
                        Dinas Perikanan Kabupaten Jember<br>
                        Jawa Timur, Indonesia
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="footer-text">
                        <p class="mb-2"><i class="bi bi-envelope me-2"></i> dinasperikanan@jemberkab.go.id</p>
                        <p class="mb-2"><i class="bi bi-telephone me-2"></i> (0331) 5101342</p>
                        <p class="mb-0"><i class="bi bi-geo-alt me-2"></i> Jl. Letjend Suprapto No.139, Lingkungan Sumber Pak, Kebonsari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68122</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">© {{ date('Y') }} SIPBIPER-BP - Dinas Perikanan Kabupaten Jember. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Prevent back to authenticated pages after logout
        (function() {
            // Replace history state to prevent going back to dashboard
            if (window.history && window.history.pushState) {
                // Push current state to history to replace previous authenticated page
                window.history.pushState(null, null, window.location.href);
                
                // Listen for back button
                window.addEventListener('popstate', function(event) {
                    // Push state again to prevent going back
                    window.history.pushState(null, null, window.location.href);
                });
            }
        })();
    </script>
</body>
</html>

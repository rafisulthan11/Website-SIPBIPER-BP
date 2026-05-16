<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIPBIPER-BP - Sistem Informasi Pendataan Bidang Budidaya dan Pasca Panen Dinas Perikanan Jember</title>

    {{-- META DESCRIPTION --}}
    <meta name="description" content="SIPBIPER-BP adalah platform digital terpadu untuk pengelolaan data pembudidaya, pengolah, dan pemasar ikan di Kabupaten Jember. Monitoring, pelaporan, dan analisis data perikanan budidaya dan pasca panen.">

    {{-- PRECONNECT — buka koneksi TCP/TLS lebih awal ke CDN yang masih dipakai --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>

    {{-- PRELOAD HERO IMAGE — fetch gambar LCP di prioritas tertinggi sejak awal --}}
    <link rel="preload"
          as="image"
          href="{{ asset('images/ilustrasi-sipbiper.png') }}"
          imagesizes="(max-width: 768px) min(78vw, 300px), min(84vw, 420px)"
          fetchpriority="high">

    {{-- GOOGLE FONTS — async, tidak blocking render --}}
    <link rel="preload"
          href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    </noscript>

    {{-- BOOTSTRAP CSS — async, tidak blocking render --}}
    <link rel="preload"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </noscript>

    {{-- AOS CSS — async, tidak blocking render --}}
    <link rel="preload"
          href="https://unpkg.com/aos@2.3.1/dist/aos.css"
          as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </noscript>

    {{--
        ✅ BOOTSTRAP ICONS CDN DIHAPUS SEPENUHNYA
        Semua ikon diganti dengan SVG inline langsung di HTML.
        Ini menghilangkan bootstrap-icons.woff2 (127 KiB, 1,062ms) dari critical path.
    --}}

    <style>
        /* ================================================================
           CRITICAL CSS — di-inline agar halaman langsung bisa dirender
           tanpa menunggu Bootstrap atau CDN lain selesai diunduh.
        ================================================================ */

        :root {
            --primary-color: #1e40af;
            --secondary-color: #3b82f6;
            --accent-color: #60a5fa;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
        }

        * { font-family: 'Poppins', sans-serif; }
        body { overflow-x: hidden; margin: 0; }

        /* ── Navbar ── */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1030;
        }
        .navbar.scrolled { box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }
        .logo-img {
            width: clamp(180px, 24vw, 230px);
            height: auto;
            aspect-ratio: 230 / 56;
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
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 64, 175, 0.4);
            color: white;
        }

        /* ── SVG icon helper ── */
        .icon {
            width: 1.1em;
            height: 1.1em;
            fill: currentColor;
            flex-shrink: 0;
            vertical-align: middle;
        }
        .icon-lg {
            width: 2rem;
            height: 2rem;
            fill: currentColor;
            flex-shrink: 0;
        }

        /* ── Minimal Bootstrap grid subset untuk above-the-fold ── */
        .container {
            width: 100%;
            max-width: 1320px;
            margin-right: auto;
            margin-left: auto;
            padding-right: 12px;
            padding-left: 12px;
            box-sizing: border-box;
        }
        .ms-auto { margin-left: auto !important; }
        .row {
            --bs-gutter-x: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            margin-right: calc(-.5 * var(--bs-gutter-x));
            margin-left:  calc(-.5 * var(--bs-gutter-x));
        }
        .row > * {
            padding-right: calc(.5 * var(--bs-gutter-x));
            padding-left:  calc(.5 * var(--bs-gutter-x));
            width: 100%;
            box-sizing: border-box;
        }
        .align-items-center { align-items: center !important; }
        .text-center        { text-align: center !important; }
        .mb-0  { margin-bottom: 0 !important; }
        .mb-2  { margin-bottom: .5rem !important; }
        .mb-4  { margin-bottom: 1.5rem !important; }
        .mb-5  { margin-bottom: 3rem !important; }
        .me-2  { margin-right: .5rem !important; }
        .px-4  { padding-right: 1.5rem !important; padding-left: 1.5rem !important; }
        .py-3  { padding-top: 1rem !important; padding-bottom: 1rem !important; }
        .shadow     { box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important; }
        .fw-bold    { font-weight: 700 !important; }
        .rounded-pill { border-radius: 50rem !important; }
        .btn-lg {
            padding: .5rem 1rem;
            font-size: 1.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
        }
        .btn-light {
            background-color: white;
            color: #1e40af;
            border: none;
            cursor: pointer;
        }
        .order-1 { order: 1 !important; }
        .order-2 { order: 2 !important; }

        @media (min-width: 576px) { .col-md-6 { width: 50%; } }
        @media (min-width: 992px) {
            .col-lg-3 { width: 25%; }
            .col-lg-4 { width: 33.33333%; }
            .col-lg-5 { width: 41.66667%; }
            .col-lg-6 { width: 50%; }
            .col-lg-7 { width: 58.33333%; }
            .order-lg-1 { order: 1 !important; }
            .order-lg-2 { order: 2 !important; }
            .mb-lg-0    { margin-bottom: 0 !important; }
        }
        .g-4 { --bs-gutter-x: 1.5rem; --bs-gutter-y: 1.5rem; }
        .g-4 > * {
            padding-right: calc(.5 * var(--bs-gutter-x));
            padding-left:  calc(.5 * var(--bs-gutter-x));
            margin-top: var(--bs-gutter-y);
        }

        /* ── Hero ── */
        .hero-section {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            padding: 160px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,186.7C384,213,480,235,576,213.3C672,192,768,128,864,128C960,128,1056,192,1152,197.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: bottom;
            opacity: 0.3;
        }
        .hero-content { position: relative; z-index: 2; }
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
            width: min(84vw, 420px);
            max-width: 100%;
            height: auto;
            aspect-ratio: 393 / 278;
            margin: 0 auto;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.2));
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-20px); }
        }

        /* ── Stats Section ── */
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
        }
        .stat-icon.blue   { background: linear-gradient(135deg, #3b82f6, #60a5fa); color: white; }
        .stat-icon.green  { background: linear-gradient(135deg, #10b981, #34d399); color: white; }
        .stat-icon.orange { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: white; }
        .stat-icon.red    { background: linear-gradient(135deg, #ef4444, #f87171); color: white; }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        .stat-label { font-size: 1rem; color: #64748b; font-weight: 500; }

        /* ── Features Section ── */
        .features-section { padding: 100px 0; background: var(--light-color); }
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }
        .feature-description { color: #64748b; line-height: 1.7; margin-bottom: 0; }

        /* ── Footer ── */
        .footer { background: var(--dark-color); color: white; padding: 3rem 0 1.5rem; }
        .footer-logo { font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; }
        .footer-text { color: rgba(255, 255, 255, 0.7); line-height: 1.8; }
        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
        }
        .footer-contact-item .icon { margin-top: 0.3rem; flex-shrink: 0; }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .hero-title          { font-size: 1.9rem; }
            .hero-image          { width: min(78vw, 300px); }
            .hero-subtitle       { font-size: 0.95rem; }
            .section-title       { font-size: 1.7rem; }
            .feature-title       { font-size: 1.1rem; }
            .feature-description { font-size: 0.95rem; }
            .stat-card           { margin-bottom: 1.5rem; }
        }
    </style>
</head>
<body>

    {{-- ============================================================
         SVG ICON DEFINITIONS — disimpan di <defs> tersembunyi,
         dipanggil dengan <use href="#icon-xxx"> di seluruh halaman.
         Tidak ada request HTTP tambahan, tidak ada CDN, tidak ada woff2.
    ============================================================ --}}
    <svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">
        <defs>
            {{-- speedometer / dashboard --}}
            <symbol id="icon-speedometer" viewBox="0 0 16 16">
                <path d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2M3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                <path d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"/>
            </symbol>
            {{-- box-arrow-in-right / login --}}
            <symbol id="icon-login" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z"/>
                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
            </symbol>
            {{-- people / pembudidaya --}}
            <symbol id="icon-people" viewBox="0 0 16 16">
                <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.555.169-1.6.9-2.404A5 5 0 0 1 4.92 10M1 6a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
            </symbol>
            {{-- building / pengolah --}}
            <symbol id="icon-building" viewBox="0 0 16 16">
                <path d="M4 2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zM4 5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM7.5 5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zM4.5 8a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm2.5.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
                <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm11 0H3v14h3v-2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V15h3z"/>
            </symbol>
            {{-- shop / pemasar --}}
            <symbol id="icon-shop" viewBox="0 0 16 16">
                <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0M1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5M4 15h3v-5H4zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm3 0h-2v3h2z"/>
            </symbol>
            {{-- graph-up / harga ikan --}}
            <symbol id="icon-graph-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07"/>
            </symbol>
            {{-- database / manajemen data --}}
            <symbol id="icon-database" viewBox="0 0 16 16">
                <path d="M4.318 2.687C5.234 2.271 6.536 2 8 2s2.766.27 3.682.687C12.644 3.125 13 3.627 13 4c0 .374-.356.875-1.318 1.313C10.766 5.729 9.464 6 8 6s-2.766-.27-3.682-.687C3.356 4.875 3 4.373 3 4c0-.374.356-.875 1.318-1.313M13 5.698V7c0 .374-.356.875-1.318 1.313C10.766 8.729 9.464 9 8 9s-2.766-.27-3.682-.687C3.356 7.875 3 7.373 3 7V5.698c.271.202.58.378.904.525C4.978 6.711 6.427 7 8 7s3.022-.289 4.096-.777A5 5 0 0 0 13 5.698M3 8.698V10c0 .374.356.875 1.318 1.313C5.234 11.729 6.536 12 8 12s2.766-.27 3.682-.687C12.644 10.875 13 10.373 13 10V8.698c-.271.202-.58.378-.904.525C11.022 9.71 9.573 10 8 10s-3.022-.29-4.096-.777A5 5 0 0 1 3 8.698m0 3V13c0 .374.356.875 1.318 1.313C5.234 14.729 6.536 15 8 15s2.766-.27 3.682-.687C12.644 13.875 13 13.373 13 13v-1.302c-.271.202-.58.378-.904.525C11.022 12.71 9.573 13 8 13s-3.022-.29-4.096-.777A5 5 0 0 1 3 11.698"/>
            </symbol>
            {{-- bar-chart / statistik --}}
            <symbol id="icon-bar-chart" viewBox="0 0 16 16">
                <path d="M4 11H2v3h2zm5-4H7v7h2zm5-5v12h-2V2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z"/>
            </symbol>
            {{-- currency-dollar / harga --}}
            <symbol id="icon-currency" viewBox="0 0 16 16">
                <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.051zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"/>
            </symbol>
            {{-- geo-alt / pemetaan --}}
            <symbol id="icon-geo" viewBox="0 0 16 16">
                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </symbol>
            {{-- file-text / laporan --}}
            <symbol id="icon-file" viewBox="0 0 16 16">
                <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5M5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1z"/>
                <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
            </symbol>
            {{-- shield-check / keamanan --}}
            <symbol id="icon-shield" viewBox="0 0 16 16">
                <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.543 9.188a11.4 11.4 0 0 0 2.943 2.327c.35.197.856.403 1.341.403s.991-.206 1.341-.403a11.4 11.4 0 0 0 2.943-2.327c1.817-1.998 3.097-5.031 2.543-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.829 9.mersenne A12.3 12.3 0 0 1 8.017 14c-.51.168-1.064.302-1.503.302-.44 0-.993-.134-1.503-.302a12.3 12.3 0 0 1-3.508-2.462c-2.042-2.24-3.425-5.558-2.829-10.035A1.54 1.54 0 0 1 .718 1.43C1.376 1.215 2.495.86 3.605.56z"/>
                <path d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
            </symbol>
            {{-- envelope --}}
            <symbol id="icon-envelope" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z"/>
            </symbol>
            {{-- telephone --}}
            <symbol id="icon-telephone" viewBox="0 0 16 16">
                <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.6 17.6 0 0 0 4.168 6.608 17.6 17.6 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.68.68 0 0 0-.58-.122l-2.19.547a1.75 1.75 0 0 1-1.657-.459L5.482 8.062a1.75 1.75 0 0 1-.46-1.657l.548-2.19a.68.68 0 0 0-.122-.58zM1.884.511a1.745 1.745 0 0 1 2.612.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
            </symbol>
            {{-- geo-alt (footer) --}}
            <symbol id="icon-location" viewBox="0 0 16 16">
                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
            </symbol>
        </defs>
    </svg>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/sipbiper-logo.png') }}"
                     alt="SIPBIPER-BP Logo"
                     class="logo-img"
                     width="230"
                     height="56"
                     loading="eager"
                     onerror="this.style.display='none'">
            </a>

            @if (Route::has('login'))
                <div class="ms-auto">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-login">
                            <svg class="icon" aria-hidden="true"><use href="#icon-speedometer"/></svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <svg class="icon" aria-hidden="true"><use href="#icon-login"/></svg>
                            Masuk
                        </a>
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <main>

        {{-- Hero Section --}}
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center hero-content">

                    <div class="col-lg-5 text-center order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                        <img src="{{ asset('images/ilustrasi-sipbiper.png') }}"
                             alt="Ilustrasi SIPBIPER - Platform Digital Perikanan Jember"
                             class="hero-image"
                             width="393"
                             height="278"
                             fetchpriority="high"
                             loading="eager"
                             onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22400%22%3E%3Crect width=%22400%22 height=%22400%22 fill=%22%2360a5fa%22 opacity=%220.2%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2224%22 fill=%22white%22%3EIlustrasi SIPBIPER%3C/text%3E%3C/svg%3E'">
                    </div>

                    <div class="col-lg-7 order-2 order-lg-1 mb-5 mb-lg-0" data-aos="fade-right">
                        <h1 class="hero-title">Sistem Informasi Pendataan Bidang Perikanan Budidaya dan Pasca Panen</h1>
                        <p class="hero-subtitle">
                            SIPBIPER-BP adalah platform digital terpadu untuk pengelolaan data dan informasi pelaku usaha sektor perikanan.
                            Memudahkan monitoring, pelaporan, dan analisis data pelaku usaha perikanan di Kabupaten Jember.
                        </p>
                        @if (Route::has('login'))
                            @guest
                                <a href="{{ route('login') }}" class="btn btn-light btn-lg px-4 py-3 rounded-pill shadow fw-bold" style="background:white; color:#1e40af;">
                                    <svg class="icon" aria-hidden="true"><use href="#icon-login"/></svg>
                                    Mulai Sekarang
                                </a>
                            @endguest
                        @endif
                    </div>

                </div>
            </div>
        </section>

        {{-- Stats Section --}}
        <section class="stats-section">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                        <div class="stat-card">
                            <div class="stat-icon blue">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-people"/></svg>
                            </div>
                            <div class="stat-number">{{ number_format($totalPembudidaya) }}</div>
                            <div class="stat-label">Total Pembudidaya</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="stat-card">
                            <div class="stat-icon green">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-building"/></svg>
                            </div>
                            <div class="stat-number">{{ number_format($totalPengolah) }}</div>
                            <div class="stat-label">Total Pengolah</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="stat-card">
                            <div class="stat-icon orange">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-shop"/></svg>
                            </div>
                            <div class="stat-number">{{ number_format($totalPemasar) }}</div>
                            <div class="stat-label">Total Pemasar</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="stat-card">
                            <div class="stat-icon red">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-graph-up"/></svg>
                            </div>
                            <div class="stat-number">{{ number_format($totalHargaIkan) }}</div>
                            <div class="stat-label">Data Harga Ikan</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section class="features-section">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Fitur Utama SIPBIPER-BP</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Berbagai fitur lengkap untuk mendukung pengelolaan data perikanan yang efektif dan efisien
                </p>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="0">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-database"/></svg>
                            </div>
                            <h3 class="feature-title">Manajemen Data Pelaku Usaha</h3>
                            <p class="feature-description">Kelola data pembudidaya, pengolah, dan pemasar ikan secara terpusat dengan sistem yang terintegrasi dan mudah digunakan.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-bar-chart"/></svg>
                            </div>
                            <h3 class="feature-title">Statistik &amp; Visualisasi Data</h3>
                            <p class="feature-description">Analisis data dengan grafik interaktif untuk memantau perkembangan sektor perikanan di berbagai wilayah.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-currency"/></svg>
                            </div>
                            <h3 class="feature-title">Monitoring Harga Ikan</h3>
                            <p class="feature-description">Pantau fluktuasi harga ikan dari berbagai pasar untuk mendukung kebijakan yang tepat sasaran.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-geo"/></svg>
                            </div>
                            <h3 class="feature-title">Pemetaan Wilayah</h3>
                            <p class="feature-description">Visualisasi sebaran pelaku usaha perikanan berdasarkan kecamatan dan desa untuk analisis geografis.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-file"/></svg>
                            </div>
                            <h3 class="feature-title">Laporan &amp; Rekapitulasi</h3>
                            <p class="feature-description">Generate laporan lengkap dan rekapitulasi data yang dapat diekspor dalam berbagai format untuk keperluan administrasi.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <svg class="icon-lg" aria-hidden="true"><use href="#icon-shield"/></svg>
                            </div>
                            <h3 class="feature-title">Keamanan Data</h3>
                            <p class="feature-description">Sistem keamanan berlapis dengan autentikasi pengguna dan manajemen hak akses untuk melindungi data sensitif.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    {{-- Footer --}}
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
                        <div class="footer-contact-item">
                            <svg class="icon" aria-hidden="true"><use href="#icon-envelope"/></svg>
                            <span>dinasperikanan@jemberkab.go.id</span>
                        </div>
                        <div class="footer-contact-item">
                            <svg class="icon" aria-hidden="true"><use href="#icon-telephone"/></svg>
                            <span>(0331) 5101342</span>
                        </div>
                        <div class="footer-contact-item">
                            <svg class="icon" aria-hidden="true"><use href="#icon-location"/></svg>
                            <span>Jl. Letjend Suprapto No.139, Lingkungan Sumber Pak, Kebonsari, Kec. Sumbersari, Kabupaten Jember, Jawa Timur 68122</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} SIPBIPER-BP - Dinas Perikanan Kabupaten Jember. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- JS di akhir body + defer, tidak blocking render --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js" defer></script>

    <script>
        // Polyfill rel=preload as=style untuk browser lama
        !function(e){"use strict";if(e.loadCSS){var t=loadCSS.relpreload={};if(t.support=function(){var e;try{e=w.document.createElement("link").relList.supports("preload")}catch(t){e=!1}return function(){return e}}(),t.bindMediaToggle=function(e){var t=e.media||"all";function n(){e.media=t}"addEventListener"in e?e.addEventListener("load",n):e.attachEvent&&e.attachEvent("onload",n),setTimeout(function(){e.rel="stylesheet",e.media="only x"}),setTimeout(n,3e3)},t.poly=function(){if(!t.support()){var n=e.document.getElementsByTagName("link");for(var r=0;r<n.length;r++){var o=n[r];"preload"===o.rel&&"style"===o.getAttribute("as")&&!o.getAttribute("data-loadcss")&&(o.setAttribute("data-loadcss",!0),t.bindMediaToggle(o))}}},!t.support()){t.poly();var n=e.setInterval(t.poly,500);e.addEventListener?e.addEventListener("load",function(){t.poly(),e.clearInterval(n)}):e.attachEvent&&e.attachEvent("onload",function(){t.poly(),e.clearInterval(n)})}}}(this);

        document.addEventListener('DOMContentLoaded', function () {
            // Init AOS
            function initAOS() {
                if (typeof AOS !== 'undefined') {
                    AOS.init({ duration: 800, once: true, offset: 100 });
                }
            }
            initAOS();
            window.addEventListener('load', initAOS);

            // Navbar scroll effect
            var navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', function () {
                navbar.classList.toggle('scrolled', window.scrollY > 50);
            }, { passive: true });
        });

        // Mencegah navigasi kembali ke halaman dashboard setelah logout
        (function () {
            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, window.location.href);
                window.addEventListener('popstate', function () {
                    window.history.pushState(null, null, window.location.href);
                });
            }
        })();
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tracer Alumni | IKPM Gontor </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            color: #fff;
            position: relative;
            overflow-x: hidden;
        }
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(6px);
            opacity: 0.16;
        }
        body::before {
            width: 320px;
            height: 320px;
            top: -80px;
            right: -90px;
            background: radial-gradient(circle, rgba(255,255,255,0.55) 0%, rgba(255,255,255,0) 70%);
        }
        body::after {
            width: 260px;
            height: 260px;
            left: -70px;
            bottom: -80px;
            background: radial-gradient(circle, rgba(255,255,255,0.45) 0%, rgba(255,255,255,0) 72%);
        }
        .main-navbar {
            background: transparent;
            box-shadow: none;
            padding-top: 20px;
            padding-bottom: 0;
            z-index: 2;
            position: relative;
            backdrop-filter: blur(6px);
        }
        /* Penyesuaian agar logo dan teks sejajar sempurna */
        .brand-logo {
            display: flex;
            align-items: center;
        }
        .brand-logo img {
            width: 80px; /* Ukuran disesuaikan agar proporsional */
            height: auto;
            margin-right: 15px;
        }
        .brand-title-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-top: 15px; /* Menurunkan teks sedikit agar sejajar tengah logo */
        }
        .brand-title {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: .5px;
            line-height: 1.2;
            color: #fff;
        }
        .brand-subtitle {
            font-size: 0.85rem;
            font-weight: 400;
            color: #e4ffe6;
        }
        .main-nav .nav-link {
            color: #e4ffe6 !important;
            font-weight: 500;
            margin-right: 18px;
            opacity: 0.88;
        }
        .main-nav .nav-link.active, .main-nav .nav-link:hover {
            color: #fff !important;
            text-decoration: underline;
            opacity: 1;
        }

        .hero-section {
            min-height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            text-align: center;
            z-index: 1;
            padding-bottom: 40px;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 12% 8% auto 8%;
            height: 320px;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(255,255,255,0.09), rgba(255,255,255,0.02));
            box-shadow: 0 18px 60px rgba(20, 78, 36, 0.08);
            z-index: 0;
        }

        /* Update Watermark ke Logo IKPM */
        .hero-bg-watermark {
            position: absolute;
            left: 50%;
            top: 47%;
            transform: translate(-50%, -50%);
            width: 400px;
            max-width: 90vw;
            opacity: 0.15; /* Lebih tipis agar teks terbaca */
            z-index: 0;
            user-select: none;
            pointer-events: none;
            animation: watermarkFloat 7s ease-in-out infinite;
        }

        @media (max-width: 800px) {
            .brand-title { font-size: 1rem; }
            .brand-subtitle { font-size: 0.75rem; }
            .brand-logo img { width: 50px; }
            .hero-title { font-size: 1.8rem;}
            .hero-bg-watermark { width: 220px; }
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 900;
            letter-spacing: 1px;
            text-shadow: 0 2px 14px rgba(21, 80, 38, 0.16);
            z-index: 2;
            position: relative;
            margin-bottom: 0.6rem;
            animation: fadeUp 0.7s ease both;
        }
        .subtitle {
            margin: 0 auto 1.7rem auto;
            font-size: 1.1rem;
            max-width: 650px;
            color: #e6ffe8;
            font-weight: 400;
            z-index: 2;
            position: relative;
            line-height: 1.6;
            animation: fadeUp 0.85s ease both;
        }
        .hero-section .btn-main {
            margin-top: 1.35rem;
            padding: 0.85rem 2.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            background: #fff;
            color: #229954 !important;
            border: none;
            border-radius: 2rem;
            box-shadow: 0 6px 36px rgba(40,150,80,0.13);
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            z-index: 2;
            position: relative;
            animation: fadeUp 1s ease both;
        }
        .hero-section .btn-main:hover {
            background: #e8ffe4;
            transform: translateY(-4px);
            box-shadow: 0 10px 42px rgba(40,150,80,0.2);
        }
        
        .card-section {
            margin-top: 3rem;
            margin-bottom: 2.2rem;
        }
        .card-feature {
            background: rgba(255,255,255,0.07);
            border: none;
            border-radius: 14px;
            color: #fff;
            box-shadow: 0 2px 14px rgba(37, 90, 51, 0.13);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            animation: fadeUp 1.05s ease both;
        }
        .card-feature:hover {
            transform: translateY(-8px);
            box-shadow: 0 14px 34px rgba(37,90,51,0.24);
            background: rgba(255,255,255,0.12);
        }
        footer {
            background: transparent;
            color: #e7ffe7;
            font-size: 0.9rem;
            text-align: center;
            padding: 25px 0;
            font-weight: 500;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes watermarkFloat {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -52%) scale(1.03); }
        }

        .card-section .col-lg-4:nth-child(1) .card-feature { animation-delay: 0.15s; }
        .card-section .col-lg-4:nth-child(2) .card-feature { animation-delay: 0.3s; }
        .card-section .col-lg-4:nth-child(3) .card-feature { animation-delay: 0.45s; }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar main-navbar navbar-expand-lg">
        <div class="container">
            <div class="brand-logo">
                <img src="assets/logo-ikpm2.png" alt="Logo IKPM" />
                <div class="brand-title-container">
                    <span class="brand-title">TRACER ALUMNI IKPM GONTOR</span>
                    <span class="brand-subtitle"> Wilayah Sulawesi Selatan & Sulawesi Barat</span>
                </div>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end main-nav" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="berita.php">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login Alumni</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Registrasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin/login_admin.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="hero-section">
        <h1 class="hero-title">Tracer Alumni</h1>
        <div class="subtitle">
            Platform pendataan dan pemetaan kiprah alumni untuk mendukung sinergi potensi, keberlanjutan program kerja, serta penguatan jaringan ukhuwwah di lingkungan keluarga besar IKPM Gontor Sulawesi Selatan & Barat.
        </div>
        <a href="login.php" class="btn btn-main">Masuk ke Sistem</a>
    </main>

    <section class="container card-section">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card card-feature h-100 p-4 text-center">
                    <h4>📝 Registrasi Mudah</h4>
                    <p>Daftar sebagai alumni IKPM hanya dengan beberapa langkah mudah. Data Anda akan langsung terintegrasi dalam database wilayah.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card card-feature h-100 p-4 text-center">
                    <h4>📊 Tajdidul Ma'lumat</h4>
                    <p>Pendataan kuesioner dirancang untuk memetakan potensi alumni sebagai pondasi utama dalam merancang program kerja dan kegiatan strategis IKPM ke depan.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card card-feature h-100 p-4 text-center">
                    <h4>📈 Sinergi Potensi</h4>
                    <p>Memudahkan organisasi dalam melakukan monitoring dan evaluasi alumni untuk mendukung agenda pemberdayaan di setiap daerah.</p>
                </div>
            </div>
        </div>
    </section>

    <footer>
        &copy; <?= date('Y') ?> Tracer Alumni IKPM Gontor Sulselbar. All Rights Reserved.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
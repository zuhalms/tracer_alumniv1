<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Beranda | IKPM Gontor Sulselbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #1abc9c;
            --secondary-green: #27ae60;
            --dark-green: #155c29;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
            font-family: 'Inter', sans-serif;
            color: #fff;
            position: relative;
            overflow-x: hidden;
        }

        /* Dekorasi Latar Belakang */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(40px);
            opacity: 0.2;
        }
        body::before { width: 400px; height: 400px; top: -100px; right: -100px; background: #fff; }
        body::after { width: 300px; height: 300px; left: -80px; bottom: -80px; background: #fff; }

        /* Navbar Style */
        .main-navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 0;
            z-index: 1050;
        }
        .brand-logo img { width: 65px; height: 50px; margin-right: 8px; top: -8px; position: relative; }
        .brand-title { font-weight: 800; font-size: 1.1rem; line-height: 1.2; display: block; }
        .brand-subtitle { font-size: 0.80rem; opacity: 0.8; margin-top: -4px; display: block; }
        .nav-link { font-weight: 600; color: rgba(255,255,255,0.85) !important; margin-left: 20px; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: #fff !important; transform: translateY(-2px); }

        /* Hero Carousel */
        .hero-carousel .carousel-item {
            height: 90vh;
            min-height: 600px;
            background-color: #000;
        }
        .carousel-image {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover;
            opacity: 0.5; /* Gelapkan gambar agar teks terbaca */
        }
        .carousel-caption-center {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            width: 80%;
            z-index: 10;
        }
        .hero-title { font-size: 3.5rem; font-weight: 800; text-shadow: 0 4px 15px rgba(0,0,0,0.3); }
        .hero-subtitle { font-size: 1.2rem; max-width: 800px; margin: 20px auto; opacity: 0.9; }

        /* Section Styling */
        .glass-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 50px;
            margin-top: -50px;
            position: relative;
            z-index: 20;
            color: #fff;
        }
        .section-title { font-weight: 800; margin-bottom: 25px; position: relative; padding-bottom: 10px; }
        .section-title::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 60px; height: 4px; background: #fff; border-radius: 2px;
        }

        /* Card Feature */
        .card-feature {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            transition: 0.4s;
            color: #fff;
        }
        .card-feature:hover { transform: translateY(-10px); background: rgba(255, 255, 255, 0.25); }

        /* Pengurus Card */
        .member-card {
            text-align: center;
            padding: 30px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
        }
        .member-card:hover { background: rgba(255, 255, 255, 0.15); }
        .member-img {
            width: 130px; height: 130px;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            margin-bottom: 15px;
            object-fit: cover;
        }

        footer { padding: 40px 0; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 50px; opacity: 0.8; }

        @media (max-width: 768px) {
            .hero-title { font-size: 2rem; }
            .glass-section { padding: 30px; margin-top: 0; border-radius: 0; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg main-navbar sticky-top">
        <div class="container">
            <div class="brand-logo d-flex align-items-center">
                <img src="assets/logo-ikpm2.png" alt="Logo">
                <div class="text-white">
                    <span class="brand-title">Ikatan Keluarga Pondok Modern Gontor</span>
                    <span class="brand-subtitle">Sulawesi Selatan & Sulawesi Barat</span>
                </div>
            </div>
            <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="berita.php">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login Alumni</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Registrasi</a></li>
                    <li class="nav-item"><a class="btn btn-light ms-lg-3 text-success fw-bold px-4 rounded-pill" href="admin/login_admin.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

        <div id="heroCarousel" class="carousel slide carousel-fade hero-carousel" data-bs-ride="carousel" style="position: relative;">
            
            <div class="carousel-caption-center" style="z-index: 20;">
                <h1 class="hero-title">Portal Resmi IKPM Gontor</h1>
                <p class="hero-subtitle">Wadah sinergi, komunikasi, dan pengabdian alumni Pondok Modern Darussalam Gontor di wilayah Sulawesi Selatan dan Sulawesi Barat.</p>
                <a href="login.php" class="btn btn-white btn-lg rounded-pill px-5 fw-bold shadow mt-3" style="background: white; color: var(--secondary-green); position: relative; z-index: 30;">Masuk ke Sistem</a>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="2000">
                    <img src="assets/1.jpg" class="carousel-image" alt="Gontor 1">
                </div>

                <div class="carousel-item" data-bs-interval="2000">
                    <img src="assets/2.jpg" class="carousel-image" alt="Gontor 2">
                </div>

                <div class="carousel-item" data-bs-interval="2000">
                    <img src="assets/3.jpg" class="carousel-image" alt="Gontor 3">
                </div>

                <div class="carousel-item" data-bs-interval="2000">
                    <img src="assets/4.jpg" class="carousel-image" alt="Gontor 4">
                </div>

            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 25;">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 25;">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

    <div class="container">
        <div class="glass-section shadow">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h2 class="section-title">Sejarah & Nilai Gontor</h2>
                    <p>Pondok Modern Darussalam Gontor, yang didirikan pada tahun 1926 oleh Tiga Serangkai (Trimurti), telah lama menjadi mercusuar pendidikan Islam di Indonesia. Dengan falsafah hidup yang mandiri dan berdikari, Gontor mencetak kader-kader pemimpin umat.</p>
                    <p>IKPM Gontor Cabang Sulawesi Selatan dan Barat hadir sebagai perpanjangan tangan nilai-nilai tersebut, mengorganisir alumni yang tersebar di pelosok daerah untuk tetap satu visi dalam "Perekat Ummat".</p>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="assets/logo-ikpm2.png" class="img-fluid" style="max-height: 250px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));" alt="Logo Besar">
                </div>
            </div>
        </div>
    </div>

    <section class="container mt-5 py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-feature h-100 p-4 text-center">
                    <div class="display-5 mb-3">📝</div>
                    <h4>Registrasi Alumni</h4>
                    <p class="small opacity-75">Pendataan resmi alumni untuk mempermudah koordinasi dan info kegiatan wilayah.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-feature h-100 p-4 text-center">
                    <div class="display-5 mb-3">📊</div>
                    <h4>Tajdidul Ma'lumat</h4>
                    <p class="small opacity-75">Pembaruan data profesi dan domisili guna pemetaan potensi ekonomi dan sosial alumni.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-feature h-100 p-4 text-center">
                    <div class="display-5 mb-3">📈</div>
                    <h4>Sinergi Potensi</h4>
                    <p class="small opacity-75">Kolaborasi antar alumni untuk mendukung agenda pemberdayaan di setiap daerah.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container mb-5 py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Struktur Pengurus Inti</h2>
            <p class="opacity-75">IKPM Gontor Cabang Sulawesi Selatan & Sulawesi Barat</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-3 col-md-6">
                <div class="member-card">
                    <img src="https://ui-avatars.com/api/?name=Ketua+Umum&background=fff&color=27ae60&size=128" class="member-img" alt="Ketua">
                    <h5 class="fw-bold mb-1">Nama Ketua</h5>
                    <span class="badge bg-white text-success rounded-pill px-3">Ketua Umum</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="member-card">
                    <img src="https://ui-avatars.com/api/?name=Sekretaris&background=fff&color=27ae60&size=128" class="member-img" alt="Sekretaris">
                    <h5 class="fw-bold mb-1">Nama Sekretaris</h5>
                    <span class="badge bg-white text-success rounded-pill px-3">Sekretaris</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="member-card">
                    <img src="https://ui-avatars.com/api/?name=Bendahara&background=fff&color=27ae60&size=128" class="member-img" alt="Bendahara">
                    <h5 class="fw-bold mb-1">Nama Bendahara</h5>
                    <span class="badge bg-white text-success rounded-pill px-3">Bendahara</span>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> IKPM Gontor Sulawesi Selatan & Barat. Seluruh Hak Cipta Dilindungi.</p>
            <div class="mt-2 small opacity-50">Dibuat dengan dedikasi untuk Alumni Gontor.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
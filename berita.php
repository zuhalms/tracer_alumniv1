<?php include 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Berita Terkini | IKPM Gontor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            color: #fff;
            position: relative;
            overflow-x: hidden;
        }
        .main-navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 0;
            z-index: 1050;
        }
        .brand-logo {
            display: flex;
            align-items: center;
        }
        .brand-logo img {
            width: 65px;
            height: 50px;
            margin-right: 8px;
            top: -8px;
            position: relative;
        }
        .brand-title {
            font-weight: 800;
            font-size: 1.1rem;
            line-height: 1.2;
            color: #fff;
            display: block;
        }
        .brand-subtitle {
            font-size: 0.80rem;
            opacity: 0.8;
            margin-top: -4px;
            display: block;
        }
        .nav-link {
            font-weight: 600;
            color: rgba(255,255,255,0.85) !important;
            margin-left: 20px;
            transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: #fff !important;
            transform: translateY(-2px);
        }

        .news-wrap {
            padding: 26px 0 60px;
        }
        .news-hero {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 24px;
            backdrop-filter: blur(8px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
            padding: 32px 28px;
            text-align: center;
            margin-bottom: 28px;
        }
        .news-hero h1 {
            font-weight: 900;
            font-size: 2.35rem;
            margin-bottom: 12px;
            text-shadow: 0 2px 14px rgba(21, 80, 38, 0.16);
        }
        .news-hero p {
            margin-bottom: 0;
            color: #e7ffe7;
            font-size: 1rem;
            line-height: 1.7;
        }
        .news-grid {
            margin-top: 6px;
        }

        .card-news {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(34, 197, 94, 0.08);
            border: none;
            border-radius: 20px;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            color: #334155;
        }
        .card-news:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 42px rgba(0, 0, 0, 0.14);
        }
        .news-media {
            width: 100%;
            aspect-ratio: 16 / 9;
            object-fit: cover;
            background: linear-gradient(135deg, rgba(26, 188, 156, 0.15), rgba(39, 174, 96, 0.15));
        }
        .news-content {
            padding: 22px 22px 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }
        .news-date,
        .news-meta {
            font-size: 0.85rem;
            color: #27ae60;
            font-weight: 600;
            display: block;
        }
        .news-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.5;
            margin-bottom: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 3.25rem;
        }
        .news-excerpt {
            font-size: 0.95rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 0;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 6.5rem;
        }
        .news-divider {
            height: 1px;
            background: linear-gradient(90deg, rgba(39,174,96,0.18), rgba(39,174,96,0.03));
            margin: 2px 0 0;
        }
        .news-actions {
            margin-top: auto;
        }
        .btn-news {
            background: #1e293b;
            color: #fff;
            border-radius: 999px;
            padding: 10px 22px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }
        .btn-news:hover {
            background: #27ae60;
            color: #fff;
            transform: translateY(-1px);
        }

        footer {
            background: transparent;
            color: #e7ffe7;
            font-size: 0.9rem;
            text-align: center;
            padding: 25px 0;
            font-weight: 500;
        }

        @media (max-width: 767.98px) {
            .news-hero {
                padding: 24px 18px;
                border-radius: 20px;
            }
            .news-hero h1 {
                font-size: 1.8rem;
            }
            .news-content {
                padding: 18px 18px 20px;
            }
            .news-title {
                min-height: auto;
            }
            .news-excerpt {
                min-height: auto;
                -webkit-line-clamp: 5;
            }
            .brand-title {
                font-size: 1rem;
            }
            .brand-logo img {
                width: 45px;
                height: 35px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg main-navbar navbar-dark sticky-top">
        <div class="container">
            <div class="brand-logo">
                <img src="assets/logo-ikpm2.png" alt="Logo IKPM" />
                <div>
                    <span class="brand-title">Ikatan Keluarga Pondok Modern Gontor</span>
                    <span class="brand-subtitle">Sulawesi Selatan & Sulawesi Barat</span>
                </div>
            </div>
            <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link active" href="berita.php">Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login Alumni</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Registrasi</a></li>
                    <li class="nav-item"><a class="btn btn-light ms-lg-3 text-success fw-bold px-4 rounded-pill" href="admin/login_admin.php">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container news-wrap">
        <div class="news-hero">
            <h1>Berita Terkini</h1>
            <p>Informasi terbaru mengenai kegiatan Alumni IKPM Gontor Sulselbar.</p>
        </div>

        <div class="row g-4 news-grid">
            <?php
            $query = mysqli_query($conn, "SELECT * FROM tb_berita ORDER BY tanggal_kegiatan DESC");
            
            if(mysqli_num_rows($query) > 0) {
                while($row = mysqli_fetch_assoc($query)) {
            ?>
            <div class="col-lg-4 col-md-6">
                <div class="card-news">
                    <?php if (!empty($row['foto_kegiatan'])): ?>
                        <img src="uploads/berita/<?= htmlspecialchars($row['foto_kegiatan']) ?>" class="news-media" alt="Foto berita">
                    <?php endif; ?>
                    <div class="news-content">
                        <span class="news-date">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?= date('d F Y', strtotime($row['tanggal_kegiatan'])) ?>
                        </span>
                        <h3 class="news-title"><?= htmlspecialchars($row['judul']) ?></h3>
                        <div class="news-divider"></div>
                        <div class="news-excerpt">
                            <?= substr(strip_tags($row['isi']), 0, 140) ?>...
                        </div>
                        <div class="news-actions">
                            <a href="detail_berita.php?id=<?= $row['id_berita'] ?>" class="btn-news">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                } 
            } else {
                echo "<div class='col-12'><div class='bg-white text-center rounded-4 shadow-sm p-5 text-dark'><h4 class='mb-2'>Belum ada berita yang dipublikasikan.</h4><p class='mb-0 text-muted'>Silakan kembali lagi nanti untuk membaca informasi terbaru.</p></div></div>";
            }
            ?>
        </div>
    </div>

    <footer>
        &copy; <?= date('Y') ?> Tracer Alumni IKPM Gontor Sulselbar. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
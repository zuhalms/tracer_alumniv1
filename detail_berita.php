<?php 
include 'config/config.php'; 

// Mengambil ID berita dari URL
if (isset($_GET['id'])) {
    $id_berita = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM tb_berita WHERE id_berita = '$id_berita'");
    $data = mysqli_fetch_assoc($query);

    // Jika data tidak ditemukan, arahkan kembali ke berita.php
    if (!$data) {
        header("Location: berita.php");
        exit;
    }
} else {
    header("Location: berita.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($data['judul']) ?> | IKPM Gontor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,500,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            font-family: 'Montserrat', Arial, sans-serif;
            color: #fff;
            overflow-x: hidden;
        }

        /* Navbar sesuai index.php */
        .main-navbar {
            background: transparent;
            padding-top: 20px;
            position: relative;
            z-index: 10;
        }
        .brand-logo { display: flex; align-items: center; }
        .brand-logo img { width: 80px; margin-right: 15px; }
        .brand-title-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-top: 15px;
        }
        .brand-title { font-weight: 700; font-size: 1.25rem; color: #fff; line-height: 1.2; }
        .brand-subtitle { font-size: 0.85rem; color: #e4ffe6; }
        .main-nav .nav-link { color: #e4ffe6 !important; font-weight: 500; margin-right: 18px; opacity: 0.9; }
        .main-nav .nav-link:hover { color: #fff !important; text-decoration: underline; opacity: 1; }

        /* Container Detail Berita */
        .detail-wrapper {
            padding: 28px 0 78px;
        }

        .detail-hero {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 22px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(8px);
            padding: 22px 24px;
            margin-bottom: 22px;
        }

        .detail-hero-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0;
            color: #f3fff4;
        }

        .content-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            color: #333;
        }

        .detail-top {
            padding: 24px 24px 0;
        }

        .detail-img {
            width: 100%;
            max-height: 460px;
            object-fit: cover;
        }

        .detail-body {
            padding: 28px 28px 34px;
        }

        .detail-date {
            color: #27ae60;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 10px;
            display: block;
        }

        .detail-title {
            font-weight: 800;
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 18px;
            line-height: 1.25;
            letter-spacing: -0.02em;
        }

        .detail-text {
            font-size: 1.05rem;
            line-height: 1.95;
            color: #475569;
            text-align: justify;
            word-break: break-word;
        }
        .detail-text img {
            max-width: 100%;
            height: auto;
            border-radius: 14px;
            margin: 14px 0;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08);
        }

        .btn-back {
            background: #1e293b;
            color: #fff;
            border-radius: 999px;
            padding: 10px 25px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #27ae60;
            color: #fff;
            transform: translateX(-3px);
        }

        footer {
            text-align: center;
            padding: 30px 0;
            font-weight: 500;
            color: #e7ffe7;
        }

        @media (max-width: 767.98px) {
            .brand-logo img { width: 52px; }
            .brand-title { font-size: 1rem; }
            .brand-subtitle { font-size: 0.75rem; }
            .detail-wrapper { padding-top: 18px; padding-bottom: 56px; }
            .detail-hero { padding: 18px 18px; border-radius: 18px; }
            .detail-body { padding: 20px 18px 24px; }
            .detail-title { font-size: 1.5rem; }
            .detail-text { font-size: 1rem; line-height: 1.85; text-align: left; }
            .btn-back { width: 100%; justify-content: center; }
            .detail-top { padding: 18px 18px 0; }
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
        </div>
    </nav>

    <div class="container detail-wrapper">
        <div class="detail-hero">
            <p class="detail-hero-title mb-0">Baca berita lengkap dengan tampilan yang lebih nyaman dan fokus pada isi.</p>
        </div>

        <a href="berita.php" class="btn-back">
            <i class="bi bi-arrow-left me-2"></i> Kembali ke Berita
        </a>

        <div class="content-card">
            <?php if (!empty($data['foto_kegiatan']) && file_exists('uploads/berita/' . $data['foto_kegiatan'])): ?>
                <div class="detail-top">
                    <img src="uploads/berita/<?= htmlspecialchars($data['foto_kegiatan']) ?>" class="detail-img rounded-4" alt="Foto Kegiatan">
                </div>
            <?php endif; ?>

            <div class="detail-body">
                <span class="detail-date">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= date('d F Y', strtotime($data['tanggal_kegiatan'])) ?>
                </span>

                <h1 class="detail-title"><?= htmlspecialchars($data['judul']) ?></h1>

                <div class="detail-text">
                    <?php
                    $isi_berita = $data['isi'];
                    $contains_rich_html = preg_match('/<\s*(img|p|figure|div|br)\b/i', $isi_berita) === 1;
                    echo $contains_rich_html ? $isi_berita : nl2br($isi_berita);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        &copy; <?= date('Y') ?> Tracer Alumni IKPM Gontor Sulselbar. All Rights Reserved.
    </footer>

</body>
</html>
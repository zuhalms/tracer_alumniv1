<?php
session_start();
include 'config/config.php';

// Proteksi Halaman
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

$id_alumni = $_SESSION['id_alumni'];

// 1. Ambil data profil (Sesuai tb_alumni)
$query_alumni = mysqli_query($conn, "SELECT * FROM tb_alumni WHERE id_alumni = '$id_alumni'");
$data = mysqli_fetch_assoc($query_alumni);

// 2. Ambil data pekerjaan (Sesuai tb_pekerjaan)
$query_pekerjaan = mysqli_query($conn, "SELECT * FROM tb_pekerjaan WHERE id_alumni = '$id_alumni' ORDER BY id_pekerjaan DESC LIMIT 1");
$pekerjaan = mysqli_fetch_assoc($query_pekerjaan);

// 3. Ambil data kuesioner (Sesuai tb_kuesioner)
$query_kuesioner = mysqli_query($conn, "SELECT * FROM tb_kuesioner WHERE id_alumni = '$id_alumni' LIMIT 1");
$kuesioner = mysqli_fetch_assoc($query_kuesioner);

$title = "Dashboard Alumni - Tracer Study IKPM";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f6fafd; min-height: 100vh; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        .navbar { background: #e8f5e9 !important; border-bottom: 2px solid #197948; z-index: 1051; position: fixed; top: 0; width: 100%; }
        .navbar-brand { color: #197948 !important; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .navbar-brand img { height: 35px; }

        .sidebar { width: 265px; background: #fff; border-right: 1px solid #e4efea; position: fixed; top: 0; bottom: 0; padding-top: 80px; z-index: 1040; transition: 0.3s; }
        .profile-box { text-align: center; padding: 20px; border-bottom: 1px solid #eee; margin-bottom: 15px; }
        .profile-img { width: 85px; height: 85px; object-fit: cover; border-radius: 50%; border: 3px solid #197948; }
        .profile-name { font-size: 1.1rem; font-weight: 700; color: #197948; margin-top: 10px; }
        .profile-desc { font-size: 0.85rem; color: #666; }

        .sidebar-link { display: flex; align-items: center; padding: 12px 25px; color: #444; text-decoration: none; transition: 0.2s; font-weight: 500; }
        .sidebar-link:hover, .sidebar-link.active { background: #dcf8e5; color: #197948; }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }

        .main-content { margin-left: 265px; margin-top: 64px; padding: 40px; transition: 0.3s; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        .status-icon { width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; }
        .status-complete { background: #d1e7dd; color: #0f5132; }
        .status-pending { background: #fff3cd; color: #664d03; }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-3">
        <button class="btn d-lg-none text-success" id="hamburgerBtn"><i class="bi bi-list fs-3"></i></button>
        <a class="navbar-brand" href="#">
            <img src="assets/logo-ikpm2.png" alt="Logo">
            <span>Tracer Alumni - IKPM Gontor</span>
        </a>
        <div class="ms-auto d-none d-lg-block">
            <span class="badge bg-success-subtle text-success p-2">Status: <?= $data['status_verifikasi'] ?></span>
        </div>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <div class="profile-box">
        <?php $foto = (!empty($data['foto']) && file_exists($data['foto'])) ? $data['foto'] : 'assets/profile_placeholder.jpg'; ?>
        <img src="<?= $foto ?>" class="profile-img">
        <div class="profile-name"><?= htmlspecialchars(explode(' ', $data['nama_lengkap'])[0]) ?></div>
        <div class="profile-desc">Marhalah <?= htmlspecialchars($data['marhalah']) ?></div>
        <div class="profile-desc text-muted small">Stambuk: <?= htmlspecialchars($data['stambuk']) ?></div>
    </div>
    
    <div class="nav-links">
        <a href="dashboard_alumni.php" class="sidebar-link active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="profil.php" class="sidebar-link"><i class="bi bi-person-circle"></i> Profil Pribadi</a>
        <a href="pekerjaan.php" class="sidebar-link"><i class="bi bi-briefcase-fill"></i> Aktivitas/Khidmah</a>
        <a href="kuesioner.php" class="sidebar-link"><i class="bi bi-file-earmark-text-fill"></i> Kuesioner</a>
        <hr class="mx-3">
        <a href="logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="mb-4">
        <h3 class="fw-bold">Ahlan wa Sahlan, Gontori!</h3>
        <p class="text-muted">Pantau status tracer alumni dan lengkapi data khidmah Anda.</p>
    </div>

    <div class="card mb-4 bg-success text-white p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4>Selamat Datang, <?= htmlspecialchars($data['nama_lengkap']) ?></h4>
                <p class="mb-0 opacity-75">Data Anda membantu kami memetakan persebaran alumni IKPM Gontor dan meningkatkan kualitas pelayanan organisasi.</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="bi bi-mortarboard" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h6 class="fw-bold text-muted small mb-3">DATA PRIBADI</h6>
                <div class="d-flex align-items-center mb-2">
                    <span class="status-icon status-complete"><i class="bi bi-check-lg"></i></span>
                    <span class="fw-bold">Terdaftar</span>
                </div>
                <p class="small text-muted mb-3">Konsulat: <?= htmlspecialchars($data['konsulat']) ?></p>
                <a href="profil.php" class="btn btn-outline-success btn-sm mt-auto">Update Profil</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h6 class="fw-bold text-muted small mb-3">STATUS AKTIVITAS</h6>
                <?php if ($pekerjaan): ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-icon status-complete"><i class="bi bi-check-lg"></i></span>
                        <span class="fw-bold"><?= htmlspecialchars($pekerjaan['status_aktivitas']) ?></span>
                    </div>
                    <p class="small text-muted mb-3"><?= htmlspecialchars($pekerjaan['nama_instansi'] ?? 'Di Instansi/Pondok') ?></p>
                    <a href="pekerjaan.php" class="btn btn-outline-success btn-sm mt-auto">Lihat Detail</a>
                <?php else: ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-icon status-pending"><i class="bi bi-exclamation"></i></span>
                        <span class="text-muted">Belum Diisi</span>
                    </div>
                    <p class="small text-muted mb-3">Mohon isi data pekerjaan/studi Anda.</p>
                    <a href="pekerjaan.php" class="btn btn-success btn-sm mt-auto">Isi Sekarang</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 h-100">
                <h6 class="fw-bold text-muted small mb-3">KUESIONER</h6>
                <?php if ($kuesioner): ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-icon status-complete"><i class="bi bi-check-lg"></i></span>
                        <span class="fw-bold">Selesai</span>
                    </div>
                    <p class="small text-muted mb-3">Diisi pada: <?= date('d/m/Y', strtotime($kuesioner['tanggal_isi'])) ?></p>
                    <a href="kuesioner.php" class="btn btn-outline-success btn-sm mt-auto">Lihat Jawaban</a>
                <?php else: ?>
                    <div class="d-flex align-items-center mb-2">
                        <span class="status-icon status-pending"><i class="bi bi-clock"></i></span>
                        <span class="text-muted">Menunggu</span>
                    </div>
                    <p class="small text-muted mb-3">Evaluasi kebermanfaatan organisasi.</p>
                    <a href="kuesioner.php" class="btn btn-success btn-sm mt-auto">Mulai Isi</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h6 class="fw-bold mb-3"><i class="bi bi-bar-chart-fill me-2 text-success"></i>Kelengkapan Tracer Study</h6>
        <?php 
            $steps = 1; // 1 = Registrasi/Profil
            if ($pekerjaan) $steps++;
            if ($kuesioner) $steps++;
            $percent = ($steps / 3) * 100;
        ?>
        <div class="progress mb-3" style="height: 12px; border-radius: 10px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percent ?>%"></div>
        </div>
        <div class="d-flex justify-content-between">
            <span class="small fw-bold text-success"><?= round($percent) ?>% Selesai</span>
            <span class="small text-muted"><?= $steps ?> dari 3 Tahap</span>
        </div>
    </div>
</div>

<script>
    const btn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    btn.onclick = () => sidebar.classList.toggle('active');
</script>
</body>
</html>
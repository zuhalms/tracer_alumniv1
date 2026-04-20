<?php
session_start();
include '../config/config.php'; 

/** * PROTEKSI ADMIN 
 * Memastikan hanya admin yang sudah login bisa mengakses
 */
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login_admin.php?error=not_logged_in");
    exit();
}

// Ambil data session untuk tampilan profile
$admin_nama = $_SESSION['admin_nama'] ?? 'Administrator';
$admin_level = $_SESSION['admin_level'] ?? 'IKPM Sulselbar';

// 1. Hitung Total Alumni
$sql_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_alumni");
$total_alumni = mysqli_fetch_assoc($sql_total)['total'] ?? 0;

// 2. Hitung Sudah Kuesioner
$sql_kuesioner = mysqli_query($conn, "SELECT COUNT(DISTINCT id_alumni) as total FROM tb_kuesioner");
$total_sudah_kuesioner = mysqli_fetch_assoc($sql_kuesioner)['total'] ?? 0;

// 3. Menunggu Verifikasi
$sql_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_alumni WHERE status_verifikasi = 'Pending'");
$total_pending = mysqli_fetch_assoc($sql_pending)['total'] ?? 0;

// 4. Hitung Yang Sudah Update Pekerjaan
$sql_kerja = mysqli_query($conn, "SELECT COUNT(DISTINCT id_alumni) as total FROM tb_pekerjaan");
$total_kerja = mysqli_fetch_assoc($sql_kerja)['total'] ?? 0;

// 5. Tambahan: Hitung Jumlah Berita (Opsional untuk statistik)
$sql_berita = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_berita");
$total_berita = mysqli_fetch_assoc($sql_berita)['total'] ?? 0;

// Deteksi halaman aktif untuk class CSS
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Tracer IKPM</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #f6fafd 0%, #eefaf2 100%);
            font-family: 'Inter', sans-serif;
            color: #334155;
            overflow-x: hidden;
            position: relative;
        }
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(8px);
            opacity: 0.18;
        }
        body::before {
            width: 340px;
            height: 340px;
            top: -100px;
            right: -120px;
            background: radial-gradient(circle, rgba(25,121,72,0.22) 0%, rgba(25,121,72,0) 72%);
        }
        body::after {
            width: 260px;
            height: 260px;
            left: -90px;
            bottom: -120px;
            background: radial-gradient(circle, rgba(46,172,104,0.18) 0%, rgba(46,172,104,0) 74%);
        }
        
        .navbar { 
            background: rgba(232, 245, 233, 0.9) !important; 
            border-bottom: 1px solid rgba(25, 121, 72, 0.18); 
            height: 64px; 
            z-index: 1100;
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 30px rgba(25, 121, 72, 0.06);
        }
        .navbar-brand { color: #197948 !important; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .navbar-brand img { height: 35px; }

        .sidebar { 
            width: 265px; 
            background: #fff; 
            border-right: 1px solid #e4efea; 
            position: fixed; 
            top: 0; 
            bottom: 0; 
            padding-top: 80px; 
            z-index: 1050; 
            transition: 0.3s;
            box-shadow: 12px 0 28px rgba(15, 23, 42, 0.04);
        }
        .sidebar-profile { text-align: center; padding: 22px 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 15px; }
        .admin-avatar { width: 80px; height: 80px; border-radius: 50%; border: 3px solid #197948; padding: 3px; object-fit: cover; box-shadow: 0 8px 24px rgba(25, 121, 72, 0.12); }
        
        .sidebar-link { 
            display: flex; 
            align-items: center; 
            padding: 13px 25px; 
            color: #475569; 
            text-decoration: none; 
            font-weight: 500; 
            transition: 0.2s ease;
            position: relative;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: linear-gradient(90deg, rgba(220, 248, 229, 0.95), rgba(220, 248, 229, 0.55));
            color: #197948;
            transform: translateX(4px);
        }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }

        .main-content { 
            margin-left: 265px; 
            margin-top: 64px; 
            padding: 40px; 
            transition: 0.3s;
            position: relative;
            z-index: 1;
        }
        
        .welcome-card { 
            background: linear-gradient(135deg, #197948 0%, #2eac68 100%); 
            color: white; 
            border-radius: 20px; 
            border: none;
            overflow: hidden;
            box-shadow: 0 16px 38px rgba(25, 121, 72, 0.18);
            position: relative;
        }
        .welcome-card::before {
            content: '';
            position: absolute;
            inset: 18px auto auto auto;
            right: 18px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }
        .welcome-card::after {
            content: '';
            position: absolute;
            left: -28px;
            bottom: -34px;
            width: 120px;
            height: 120px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.07);
            transform: rotate(18deg);
        }

        .stat-card { 
            border-radius: 18px; 
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05); 
            transition: transform 0.22s ease, box-shadow 0.22s ease; 
            background: rgba(255, 255, 255, 0.96);
        }
        .stat-card:hover { 
            transform: translateY(-6px); 
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08); 
        }
        .icon-box { 
            width: 48px; height: 48px; border-radius: 12px; 
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.6);
        }
        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 1rem;
        }
        .section-title h3,
        .section-title p {
            margin-bottom: 0;
        }
        .quick-actions-card {
            border-radius: 18px;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
            background: rgba(255, 255, 255, 0.96);
        }
        .quick-actions-card .btn {
            border-radius: 999px;
        }
        .btn-success {
            background: linear-gradient(135deg, #197948, #2eac68);
            border-color: #197948;
            box-shadow: 0 8px 20px rgba(25, 121, 72, 0.16);
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #155c29, #24995a);
            border-color: #155c29;
            transform: translateY(-1px);
        }
        .btn-outline-primary {
            border-color: rgba(25, 121, 72, 0.3);
            color: #197948;
        }
        .btn-outline-primary:hover {
            background: #197948;
            border-color: #197948;
            color: #fff;
        }
        .content-fade {
            animation: fadeUp 0.7s ease both;
        }
        .content-fade.delay-1 { animation-delay: 0.08s; }
        .content-fade.delay-2 { animation-delay: 0.16s; }
        .content-fade.delay-3 { animation-delay: 0.24s; }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); box-shadow: 10px 0 30px rgba(0,0,0,0.1); }
            .main-content { margin-left: 0; padding: 20px; }
            .section-title { flex-direction: column; align-items: flex-start; }
            .welcome-card::before,
            .welcome-card::after { display: none; }
            .quick-actions-card .d-flex { flex-direction: column; }
            .quick-actions-card .btn { width: 100%; }
        }

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

<nav class="navbar fixed-top">
    <div class="container-fluid px-3">
        <button class="btn d-lg-none text-success" id="hamburgerBtn">
            <i class="bi bi-list fs-3"></i>
        </button>
        <a class="navbar-brand" href="dashboard_admin.php">
            <img src="../assets/logo-ikpm2.png" alt="Logo">
            <span>Admin Tracer IKPM</span>
        </a>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <div class="sidebar-profile">
        <img src="../assets/admin.png" class="admin-avatar" alt="Admin" onerror="this.src='https://via.placeholder.com/80'">
        <div class="fw-bold mt-2 text-success"><?= htmlspecialchars($admin_nama) ?></div>
        <div class="small text-muted"><?= htmlspecialchars($admin_level) ?></div>
    </div>
    
    <div class="nav-links">
        <a href="dashboard_admin.php" class="sidebar-link <?= ($current_page == 'dashboard_admin.php') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="data_alumni.php" class="sidebar-link <?= ($current_page == 'data_alumni.php') ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i> Data Alumni
        </a>
        
        <a href="kelola_berita.php" class="sidebar-link <?= ($current_page == 'kelola_berita.php') ? 'active' : '' ?>">
            <i class="bi bi-newspaper"></i> Kelola Berita
        </a>

        <a href="admin_export.php" class="sidebar-link <?= ($current_page == 'admin_export.php') ? 'active' : '' ?>">
            <i class="bi bi-file-earmark-excel-fill"></i> Export Data
        </a>
        <hr class="mx-3">
        <a href="logout_admin.php" class="sidebar-link text-danger" onclick="return confirm('Yakin ingin logout?')">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <div class="section-title content-fade">
        <h3 class="fw-bold">Ringkasan Statistik</h3>
        <p class="text-muted">Pantau perkembangan data tracer alumni secara real-time.</p>
    </div>

    <div class="card welcome-card p-4 mb-4 shadow-sm content-fade delay-1">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="fw-bold">Selamat Datang, <?= explode(' ', $admin_nama)[0] ?>!</h4>
                <p class="mb-0 opacity-75">Anda login sebagai <strong><?= $admin_level ?></strong>. Gunakan panel ini untuk mengelola data alumni Sulselbar.</p>
            </div>
            <div class="col-md-4 text-end d-none d-md-block">
                <i class="bi bi-shield-check" style="font-size: 4rem; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card p-3 h-100 content-fade delay-1">
                <div class="icon-box bg-success-subtle text-success mb-3">
                    <i class="bi bi-people"></i>
                </div>
                <h6 class="text-muted small fw-bold">TOTAL ALUMNI</h6>
                <h2 class="fw-bold mb-0"><?= number_format($total_alumni, 0, ',', '.'); ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card p-3 h-100 content-fade delay-2">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <?php if($total_pending > 0): ?>
                        <span class="badge bg-danger rounded-pill">Perlu Verifikasi</span>
                    <?php endif; ?>
                </div>
                <h6 class="text-muted small fw-bold">MENUNGGU VERIFIKASI</h6>
                <h2 class="fw-bold mb-0 text-danger"><?= number_format($total_pending, 0, ',', '.'); ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card p-3 h-100 content-fade delay-3">
                <div class="icon-box bg-info-subtle text-info mb-3">
                    <i class="bi bi-newspaper"></i>
                </div>
                <h6 class="text-muted small fw-bold">JUMLAH BERITA</h6>
                <h2 class="fw-bold mb-0"><?= number_format($total_berita, 0, ',', '.'); ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card p-3 h-100 content-fade delay-3">
                <div class="icon-box bg-primary-subtle text-primary mb-3">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h6 class="text-muted small fw-bold">KUESIONER MASUK</h6>
                <h2 class="fw-bold mb-0"><?= number_format($total_sudah_kuesioner, 0, ',', '.'); ?></h2>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card quick-actions-card p-4 border-0 shadow-sm content-fade delay-3">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Aksi Cepat</h6>
                <div class="d-flex gap-2">
                    <a href="data_alumni.php" class="btn btn-success px-4">Kelola Alumni</a>
                    <a href="kelola_berita.php" class="btn btn-success px-4">Input Berita Baru</a>
                    <a href="admin_export.php" class="btn btn-outline-primary px-4">Unduh Laporan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const btn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    
    btn.onclick = (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('active');
    };

    document.onclick = (e) => {
        if (!sidebar.contains(e.target) && !btn.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    };
</script>

</body>
</html>
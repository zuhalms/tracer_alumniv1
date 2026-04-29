<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= isset($title) ? htmlspecialchars($title) : 'Tracer Alumni Kampus' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:700,400&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            min-height: 100vh;
            font-family: 'Montserrat', Arial, sans-serif;
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
            color: #fff;
            line-height: 1.2;
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
        @media (max-width: 800px) {
            .brand-title { font-size: 1rem; }
            .brand-logo img { width: 45px; height: 35px; }
        }
        .container-main {
            padding-top: 68px;
            padding-bottom: 40px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg main-navbar navbar-dark sticky-top">
    <div class="container">
        <div class="brand-logo">
            <img src="assets/logo-ikpm2.png" alt="Logo IKPM Gontor" />
            <div class="brand-title">
                <span>Ikatan Keluarga Pondok Modern Gontor</span>
                <span class="brand-subtitle">Sulawesi Selatan & Sulawesi Barat</span>
            </div>
        </div>
        <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNavMain">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item"><a class="nav-link<?= ($_SERVER['SCRIPT_NAME']=='/index.php'?' active':'') ?>" href="index.php">Beranda</a></li>
                <?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard_alumni.php"><i class="bi bi-house-door"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="profil.php"><i class="bi bi-person-circle"></i> Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="kuesioner.php"><i class="bi bi-journal-text"></i> Kuesioner</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="berita.php"><i class="bi bi-newspaper"></i> Berita</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php"><i class="bi bi-pencil-square"></i> Registrasi</a></li>
                    <li class="nav-item"><a class="btn btn-light ms-lg-3 text-success fw-bold px-4 rounded-pill" href="admin/login_admin.php">Admin</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container container-main">
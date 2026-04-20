<?php
session_start();

// Jika sudah login, redirect ke dashboard alumni
if (isset($_SESSION['id_alumni'])) {
    header("Location: dashboard_alumni.php");
    exit();
}
$title = "Tracer Alumni | IKPM Gontor";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Alumni - Tracer Alumni | IKPM Gontor</title>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            position: relative;
            overflow-x: hidden;
        }
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0.16;
            filter: blur(8px);
            z-index: 0;
        }
        body::before {
            width: 360px;
            height: 360px;
            top: -120px;
            right: -100px;
            background: radial-gradient(circle, rgba(255,255,255,0.55) 0%, rgba(255,255,255,0) 70%);
        }
        body::after {
            width: 280px;
            height: 280px;
            left: -90px;
            bottom: -100px;
            background: radial-gradient(circle, rgba(255,255,255,0.45) 0%, rgba(255,255,255,0) 72%);
        }
        .login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            z-index: 1;
        }
        .login-card {
            width: min(100%, 1020px);
            min-height: 600px;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            border-radius: 28px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.16);
            box-shadow: 0 24px 60px rgba(10, 60, 30, 0.2);
            backdrop-filter: blur(10px);
        }
        .login-visual {
            position: relative;
            padding: 44px;
            background: linear-gradient(160deg, rgba(19, 121, 72, 0.98), rgba(26, 188, 156, 0.9));
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }
        .login-visual::before {
            content: '';
            position: absolute;
            inset: auto -60px -120px auto;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .login-visual::after {
            content: '';
            position: absolute;
            top: 36px;
            right: 36px;
            width: 120px;
            height: 120px;
            border-radius: 28px;
            border: 1px solid rgba(255,255,255,0.14);
            transform: rotate(14deg);
        }
        .brand-block,
        .login-points {
            position: relative;
            z-index: 1;
        }
        .brand-logo-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }
        .brand-logo-row img {
            width: 66px;
            height: 66px;
            object-fit: contain;
        }
        .brand-kicker {
            font-size: 0.85rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            opacity: 0.82;
            margin-bottom: 8px;
        }
        .brand-title {
            font-size: 2.1rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 12px;
        }
        .brand-desc {
            max-width: 420px;
            line-height: 1.75;
            color: #ecffef;
            margin-bottom: 0;
        }
        .visual-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            margin-top: 22px;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #f4fff6;
            font-weight: 600;
        }
        .login-points {
            display: grid;
            gap: 14px;
        }
        .point-text {
            font-size: 0.95rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.92);
            margin: 0;
        }
        .login-form-panel {
            padding: 46px 40px;
            background: rgba(255, 255, 255, 0.98);
            display: flex;
            align-items: center;
        }
        .login-box {
            width: 100%;
        }
        .login-head {
            margin-bottom: 20px;
        }
        .login-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .login-logo img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }
        .login-title {
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 8px;
            color: #197948;
        }
        .login-desc {
            color: #64748b;
            margin-bottom: 0;
            font-size: 1rem;
            line-height: 1.7;
        }
        .form-label {
            font-weight: 600;
            color: #334155;
        }
        .form-control {
            border-radius: 14px;
            border: 1px solid #d8e7dd;
            padding: 0.85rem 1rem;
        }
        .form-control:focus {
            border-color: #197948;
            box-shadow: 0 0 0 0.2rem rgba(25, 121, 72, 0.16);
        }
        .input-group .btn {
            border-radius: 0 14px 14px 0;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            color: #197948;
            text-decoration: none;
            font-weight: 700;
        }
        .back-link:hover {
            color: #155c29;
        }
        .btn-green {
            background: linear-gradient(135deg, #197948, #2eac68);
            border: 0;
            color: #fff;
            font-weight: 700;
            padding: 12px 0;
            border-radius: 14px;
            font-size: 1.05rem;
            box-shadow: 0 10px 22px rgba(25, 121, 72, 0.18);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-green:hover {
            background: linear-gradient(135deg, #155c29, #24995a);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(25, 121, 72, 0.22);
        }
        .login-links {
            color: #64748b;
        }
        .login-links a {
            color: #197948;
            font-weight: 700;
            text-decoration: none;
        }
        .login-links a:hover {
            color: #155c29;
            text-decoration: underline;
        }
        .alert {
            border-radius: 14px;
        }
        @media (max-width: 991.98px) {
            .login-shell { padding: 16px; }
            .login-card {
                grid-template-columns: 1fr;
                min-height: auto;
            }
            .login-visual {
                padding: 32px 24px;
            }
            .login-form-panel {
                padding: 32px 24px 36px;
            }
            .brand-title {
                font-size: 1.7rem;
            }
        }
        @media (max-width: 575.98px) {
            .login-shell { padding: 12px; }
            .login-visual { padding: 26px 20px; }
            .login-form-panel { padding: 24px 18px 28px; }
            .brand-logo-row { align-items: flex-start; }
            .brand-title { font-size: 1.45rem; }
            .login-title { font-size: 1.5rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
    <script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
            btn.querySelector('span').classList.remove('bi-eye');
            btn.querySelector('span').classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            btn.querySelector('span').classList.remove('bi-eye-slash');
            btn.querySelector('span').classList.add('bi-eye');
        }
    }
    </script>
</head>
<body>
<div class="login-shell">
    <div class="login-card">
        <section class="login-visual d-none d-lg-flex">
            <div class="brand-block">
                <div class="brand-logo-row">
                    <img src="assets/logo-ikpm2.png" alt="Logo Kampus" />
                    <div>
                        <div class="brand-kicker">Tracer Alumni IKPM Gontor</div>
                        <div class="brand-title">Login Alumni</div>
                    </div>
                </div>
                <p class="brand-desc">Masuk untuk mengakses dashboard alumni, memperbarui profil, serta mengisi kuesioner tracer study.</p>
                <div class="visual-chip"><i class="bi bi-shield-lock-fill"></i> Akses aman dan terintegrasi</div>
            </div>
            <div class="login-points">
                <p class="point-text">Pantau data diri, pekerjaan, dan perkembangan alumni dalam satu tempat yang sederhana dan jelas.</p>
            </div>
        </section>

        <section class="login-form-panel">
            <div class="login-box">
                <a href="index.php" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>

                <div class="login-head">
                    <div class="login-logo">
                        <img src="assets/logo-ikpm2.png" alt="Logo Kampus" />
                        <div>
                            <div class="fw-bold text-success">Tracer Alumni</div>
                            <div class="small text-muted">IKPM Gontor</div>
                        </div>
                    </div>
                    <div class="login-title">Login Alumni</div>
                    <p class="login-desc">Masukkan username dan password untuk masuk ke dashboard alumni.</p>
                </div>

                <?php
                if (isset($_GET['success']) && $_GET['success'] == 'register') {
                    echo '<div class="alert alert-success">Registrasi berhasil! Silakan login.</div>';
                }
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger">';
                    if ($_GET['error'] == 'wrong_credentials') {
                        echo 'NIM/Email atau Password salah!';
                    } elseif ($_GET['error'] == 'empty_fields') {
                        echo 'Mohon isi semua field dengan lengkap!';
                    } else {
                        echo 'Terjadi kesalahan, silakan coba lagi!';
                    }
                    echo '</div>';
                }
                if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
                    echo '<div class="alert alert-info">Anda berhasil logout.</div>';
                }
                ?>

                <form action="proses_login.php" method="POST" autocomplete="off">
                    <div class="mb-3">
                        <label for="nim_email" class="form-label">Username</label>
                        <input type="text" id="nim_email" name="nim_email" class="form-control" required autofocus placeholder="Masukkan username/NIW" />
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" required placeholder="Masukkan password" />
                            <button type="button" class="btn btn-outline-secondary" tabindex="-1" onclick="togglePassword('password', this)" aria-label="Tampilkan/Sembunyikan Password">
                                <span class="bi bi-eye"></span>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-green w-100 my-3">Login</button>
                </form>

                <div class="mt-2 text-center small login-links">
                    Belum punya akun? <a href="register.php">Daftar di sini</a>
                    <br>
                    <a href="index.php">← Kembali ke Beranda</a>
                </div>
            
            </div>
        </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
session_start();

// Jika sudah login, redirect ke dashboard alumni
if (isset($_SESSION['id_alumni'])) {
    header('Location: dashboard_alumni.php');
    exit();
}

$title = 'Tracer Alumni | IKPM Gontor';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Tracer Alumni | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            background: linear-gradient(135deg, #1abc9c 0%, #27ae60 100%);
            overflow-x: hidden;
            position: relative;
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
            width: min(100%, 540px);
            padding: 24px;
            position: relative;
            z-index: 1;
        }
        .login-card {
            min-height: 0;
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 44px rgba(10, 60, 30, 0.16);
            backdrop-filter: blur(8px);
        }
        .login-form-panel {
            padding: 28px;
            background: rgba(255, 255, 255, 0.98);
        }
        .brand-inline {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .brand-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
            flex: 0 0 auto;
            filter: drop-shadow(0 4px 8px rgba(25, 121, 72, 0.22));
            
            /* Tambahkan dua baris di bawah ini */
            transform: translateY(-10px); /* Angka minus (-) untuk geser ke atas */
            margin-left: 10px;           /* Angka positif untuk geser ke kanan */
        }
        .org-name {
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.4;
            margin: 0;
            color: #154d30;
        }
        .org-region {
            margin: 2px 0 0;
            font-size: 0.78rem;
            color: #5f7d6d;
            letter-spacing: 0.04em;
        }
        .login-title {
            font-size: 1.35rem;
            font-weight: 800;
            line-height: 1.25;
            margin: 0 0 6px;
            color: #154d30;
            text-align: center;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            padding: 8px 12px;
            border-radius: 12px;
            color: #197948;
            text-decoration: none;
            font-weight: 600;
            background: rgba(25, 121, 72, 0.07);
            border: 1px solid rgba(25, 121, 72, 0.12);
            transition: background-color 0.2s ease, transform 0.2s ease, border-color 0.2s ease;
        }
        .back-link:hover {
            color: #155c29;
            background: rgba(25, 121, 72, 0.12);
            border-color: rgba(25, 121, 72, 0.22);
            transform: translateY(-1px);
        }
        .form-control {
            border-radius: 14px;
            border: 1px solid #d8e7dd;
            padding: 0.85rem 1rem;
        }
        .form-control:focus { border-color: #197948; box-shadow: 0 0 0 0.2rem rgba(25, 121, 72, 0.18); }
        .input-group .btn { border-radius: 0 14px 14px 0; }
        .btn-success {
            background: linear-gradient(135deg, #197948, #2eac68);
            border-color: #197948;
            border-radius: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 10px 22px rgba(25, 121, 72, 0.18);
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #155c29, #24995a);
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(25, 121, 72, 0.22);
        }
        .alert { border-radius: 14px; }
        .login-links a { color: #197948; text-decoration: none; font-weight: 700; }
        .login-links a:hover { text-decoration: underline; }
        
        @media (max-width: 575.98px) {
            .org-name { font-size: 0.95rem; }
            .org-region { font-size: 0.72rem; }
            .login-title { font-size: 1.2rem; }
            .back-link { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-card">
            <section class="login-form-panel">
                <div class="login-box">
                    <a href="index.php" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
                    
                    <div class="brand-inline">
                        <img src="assets/logo-ikpm2.png" alt="Logo IKPM" class="brand-logo"/>
                        <div>
                            <p class="org-name">Ikatan Keluarga Pondok Modern Gontor</p>
                            <p class="org-region">Sulawesi Selatan dan Sulawesi Barat</p>
                        </div>
                    </div>

                    <h1 class="login-title">Login Alumni</h1>

                    <?php if (isset($_GET['success']) && $_GET['success'] == 'register'): ?>
                        <div class="alert alert-success alert-dismissible fade show small" role="alert">
                            Registrasi berhasil! Silakan login.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php
                            if ($_GET['error'] == 'wrong_credentials') echo 'NIM/Email atau Password salah!';
                            elseif ($_GET['error'] == 'empty_fields') echo 'Mohon isi semua field dengan lengkap!';
                            else echo 'Terjadi kesalahan, silakan coba lagi!';
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
                        <div class="alert alert-info alert-dismissible fade show small" role="alert">
                            Anda berhasil logout.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="proses_login.php" method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="nim_email" class="form-control" required autofocus placeholder="Masukkan username/NIW" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password" />
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-semibold py-2 mt-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                        </button>
                    </form>

                    <div class="mt-4 text-center small login-links">
                        Belum punya akun? <a href="register.php">Daftar di sini</a>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const toggleIcon = document.getElementById('togglePasswordIcon');
        
        if (passwordInput && toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                toggleIcon.classList.toggle('bi-eye');
                toggleIcon.classList.toggle('bi-eye-slash');
            });
        }
    });
    </script>
</body>
</html>
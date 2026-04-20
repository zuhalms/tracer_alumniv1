<?php
session_start();
include '../config/config.php';

// Jika sudah login, redirect ke dashboard admin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: dashboard_admin.php');
    exit();
}

$error = '';

// Handle error dari URL parameter
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'not_logged_in':
            $error = 'Silakan login terlebih dahulu untuk mengakses halaman admin.';
            break;
        case 'session_expired':
            $error = 'Sesi Anda telah berakhir. Silakan login kembali.';
            break;
        default:
            $error = '';
    }
}

// Proses login jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$username = mysqli_real_escape_string($conn, trim($_POST['username']));
$password = trim($_POST['password']); // Fungsi trim() ini penting untuk hapus spasi tak sengaja

    if (empty($username) || empty($password)) {
        $error = "Username dan password harus diisi!";
    } else {
        // Ambil data admin berdasarkan username
        $query = "SELECT * FROM tb_admin WHERE username = '$username' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            
            // VERIFIKASI BARU: Menggunakan password_verify untuk mendukung Password Hash
            if (password_verify($password, $row['password'])) {
                // Set session yang sinkron dengan database
                $_SESSION['is_admin'] = true;
                $_SESSION['id_admin'] = $row['id_admin']; 
                $_SESSION['admin_username'] = $row['username'];
                $_SESSION['admin_nama'] = $row['nama_admin'];
                $_SESSION['admin_level'] = $row['level'];
                
                header('Location: dashboard_admin.php');
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Username tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-ikpm2.png">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Tracer Admin | Login</title>
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
            overflow: hidden;
            position: relative;
        }
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0.15;
            filter: blur(6px);
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
            width: min(100%, 980px);
            padding: 24px;
            position: relative;
            z-index: 1;
        }
        .login-card {
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            min-height: 560px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(10, 60, 30, 0.2);
            backdrop-filter: blur(8px);
        }
        .login-visual {
            position: relative;
            padding: 42px;
            color: #fff;
            background: linear-gradient(160deg, rgba(19, 121, 72, 0.98), rgba(26, 188, 156, 0.88));
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
            top: 40px;
            right: 40px;
            width: 120px;
            height: 120px;
            border-radius: 28px;
            border: 1px solid rgba(255,255,255,0.14);
            transform: rotate(14deg);
        }
        .brand-row {
            position: relative;
            z-index: 1;
        }
        .brand-row img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            margin-bottom: 18px;
        }
        .brand-kicker {
            font-size: 0.85rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            opacity: 0.82;
            margin-bottom: 10px;
        }
        .brand-title {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 12px;
        }
        .brand-desc {
            max-width: 420px;
            color: #ecffef;
            line-height: 1.75;
            margin-bottom: 0;
        }
        .visual-stats {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 14px;
            margin-top: 28px;
        }
        .visual-chip {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #f4fff6;
            font-weight: 600;
        }
        .visual-note {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.7;
            margin: 0;
        }
        .login-form-panel {
            padding: 44px 38px;
            background: rgba(255, 255, 255, 0.98);
            display: flex;
            align-items: center;
        }
        .login-box {
            width: 100%;
        }
        .login-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        .login-logo img { width: 46px; height: 46px; object-fit: contain; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 20px; color: #197948; text-decoration: none; font-weight: 700; }
        .back-link:hover { color: #155c29; }
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
        .login-subtitle {
            color: #64748b;
            line-height: 1.7;
            margin-bottom: 26px;
        }
        .alert {
            border-radius: 14px;
        }
        @media (max-width: 991.98px) {
            body { overflow-y: auto; }
            .login-shell { padding: 16px; }
            .login-card { grid-template-columns: 1fr; min-height: auto; }
            .login-visual { padding: 30px 24px; }
            .login-form-panel { padding: 32px 24px 34px; }
            .brand-title { font-size: 1.6rem; }
        }
        @media (max-width: 575.98px) {
            .login-shell { padding: 12px; }
            .login-visual { padding: 24px 20px; }
            .login-form-panel { padding: 24px 18px 26px; }
            .brand-title { font-size: 1.35rem; }
            .brand-desc { font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="login-card">
            <section class="login-visual d-none d-lg-flex">
                <div class="brand-row">
                    <img src="../assets/logo-ikpm2.png" alt="Logo IKPM"/>
                    <div class="brand-kicker">Tracer Alumni IKPM Gontor</div>
                    <div class="brand-title">Admin Control Center</div>
                    <p class="brand-desc">Masuk untuk mengelola data alumni, berita, dan verifikasi.</p>
                </div>
                <div class="visual-stats">
                    <div class="visual-chip"><i class="bi bi-shield-lock-fill"></i> Akses aman untuk admin</div>
   
                </div>
            </section>

            <section class="login-form-panel">
                <div class="login-box">
                    <a href="../index.php" class="back-link"><i class="bi bi-arrow-left"></i> Kembali ke Beranda</a>
                    <div class="login-logo">
                        <img src="../assets/logo-ikpm2.png" alt="Logo IKPM"/>
                        <div>
                            <div class="fw-bold fs-4 text-success">Tracer Admin</div>
                            <div class="small text-muted">Akses Administrator</div>
                        </div>
                    </div>
                    <p class="login-subtitle">Silakan masuk menggunakan akun admin untuk mengelola data tracer alumni.</p>
                    
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="login_admin.php">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control" required autofocus placeholder="Masukkan username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 fw-semibold py-2 mt-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                        </button>
                    </form>
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
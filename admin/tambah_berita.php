<?php
session_start();
include '../config/config.php'; 

// PROTEKSI ADMIN
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login_admin.php?error=not_logged_in");
    exit();
}

$admin_nama = $_SESSION['admin_nama'] ?? 'Administrator';
$admin_level = $_SESSION['admin_level'] ?? 'IKPM Sulselbar';
$current_page = 'kelola_berita.php';

// PROSES SIMPAN BERITA
if (isset($_POST['simpan'])) {
    $judul   = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $isi_input = trim($_POST['isi']);

    // Konfigurasi Upload Gambar (mendukung banyak file)
    $allowed   = ['jpg', 'jpeg', 'png'];

    if (!isset($_FILES['foto']) || empty($_FILES['foto']['name'][0])) {
        echo "<script>alert('Silakan pilih minimal 1 gambar.');</script>";
    } else {
        $uploaded_images = [];
        $total_files = count($_FILES['foto']['name']);
        $upload_error = '';

        for ($i = 0; $i < $total_files; $i++) {
            $foto_name = $_FILES['foto']['name'][$i];
            $foto_tmp  = $_FILES['foto']['tmp_name'][$i];
            $foto_size = $_FILES['foto']['size'][$i];
            $foto_err  = $_FILES['foto']['error'][$i];

            if ($foto_err !== UPLOAD_ERR_OK) {
                $upload_error = 'Terjadi kesalahan saat upload gambar.';
                break;
            }

            $foto_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));

            if (!in_array($foto_ext, $allowed)) {
                $upload_error = 'Format file tidak didukung! Gunakan JPG/JPEG/PNG.';
                break;
            }

            if ($foto_size > 2000000) {
                $upload_error = 'Ukuran file terlalu besar! Maksimal 2MB per gambar.';
                break;
            }

            $foto_baru = "Berita_" . date('Ymd_His') . "_" . ($i + 1) . "." . $foto_ext;
            $path = "../uploads/berita/" . $foto_baru;

            if (!move_uploaded_file($foto_tmp, $path)) {
                $upload_error = 'Gagal mengunggah gambar ke server.';
                break;
            }

            $uploaded_images[] = $foto_baru;
        }

        if (!empty($upload_error)) {
            echo "<script>alert('" . $upload_error . "');</script>";
        } else {
            $isi_final = $isi_input;
            $has_placeholder = preg_match('/\[gambar([0-9]+)\]/i', $isi_input) === 1;

            if ($has_placeholder) {
                $isi_final = preg_replace_callback('/\[gambar([0-9]+)\]/i', function ($match) use ($uploaded_images) {
                    $index = (int)$match[1] - 1;
                    if (isset($uploaded_images[$index])) {
                        $src = 'uploads/berita/' . $uploaded_images[$index];
                        return "<p><img src=\"{$src}\" alt=\"Gambar Berita\" style=\"max-width:100%;height:auto;border-radius:14px;margin:16px 0;\"></p>";
                    }
                    return '';
                }, $isi_input);
            } elseif (count($uploaded_images) > 1) {
                // Jika upload banyak gambar tanpa placeholder, sisipkan semua gambar di akhir narasi.
                foreach ($uploaded_images as $img) {
                    $src = 'uploads/berita/' . $img;
                    $isi_final .= "\n<p><img src=\"{$src}\" alt=\"Gambar Berita\" style=\"max-width:100%;height:auto;border-radius:14px;margin:16px 0;\"></p>";
                }
            }

            $isi_db = mysqli_real_escape_string($conn, $isi_final);
            $foto_utama = mysqli_real_escape_string($conn, $uploaded_images[0]);

            $query = "INSERT INTO tb_berita (judul, isi, foto_kegiatan, tanggal_kegiatan) 
                      VALUES ('$judul', '$isi_db', '$foto_utama', '$tanggal')";

            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Berita berhasil ditambahkan!'); window.location='kelola_berita.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan data berita.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manajemen Alumni - Tracer IKPM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f6fafd 0%, #eefaf2 100%);
            font-family: 'Inter', sans-serif;
            color: #334155;
        }
        .navbar { background: rgba(232, 245, 233, 0.9) !important; border-bottom: 1px solid rgba(25,121,72,0.18); height: 64px; z-index: 1100; backdrop-filter: blur(8px); }
        .navbar-brand { color: #197948 !important; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .navbar-brand img { height: 35px; }
        .sidebar { width: 265px; background: #fff; border-right: 1px solid #e4efea; position: fixed; top: 0; bottom: 0; padding-top: 80px; z-index: 1050; transition: 0.3s; box-shadow: 12px 0 28px rgba(15,23,42,0.04); }
        .sidebar-profile { text-align: center; padding: 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 15px; }
        .admin-avatar { width: 70px; height: 70px; border-radius: 50%; border: 2px solid #197948; padding: 2px; object-fit: cover; box-shadow: 0 8px 24px rgba(25,121,72,0.12); }
        .sidebar-link { display: flex; align-items: center; padding: 12px 25px; color: #475569; text-decoration: none; font-weight: 500; transition: 0.2s ease; }
        .sidebar-link:hover, .sidebar-link.active { background: linear-gradient(90deg, rgba(220,248,229,0.95), rgba(220,248,229,0.55)); color: #197948; transform: translateX(4px); }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }
        .main-content { margin-left: 265px; margin-top: 64px; padding: 40px; transition: 0.3s; position: relative; z-index: 1; }
        .panel-shell { background: rgba(255,255,255,0.9); border: 1px solid rgba(255,255,255,0.45); border-radius: 24px; box-shadow: 0 18px 40px rgba(15,23,42,0.08); padding: 24px; backdrop-filter: blur(8px); }
        .card { border-radius: 18px; border: none; box-shadow: 0 10px 24px rgba(0,0,0,0.05); }
        .table thead th { background: #f8fafc; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; padding: 15px; border-bottom: 1px solid #e2e8f0; }
        .avatar-img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; background: #eee; }
        .status-badge { font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; font-weight: 600; }
        .badge-approved { background: #d1e7dd; color: #0f5132; }
        .badge-pending { background: #fff3cd; color: #664d03; }
        .badge-rejected { background: #f8d7da; color: #842029; }
        .btn-success { background: linear-gradient(135deg, #197948, #2eac68); border-color: #197948; border-radius: 14px; box-shadow: 0 10px 22px rgba(25,121,72,0.16); }
        .btn-success:hover { background: linear-gradient(135deg, #155c29, #24995a); border-color: #155c29; transform: translateY(-1px); }
        .btn-light { border-radius: 14px; }
        .page-title { display: flex; justify-content: space-between; align-items: end; gap: 12px; margin-bottom: 18px; }
        .page-title h3, .page-title p { margin-bottom: 0; }
        .form-control, .form-select { border-radius: 14px; border-color: #d8e7dd; }
        .form-control:focus, .form-select:focus { border-color: #197948; box-shadow: 0 0 0 0.2rem rgba(25,121,72,0.16); }
        .hint-box {
            border-radius: 14px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 12px 14px;
            font-size: 0.86rem;
            color: #475569;
            line-height: 1.7;
        }
        @media (max-width: 991px) { .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } .main-content { margin-left: 0; padding: 20px; } .page-title { align-items: flex-start; flex-direction: column; } }
    </style>
</head>
<body>

<nav class="navbar fixed-top">
    <div class="container-fluid px-3">
        <button class="btn d-lg-none text-success" id="hamburgerBtn"><i class="bi bi-list fs-3"></i></button>
        <a class="navbar-brand" href="dashboard_admin.php">
            <img src="../assets/logo-ikpm2.png" alt="Logo">
            <span>Kelola Berita</span>
        </a>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <div class="sidebar-profile">
        <img src="../assets/admin.png" class="admin-avatar" alt="Admin" onerror="this.src='https://via.placeholder.com/70'">
        <div class="fw-bold mt-2 text-success"><?= htmlspecialchars($admin_nama) ?></div>
        <div class="small text-muted">IKPM Sulselbar</div>
    </div>
    <div class="nav-links">
        <a href="dashboard_admin.php" class="sidebar-link <?= ($current_page == 'dashboard_admin.php') ? 'active' : '' ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="data_alumni.php" class="sidebar-link <?= ($current_page == 'data_alumni.php') ? 'active' : '' ?>"><i class="bi bi-people-fill"></i> Data Alumni</a>
        <a href="kelola_berita.php" class="sidebar-link <?= ($current_page == 'kelola_berita.php') ? 'active' : '' ?>"><i class="bi bi-newspaper"></i> Kelola Berita</a>
        <a href="admin_export.php" class="sidebar-link <?= ($current_page == 'admin_export.php') ? 'active' : '' ?>"><i class="bi bi-file-earmark-excel-fill"></i> Export Data</a>
        <hr class="mx-3">
        <a href="logout_admin.php" class="sidebar-link text-danger" onclick="return confirm('Yakin ingin logout?')"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main-content">
    <div class="panel-shell">
    <div class="page-title">
        <div>
            <h3 class="fw-bold">Form Tambah Berita</h3>
            <p class="text-muted">Masukkan narasi dan foto kegiatan dalam tampilan yang lebih rapi.</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="kelola_berita.php" class="text-success">Kelola Berita</a></li>
                <li class="breadcrumb-item active">Tambah Berita</li>
            </ol>
        </nav>
    </div>

    <div class="card p-4">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Berita</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Baksos Marhalah 2020" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Narasi Berita</label>
                        <textarea name="isi" class="form-control" rows="10" placeholder="Contoh: Kegiatan dimulai pukul 08.00. [gambar1] Setelah sesi pembukaan, dilanjutkan diskusi. [gambar2]" required></textarea>
                        <div class="hint-box mt-2">
                            Gunakan format placeholder di narasi: <strong>[gambar1]</strong>, <strong>[gambar2]</strong>, dst.
                            Contoh: Narasi awal [gambar1] narasi lanjutan [gambar2].
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Dokumentasi (Bisa Banyak)</label>
                        <input type="file" name="foto[]" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png" multiple required>
                        <div class="form-text text-muted">Format: JPG/JPEG/PNG, maksimal 2MB per gambar. Gambar pertama jadi foto utama di daftar berita.</div>
                    </div>
                </div>
                <div class="col-12 border-top pt-3">
                    <button type="submit" name="simpan" class="btn btn-success px-5 rounded-pill">
                        <i class="bi bi-send-fill me-2"></i> Publikasikan Berita
                    </button>
                    <a href="kelola_berita.php" class="btn btn-light px-4 rounded-pill ms-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>

</body>
</html>
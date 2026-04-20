<?php
session_start();
include 'config/config.php';

// Force set PHP upload limits
@ini_set('upload_max_filesize', '10M');
@ini_set('post_max_size', '12M');
@ini_set('max_execution_time', '300');

// Cek login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

$id_alumni = $_SESSION['id_alumni'];

// Ambil data alumni (tb_alumni)
$query = "SELECT * FROM tb_alumni WHERE id_alumni = '$id_alumni'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Data alumni tidak ditemukan: " . mysqli_error($conn));
}
$data = mysqli_fetch_assoc($result);

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap    = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $stambuk         = mysqli_real_escape_string($conn, $_POST['stambuk']);
    $marhalah        = mysqli_real_escape_string($conn, $_POST['marhalah']);
    $konsulat        = mysqli_real_escape_string($conn, $_POST['konsulat']);
    $tahun_lulus     = mysqli_real_escape_string($conn, $_POST['tahun_lulus']);
    $email           = mysqli_real_escape_string($conn, $_POST['email']);
    $no_hp           = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat_sekarang = mysqli_real_escape_string($conn, $_POST['alamat_sekarang']);
    
    $foto_path = $data['foto']; 
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            if (in_array($_FILES['foto']['type'], $allowed_types)) {
                $upload_dir = 'uploads/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
                
                $file_extension = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                $new_filename = 'foto_' . $id_alumni . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
                    if (!empty($data['foto']) && file_exists($data['foto']) && strpos($data['foto'], 'placeholder') === false) {
                        @unlink($data['foto']);
                    }
                    $foto_path = $upload_path;
                }
            } else {
                $error_msg = "Format file tidak valid (Hanya JPG/PNG).";
            }
        }
    }

    if (!isset($error_msg)) {
        $update = "UPDATE tb_alumni SET 
            stambuk='$stambuk',
            nama_lengkap='$nama_lengkap',
            marhalah='$marhalah',
            konsulat='$konsulat',
            tahun_lulus='$tahun_lulus',
            email='$email',
            no_hp='$no_hp',
            alamat_sekarang='$alamat_sekarang',
            foto='$foto_path'
            WHERE id_alumni='$id_alumni'";

        if (mysqli_query($conn, $update)) {
            header("Location: profil.php?success=update");
            exit();
        } else {
            $error_msg = "Gagal update: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Profil Pribadi - Tracer Study IKPM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f6fafd; min-height: 100vh; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        
        /* Layout CSS (Identik dengan Dashboard) */
        .navbar { background: #e8f5e9 !important; border-bottom: 2px solid #197948; z-index: 1051; position: fixed; top: 0; width: 100%; }
        .navbar-brand { color: #197948 !important; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .navbar-brand img { height: 35px; }

        .sidebar { width: 265px; background: #fff; border-right: 1px solid #e4efea; position: fixed; top: 0; bottom: 0; padding-top: 80px; z-index: 1040; transition: 0.3s; }
        .profile-box { text-align: center; padding: 20px; border-bottom: 1px solid #eee; margin-bottom: 15px; }
        .profile-img-sidebar { width: 85px; height: 85px; object-fit: cover; border-radius: 50%; border: 3px solid #197948; }
        .profile-name { font-size: 1.1rem; font-weight: 700; color: #197948; margin-top: 10px; }
        .profile-desc { font-size: 0.85rem; color: #666; }

        .sidebar-link { display: flex; align-items: center; padding: 12px 25px; color: #444; text-decoration: none; transition: 0.2s; font-weight: 500; }
        .sidebar-link:hover, .sidebar-link.active { background: #dcf8e5; color: #197948; }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }

        .main-content { margin-left: 265px; margin-top: 64px; padding: 40px; transition: 0.3s; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        .foto-preview-container { text-align: center; margin-bottom: 20px; }
        .foto-preview { width: 150px; height: 150px; object-fit: cover; border-radius: 15px; border: 3px solid #e8f5e9; margin-bottom: 15px; }

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
            <span>Tracer Alumni IKPM</span>
        </a>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <div class="profile-box">
        <?php $foto = (!empty($data['foto']) && file_exists($data['foto'])) ? $data['foto'] : 'assets/profile_placeholder.jpg'; ?>
        <img src="<?= $foto ?>" class="profile-img-sidebar">
        <div class="profile-name"><?= htmlspecialchars(explode(' ', $data['nama_lengkap'])[0]) ?></div>
        <div class="profile-desc">Marhalah <?= htmlspecialchars($data['marhalah']) ?></div>
        <div class="profile-desc text-muted small">Stambuk: <?= htmlspecialchars($data['stambuk']) ?></div>
    </div>
    
    <div class="nav-links">
        <a href="dashboard_alumni.php" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="profil.php" class="sidebar-link active"><i class="bi bi-person-circle"></i> Profil Pribadi</a>
        <a href="pekerjaan.php" class="sidebar-link"><i class="bi bi-briefcase-fill"></i> Aktivitas/Khidmah</a>
        <a href="kuesioner.php" class="sidebar-link"><i class="bi bi-file-earmark-text-fill"></i> Kuesioner</a>
        <hr class="mx-3">
        <a href="logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="mb-4">
        <h3 class="fw-bold">Identitas Diri</h3>
        <p class="text-muted">Pastikan data profil Anda selalu akurat dan terbaru.</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4"><i class="bi bi-check-circle-fill me-2"></i>Data profil berhasil diperbarui!</div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card p-4 h-100">
                <h6 class="fw-bold text-success mb-4">FOTO PROFIL</h6>
                <div class="foto-preview-container">
                    <img src="<?= $foto ?>" class="foto-preview">
                    <form method="POST" enctype="multipart/form-data" id="form-foto">
                        <input type="hidden" name="nama_lengkap" value="<?= $data['nama_lengkap'] ?>">
                        <input type="hidden" name="stambuk" value="<?= $data['stambuk'] ?>">
                        <input type="hidden" name="marhalah" value="<?= $data['marhalah'] ?>">
                        <input type="hidden" name="konsulat" value="<?= $data['konsulat'] ?>">
                        <input type="hidden" name="tahun_lulus" value="<?= $data['tahun_lulus'] ?>">
                        <input type="hidden" name="email" value="<?= $data['email'] ?>">
                        <input type="hidden" name="no_hp" value="<?= $data['no_hp'] ?>">
                        <input type="hidden" name="alamat_sekarang" value="<?= $data['alamat_sekarang'] ?>">

                        <label for="foto" class="btn btn-outline-success btn-sm w-100">Ganti Foto Identitas</label>
                        <input type="file" id="foto" name="foto" hidden onchange="document.getElementById('form-foto').submit()">
                        <small class="text-muted d-block mt-2">Format: JPG/PNG, Maks. 2MB</small>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card p-4">
                <h6 class="fw-bold text-success mb-4">DATA LENGKAP</h6>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Nomor Stambuk / NIW</label>
                            <input type="text" class="form-control bg-light" name="stambuk" value="<?= htmlspecialchars($data['stambuk']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Marhalah (Angkatan)</label>
                            <input type="text" class="form-control" name="marhalah" value="<?= htmlspecialchars($data['marhalah']) ?>" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" value="<?= htmlspecialchars($data['nama_lengkap']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Konsulat Asal</label>
                            <input type="text" class="form-control" name="konsulat" value="<?= htmlspecialchars($data['konsulat']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Tahun Lulus Gontor</label>
                            <input type="number" class="form-control" name="tahun_lulus" value="<?= htmlspecialchars($data['tahun_lulus']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Email Aktif</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">No. HP / WhatsApp</label>
                            <input type="text" class="form-control" name="no_hp" value="<?= htmlspecialchars($data['no_hp']) ?>" required>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="form-label small fw-bold">Alamat Domisili Sekarang</label>
                            <textarea class="form-control" name="alamat_sekarang" rows="3"><?= htmlspecialchars($data['alamat_sekarang']) ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 border-top pt-4">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
                        <a href="dashboard_alumni.php" class="btn btn-light px-4">Batal</a>
                    </div>
                </form>
            </div>
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
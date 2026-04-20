<?php
session_start();
include 'config/config.php';

// Proteksi Halaman
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

$id_alumni = $_SESSION['id_alumni'];

// 1. Ambil data alumni (untuk sidebar & profil)
$query_alumni = mysqli_query($conn, "SELECT * FROM tb_alumni WHERE id_alumni = '$id_alumni'");
$data = mysqli_fetch_assoc($query_alumni);

// 2. Cek apakah sudah pernah mengisi kuesioner
$cek_kuesioner = mysqli_query($conn, "SELECT * FROM tb_kuesioner WHERE id_alumni = '$id_alumni' LIMIT 1");
$sudah_isi = mysqli_num_rows($cek_kuesioner) > 0;
// $data_kuesioner = $sudah_isi ? mysqli_fetch_assoc($cek_kuesioner) : null; <--- MATIKAN INI
$data_kuesioner = null; // Tambahkan ini agar form selalu kosong

// Catatan: Proses simpan dipindah ke proses_kuesioner.php sesuai form action Anda
// Namun logika variabel di bawah disesuaikan dengan kolom SQL terbaru Anda.

$title = "Kuesioner Alumni - Tracer Study IKPM";
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
        .form-label { font-weight: 600; color: #197948; margin-bottom: 8px; }
        .status-badge { font-size: 0.85rem; padding: 6px 15px; border-radius: 20px; }

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
        <img src="<?= $foto ?>" class="profile-img">
        <div class="profile-name"><?= htmlspecialchars(explode(' ', $data['nama_lengkap'])[0]) ?></div>
        <div class="profile-desc">Marhalah <?= htmlspecialchars($data['marhalah']) ?></div>
        <div class="profile-desc text-muted small">Stambuk: <?= htmlspecialchars($data['stambuk']) ?></div>
    </div>
    
    <div class="nav-links">
        <a href="dashboard_alumni.php" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="profil.php" class="sidebar-link"><i class="bi bi-person-circle"></i> Profil Pribadi</a>
        <a href="pekerjaan.php" class="sidebar-link"><i class="bi bi-briefcase-fill"></i> Aktivitas/Khidmah</a>
        <a href="kuesioner.php" class="sidebar-link active"><i class="bi bi-file-earmark-text-fill"></i> Kuesioner</a>
        <hr class="mx-3">
        <a href="logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Evaluasi & Kuesioner</h3>
            <p class="text-muted">Masukan Anda sangat berarti untuk kemajuan organisasi.</p>
        </div>
        <?php if($sudah_isi): ?>
            <span class="badge bg-success-subtle text-success status-badge border border-success">
                <i class="bi bi-check-circle-fill me-1"></i> Sudah Diisi
            </span>
        <?php else: ?>
            <span class="badge bg-warning-subtle text-warning status-badge border border-warning">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum Diisi
            </span>
        <?php endif; ?>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Jazakumullah Khairan! Data kuesioner berhasil disimpan.
        </div>
    <?php endif; ?>

    <div class="card p-4">
        <form action="proses_kuesioner.php" method="POST">
            <div class="row g-4">
                <div class="col-md-12">
                    <label class="form-label">Sejauh mana IKPM memberikan kontribusi bagi perkembangan alumni?</label>
                    <select name="kontribusi_ikpm" class="form-select shadow-sm" required>
                        <option value="">-- Pilih Penilaian (Skala 1-5) --</option>
                        <?php
                        $skala = [5=>"Sangat Besar", 4=>"Besar", 3=>"Cukup", 2=>"Kecil", 1=>"Sangat Kecil"];
                        foreach($skala as $k => $v) {
                            $sel = ($data_kuesioner && $data_kuesioner['kontribusi_ikpm'] == $k) ? 'selected' : '';
                            echo "<option value='$k' $sel>$v</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Apakah nilai-nilai Pondok masih dirasakan manfaatnya dalam kehidupan saat ini?</label>
                    <select name="manfaat_pondok" class="form-select shadow-sm" required>
                        <option value="">-- Pilih Penilaian (Skala 1-5) --</option>
                        <?php
                        foreach($skala as $k => $v) {
                            $sel = ($data_kuesioner && $data_kuesioner['manfaat_pondok'] == $k) ? 'selected' : '';
                            echo "<option value='$k' $sel>$v</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Seberapa aktif Anda mengikuti kegiatan yang diadakan IKPM?</label>
                    <select name="aktif_kegiatan" class="form-select shadow-sm" required>
                        <option value="">-- Pilih Penilaian (Skala 1-5) --</option>
                        <?php
                        $skala_aktif = [5=>"Sangat Aktif", 4=>"Aktif", 3=>"Cukup Aktif", 2=>"Jarang", 1=>"Tidak Pernah"];
                        foreach($skala_aktif as $k => $v) {
                            $sel = ($data_kuesioner && $data_kuesioner['aktif_kegiatan'] == $k) ? 'selected' : '';
                            echo "<option value='$k' $sel>$v</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">Saran untuk Perbaikan IKPM / Almamater</label>
                    <textarea name="saran_perbaikan" class="form-control shadow-sm" rows="5" placeholder="Tuliskan masukan konstruktif Anda di sini..."><?= $data_kuesioner ? htmlspecialchars($data_kuesioner['saran_perbaikan']) : '' ?></textarea>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-success px-5 py-2 fw-bold shadow-sm">
                    <i class="bi bi-send-fill me-2"></i><?= $sudah_isi ? 'Perbarui Jawaban' : 'Kirim Kuesioner' ?>
                </button>
                <a href="dashboard_alumni.php" class="btn btn-light px-4 py-2 border shadow-sm">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    const btn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    btn.onclick = () => sidebar.classList.toggle('active');
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
session_start();
include 'config/config.php';

// Cek login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

$id_alumni = $_SESSION['id_alumni'];

// Ambil data alumni untuk sidebar
$query_alumni = mysqli_query($conn, "SELECT * FROM tb_alumni WHERE id_alumni = '$id_alumni'");
$data_alumni = mysqli_fetch_assoc($query_alumni);

// Cek apakah sudah pernah mengisi data pekerjaan (tb_pekerjaan)
$cek_pekerjaan = mysqli_query($conn, "SELECT * FROM tb_pekerjaan WHERE id_alumni = '$id_alumni'");
$sudah_isi = mysqli_num_rows($cek_pekerjaan) > 0;
$data_pekerjaan = $sudah_isi ? mysqli_fetch_assoc($cek_pekerjaan) : null;

// Proses simpan/update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status_aktivitas = mysqli_real_escape_string($conn, $_POST['status_aktivitas']);
    $nama_instansi    = mysqli_real_escape_string($conn, $_POST['nama_instansi']);
    $jabatan          = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $bidang_khidmah   = mysqli_real_escape_string($conn, $_POST['bidang_khidmah']);
    $tahun_mulai      = !empty($_POST['tahun_mulai']) ? (int)$_POST['tahun_mulai'] : "NULL";
    $domisili_kerja   = mysqli_real_escape_string($conn, $_POST['domisili_kerja']);
    $relevansi_ilmu   = mysqli_real_escape_string($conn, $_POST['relevansi_ilmu']);

    if ($sudah_isi) {
        $query = "UPDATE tb_pekerjaan SET 
            status_aktivitas = '$status_aktivitas',
            nama_instansi = '$nama_instansi',
            jabatan = '$jabatan',
            bidang_khidmah = '$bidang_khidmah',
            tahun_mulai = $tahun_mulai,
            domisili_kerja = '$domisili_kerja',
            relevansi_ilmu = '$relevansi_ilmu'
            WHERE id_alumni = '$id_alumni'";
    } else {
        $query = "INSERT INTO tb_pekerjaan 
            (id_alumni, status_aktivitas, nama_instansi, jabatan, bidang_khidmah, tahun_mulai, domisili_kerja, relevansi_ilmu) 
            VALUES 
            ('$id_alumni', '$status_aktivitas', '$nama_instansi', '$jabatan', '$bidang_khidmah', $tahun_mulai, '$domisili_kerja', '$relevansi_ilmu')";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: pekerjaan.php?success=1");
        exit();
    } else {
        $error_msg = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Aktivitas & Khidmah - Tracer Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f6fafd; min-height: 100vh; font-family: 'Inter', sans-serif; }
        
        /* Layout CSS (Konsisten dengan Profil/Dashboard) */
        .navbar { background: #e8f5e9 !important; border-bottom: 2px solid #197948; z-index: 1051; position: fixed; top: 0; width: 100%; }
        .navbar-brand { color: #197948 !important; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        
        .sidebar { width: 265px; background: #fff; border-right: 1px solid #e4efea; position: fixed; top: 0; bottom: 0; padding-top: 80px; z-index: 1040; transition: 0.3s; }
        .profile-box { text-align: center; padding: 20px; border-bottom: 1px solid #eee; margin-bottom: 15px; }
        .profile-img-sidebar { width: 85px; height: 85px; object-fit: cover; border-radius: 50%; border: 3px solid #197948; }
        
        .sidebar-link { display: flex; align-items: center; padding: 12px 25px; color: #444; text-decoration: none; transition: 0.2s; font-weight: 500; }
        .sidebar-link:hover, .sidebar-link.active { background: #dcf8e5; color: #197948; }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }

        .main-content { margin-left: 265px; margin-top: 64px; padding: 40px; transition: 0.3s; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        .form-label { font-weight: 600; color: #197948; font-size: 0.9rem; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }

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
            <img src="assets/logo-ikpm2.png" alt="Logo" style="height: 35px;">
            <span>Tracer Alumni IKPM</span>
        </a>
    </div>
</nav>

<div class="sidebar" id="sidebar">
    <div class="profile-box">
        <?php $foto = (!empty($data_alumni['foto']) && file_exists($data_alumni['foto'])) ? $data_alumni['foto'] : 'assets/profile_placeholder.jpg'; ?>
        <img src="<?= $foto ?>" class="profile-img-sidebar">
        <div class="fw-bold text-success mt-2"><?= htmlspecialchars(explode(' ', $data_alumni['nama_lengkap'])[0]) ?></div>
        <div class="small text-muted">Marhalah <?= htmlspecialchars($data_alumni['marhalah']) ?></div>
    </div>
    
    <div class="nav-links">
        <a href="dashboard_alumni.php" class="sidebar-link"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
        <a href="profil.php" class="sidebar-link"><i class="bi bi-person-circle"></i> Profil Pribadi</a>
        <a href="pekerjaan.php" class="sidebar-link active"><i class="bi bi-briefcase-fill"></i> Aktivitas/Khidmah</a>
        <a href="kuesioner.php" class="sidebar-link"><i class="bi bi-file-earmark-text-fill"></i> Kuesioner</a>
        <hr class="mx-3">
        <a href="logout.php" class="sidebar-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Aktivitas & Khidmah</h3>
            <p class="text-muted small">Update data kesibukan, pekerjaan, atau studi lanjut Anda.</p>
        </div>
        <?php if ($sudah_isi): ?>
            <span class="status-badge bg-success text-white"><i class="bi bi-check-circle me-1"></i> Terdata</span>
        <?php else: ?>
            <span class="status-badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Belum Isi</span>
        <?php endif; ?>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4">Data aktivitas berhasil diperbarui!</div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Aktivitas Saat Ini</label>
                    <select name="status_aktivitas" class="form-select" id="status_aktivitas" required>
                        <option value="">-- Pilih Status --</option>
                        <?php 
                        $statuses = ['Bekerja', 'Wirausaha', 'Melanjutkan Studi', 'Pengabdian', 'Belum Bekerja'];
                        foreach($statuses as $st):
                            $sel = ($data_pekerjaan && $data_pekerjaan['status_aktivitas'] == $st) ? 'selected' : '';
                            echo "<option value='$st' $sel>$st</option>";
                        endforeach;
                        ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Mulai Aktivitas</label>
                    <input type="number" name="tahun_mulai" class="form-control" placeholder="Contoh: 2023" value="<?= $data_pekerjaan['tahun_mulai'] ?? '' ?>">
                </div>
            </div>

            <div id="extra-fields" style="<?= ($data_pekerjaan && $data_pekerjaan['status_aktivitas'] != 'Belum Bekerja') ? '' : 'display:none;' ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Instansi / Kantor / Pondok / Kampus</label>
                        <input type="text" name="nama_instansi" class="form-control" placeholder="Masukkan nama tempat beraktivitas" value="<?= htmlspecialchars($data_pekerjaan['nama_instansi'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan / Profesi</label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Guru, Manager, Mahasiswa" value="<?= htmlspecialchars($data_pekerjaan['jabatan'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Bidang Khidmah / Usaha</label>
                        <input type="text" name="bidang_khidmah" class="form-control" placeholder="Contoh: Pendidikan, Dakwah, IT, Kuliner" value="<?= htmlspecialchars($data_pekerjaan['bidang_khidmah'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Domisili Kerja / Studi</label>
                        <input type="text" name="domisili_kerja" class="form-control" placeholder="Kota tempat beraktivitas" value="<?= htmlspecialchars($data_pekerjaan['domisili_kerja'] ?? '') ?>">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Relevansi Bidang dengan Nilai Kepondokan</label>
                        <select name="relevansi_ilmu" class="form-select">
                            <option value="">-- Pilih Relevansi --</option>
                            <?php 
                            $relevansi = ['Sangat Relevan', 'Relevan', 'Cukup Relevan', 'Tidak Relevan'];
                            foreach($relevansi as $rel):
                                $sel = ($data_pekerjaan && $data_pekerjaan['relevansi_ilmu'] == $rel) ? 'selected' : '';
                                echo "<option value='$rel' $sel>$rel</option>";
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 border-top pt-4 mt-2">
                <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
                <a href="dashboard_alumni.php" class="btn btn-light px-4">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle fields berdasarkan status
    document.getElementById('status_aktivitas').addEventListener('change', function() {
        const fields = document.getElementById('extra-fields');
        if (this.value === 'Belum Bekerja' || this.value === '') {
            fields.style.display = 'none';
        } else {
            fields.style.display = 'block';
        }
    });

    // Hamburger Menu Mobile
    const btn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    btn.onclick = () => sidebar.classList.toggle('active');
</script>
</body>
</html>
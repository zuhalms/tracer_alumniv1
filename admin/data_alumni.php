<?php
session_start();
include '../config/config.php'; 

// Proteksi Admin (Gunakan is_admin agar sinkron dengan login)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login_admin.php?error=not_logged_in");
    exit();
}

// 1. Ambil Filter Tahun Lulus dari URL
$tahunLulus = isset($_GET['tahun_lulus']) ? intval($_GET['tahun_lulus']) : '';

// 2. Bangun Query Utama
// Ditambahkan subquery untuk p agar tidak terjadi duplikasi row jika alumni punya >1 data pekerjaan
$whereClause = "";
if ($tahunLulus) {
    $whereClause = "WHERE a.tahun_lulus = '$tahunLulus'";
}

$query = "
SELECT 
    a.*, 
    (SELECT status_aktivitas FROM tb_pekerjaan p WHERE p.id_alumni = a.id_alumni ORDER BY id_pekerjaan DESC LIMIT 1) as status_aktivitas,
    (SELECT COUNT(*) FROM tb_kuesioner k WHERE k.id_alumni = a.id_alumni) as isi_kuesioner
FROM tb_alumni a
$whereClause
ORDER BY CASE WHEN a.status_verifikasi = 'Pending' THEN 1 ELSE 2 END, a.nama_lengkap ASC
";
$result = mysqli_query($conn, $query);

// 3. Hitung Statistik Cepat
$count_all = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_alumni " . ($tahunLulus ? "WHERE tahun_lulus = '$tahunLulus'" : ""));
$total_data = mysqli_fetch_assoc($count_all)['total'] ?? 0;

$count_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_alumni WHERE status_verifikasi = 'Pending' " . ($tahunLulus ? "AND tahun_lulus = '$tahunLulus'" : ""));
$total_pending = mysqli_fetch_assoc($count_pending)['total'] ?? 0;

$current_page = basename($_SERVER['PHP_SELF']);
$admin_nama = $_SESSION['admin_nama'] ?? 'Administrator';
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
        body { background: #f6fafd; font-family: 'Inter', sans-serif; color: #334155; }
        .navbar { background: #e8f5e9 !important; border-bottom: 2px solid #197948; height: 64px; z-index: 1100; }
        .navbar-brand { color: #197948 !important; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .navbar-brand img { height: 35px; }
        .sidebar { width: 265px; background: #fff; border-right: 1px solid #e4efea; position: fixed; top: 0; bottom: 0; padding-top: 80px; z-index: 1050; transition: 0.3s; }
        .sidebar-profile { text-align: center; padding: 20px; border-bottom: 1px solid #f1f5f9; margin-bottom: 15px; }
        .admin-avatar { width: 70px; height: 70px; border-radius: 50%; border: 2px solid #197948; padding: 2px; object-fit: cover; }
        .sidebar-link { display: flex; align-items: center; padding: 12px 25px; color: #475569; text-decoration: none; font-weight: 500; transition: 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: #dcf8e5; color: #197948; }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }
        .main-content { margin-left: 265px; margin-top: 64px; padding: 40px; transition: 0.3s; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .table thead th { background: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; padding: 15px; border-bottom: 1px solid #e2e8f0; }
        .avatar-img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; background: #eee; }
        .status-badge { font-size: 0.75rem; padding: 5px 12px; border-radius: 20px; font-weight: 600; }
        .badge-approved { background: #d1e7dd; color: #0f5132; }
        .badge-pending { background: #fff3cd; color: #664d03; }
        .badge-rejected { background: #f8d7da; color: #842029; }
        @media (max-width: 991px) { .sidebar { transform: translateX(-100%); } .sidebar.active { transform: translateX(0); } .main-content { margin-left: 0; padding: 20px; } }
    </style>
</head>
<body>

<nav class="navbar fixed-top">
    <div class="container-fluid px-3">
        <button class="btn d-lg-none text-success" id="hamburgerBtn"><i class="bi bi-list fs-3"></i></button>
        <a class="navbar-brand" href="dashboard_admin.php">
            <img src="../assets/logo-ikpm2.png" alt="Logo">
            <span>Management Data Alumni</span>
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
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
        <div>
            <h3 class="fw-bold m-0">Database Alumni</h3>
            <p class="text-muted m-0">Total <strong><?= $total_data ?></strong> Alumni terdaftar.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <?php if($total_pending > 0): ?>
                <div class="alert alert-warning py-2 px-3 m-0 small fw-bold shadow-sm border-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $total_pending ?> Menunggu Verifikasi
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <form method="GET" class="row g-3 align-items-center">
            <div class="col-md-auto">
                <label class="fw-bold text-secondary small">ANGKATAN:</label>
            </div>
            <div class="col-md-3">
                <select name="tahun_lulus" class="form-select border-0 bg-light shadow-none">
                    <option value="">-- Semua Angkatan --</option>
                    <?php
                    $resTahun = mysqli_query($conn, "SELECT DISTINCT tahun_lulus FROM tb_alumni WHERE tahun_lulus != 0 ORDER BY tahun_lulus DESC");
                    while($t = mysqli_fetch_assoc($resTahun)) {
                        $selected = ($tahunLulus == $t['tahun_lulus']) ? 'selected' : '';
                        echo "<option value='{$t['tahun_lulus']}' $selected>{$t['tahun_lulus']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-success px-4">Filter</button>
                <?php if($tahunLulus): ?>
                    <a href="data_alumni.php" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Alumni</th>
                        <th>Detail</th>
                        <th>Lulus</th>
                        <th>Status</th>
                        <th>Tracer</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)): 
                            $badgeColor = ($row['status_verifikasi'] == 'Approved') ? 'badge-approved' : (($row['status_verifikasi'] == 'Pending') ? 'badge-pending' : 'badge-rejected');
                    ?>
                    <tr>
                        <td class="ps-4 text-muted small"><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php $foto = !empty($row['foto']) ? '../'.$row['foto'] : '../assets/profile_placeholder.jpg'; ?>
                                <img src="<?= $foto ?>" class="avatar-img me-3" onerror="this.src='../assets/profile_placeholder.jpg'">
                                <div>
                                    <div class="fw-bold text-dark small mb-0"><?= htmlspecialchars($row['nama_lengkap']) ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem;"><?= $row['stambuk'] ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-medium"><?= htmlspecialchars($row['marhalah']) ?></div>
                            <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($row['konsulat']) ?></div>
                        </td>
                        <td><span class="badge bg-light text-dark border fw-normal"><?= $row['tahun_lulus'] ?></span></td>
                        <td><span class="status-badge <?= $badgeColor ?>"><?= $row['status_verifikasi'] ?></span></td>
                        <td>
                            <div class="d-flex gap-2">
                                <i class="bi bi-briefcase-fill <?= $row['status_aktivitas'] ? 'text-primary' : 'text-light' ?>" title="<?= $row['status_aktivitas'] ?? 'Belum isi pekerjaan' ?>"></i>
                                <i class="bi bi-file-earmark-check-fill <?= $row['isi_kuesioner'] > 0 ? 'text-success' : 'text-light' ?>" title="<?= $row['isi_kuesioner'] > 0 ? 'Kuesioner Terisi' : 'Belum isi kuesioner' ?>"></i>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="detail_alumni.php?id=<?= $row['id_alumni'] ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-eye"></i></a>
                                <?php if($row['status_verifikasi'] == 'Pending'): ?>
                                    <a href="proses_verifikasi.php?id=<?= $row['id_alumni'] ?>&action=approve" class="btn btn-success btn-sm" onclick="return confirm('Setujui alumni ini?')"><i class="bi bi-check-lg"></i></a>
                                <?php endif; ?>
                                <a href="hapus_alumni.php?id=<?= $row['id_alumni'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus permanen data ini?')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Data tidak ditemukan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const btn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    btn.onclick = (e) => { e.stopPropagation(); sidebar.classList.toggle('active'); };
    document.onclick = (e) => { if (!sidebar.contains(e.target) && !btn.contains(e.target)) sidebar.classList.remove('active'); };
</script>
</body>
</html>
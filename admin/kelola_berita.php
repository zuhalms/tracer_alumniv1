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
$current_page = basename($_SERVER['PHP_SELF']);

// Ambil Data Berita
$query_berita = mysqli_query($conn, "SELECT * FROM tb_berita ORDER BY id_berita DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="icon" type="image/png" href="../assets/logo-ikpm2.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kelola Berita - Admin Tracer IKPM</title>
    
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
        .admin-avatar { width: 80px; height: 80px; border-radius: 50%; border: 3px solid #197948; padding: 3px; object-fit: cover; }
        
        .sidebar-link { display: flex; align-items: center; padding: 12px 25px; color: #475569; text-decoration: none; font-weight: 500; transition: 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: #dcf8e5; color: #197948; }
        .sidebar-link i { margin-right: 12px; font-size: 1.2rem; }

        .main-content { margin-left: 265px; margin-top: 64px; padding: 40px; }
        .card { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
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
        <img src="../assets/admin.png" class="admin-avatar" onerror="this.src='https://via.placeholder.com/80'">
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">Manajemen Berita</h3>
            <p class="text-muted">Kelola narasi dan foto kegiatan alumni.</p>
        </div>
        <a href="tambah_berita.php" class="btn btn-success px-4 rounded-pill">
            <i class="bi bi-plus-lg me-2"></i> Tambah Berita
        </a>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Foto</th>
                        <th>Judul Kegiatan</th>
                        <th>Tanggal</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($query_berita)) : 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <img src="../uploads/berita/<?= $row['foto_kegiatan'] ?>" class="rounded" width="80" height="50" style="object-fit: cover;">
                        </td>
                        <td class="fw-bold"><?= $row['judul'] ?></td>
                        <td><?= date('d M Y', strtotime($row['tanggal_kegiatan'])) ?></td>
                        <td>
                            <a href="edit_berita.php?id=<?= $row['id_berita']; ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <a href="hapus_berita.php?id=<?= $row['id_berita'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Hapus berita ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; if(mysqli_num_rows($query_berita) == 0) echo "<tr><td colspan='5' class='text-center py-4 text-muted'>Belum ada berita.</td></tr>"; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const btn = document.getElementById('hamburgerBtn');
    const sidebar = document.getElementById('sidebar');
    btn.onclick = (e) => { e.stopPropagation(); sidebar.classList.toggle('active'); };
    document.onclick = (e) => { if (!sidebar.contains(e.target) && !btn.contains(e.target)) sidebar.classList.remove('active'); };
</script>

</body>
</html>
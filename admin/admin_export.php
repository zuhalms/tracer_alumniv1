<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login_admin.php'); exit();
}
include '../config/config.php';

// 1. Ambil tahun unik untuk filter
$tahunQuery = mysqli_query($conn, "SELECT DISTINCT tahun_lulus FROM tb_alumni WHERE tahun_lulus IS NOT NULL AND tahun_lulus != '' ORDER BY tahun_lulus DESC");
$tahunOptions = [];
if ($tahunQuery) {
    while ($row = mysqli_fetch_assoc($tahunQuery)) {
        $tahunOptions[] = $row['tahun_lulus'];
    }
}

$tahunLulus = isset($_GET['tahun_lulus']) ? mysqli_real_escape_string($conn, $_GET['tahun_lulus']) : '';
$where = $tahunLulus ? "WHERE a.tahun_lulus = '$tahunLulus'" : "";

// 2. Query Data Gabungan
$query = "
    SELECT 
        a.*, 
        p.status_aktivitas, p.nama_instansi,
        k.id_kuesioner
    FROM tb_alumni a
    LEFT JOIN (
        SELECT * FROM tb_pekerjaan WHERE id_pekerjaan IN (SELECT MAX(id_pekerjaan) FROM tb_pekerjaan GROUP BY id_alumni)
    ) p ON p.id_alumni = a.id_alumni
    LEFT JOIN tb_kuesioner k ON k.id_alumni = a.id_alumni
    $where
    ORDER BY a.nama_lengkap ASC
";

$result = mysqli_query($conn, $query);
$total_data = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Data Alumni - IKPM</title>
    
    <link rel="icon" type="image/png" href="../assets/logo-ikpm2.png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f6fafd 0%, #eefaf2 100%);
            font-family: 'Inter', sans-serif;
            color: #334155;
            overflow-x: hidden;
            position: relative;
        }
        body::before,
        body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.2;
            filter: blur(8px);
        }
        body::before {
            width: 320px;
            height: 320px;
            top: -100px;
            right: -100px;
            background: radial-gradient(circle, rgba(25,121,72,0.20) 0%, rgba(25,121,72,0) 72%);
        }
        body::after {
            width: 240px;
            height: 240px;
            bottom: -80px;
            left: -60px;
            background: radial-gradient(circle, rgba(46,172,104,0.18) 0%, rgba(46,172,104,0) 70%);
        }
        .main-box {
            background: rgba(255,255,255,0.92);
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15,23,42,0.08);
            padding: 30px;
            margin-top: 30px;
            border: 1px solid rgba(255,255,255,0.5);
            backdrop-filter: blur(8px);
            position: relative;
            overflow: hidden;
            z-index: 1;
            animation: fadeUp 0.55s ease;
        }
        .main-box::before { content: ''; position: absolute; top: -70px; right: -90px; width: 220px; height: 220px; border-radius: 50%; background: radial-gradient(circle, rgba(25,121,72,0.10) 0%, rgba(25,121,72,0) 72%); }
        .main-box::after { content: ''; position: absolute; left: 24px; top: 0; width: 120px; height: 4px; background: linear-gradient(90deg, #197948, #2eac68); border-radius: 0 0 999px 999px; }
        .header-report { display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 30px; border-bottom: 2px solid #197948; padding-bottom: 20px; position: relative; }
        .header-report img { width: 70px; height: auto; }
        .table-responsive { max-height: 500px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: inset 0 1px 0 rgba(255,255,255,0.5); background: #fff; }
        .table thead th { position: sticky; top: 0; background: #1e293b; color: #fff; z-index: 5; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.03em; }
        .table tbody tr { transition: background 0.2s ease; }
        .table tbody tr:nth-child(even) { background: #fbfdfb; }
        .table tbody tr:hover { background: #f2faf4; }
        .btn-success { background: linear-gradient(135deg, #197948, #2eac68); border-color: #197948; border-radius: 14px; box-shadow: 0 8px 20px rgba(25,121,72,0.14); }
        .btn-success:hover { background: linear-gradient(135deg, #155c29, #24995a); border-color: #155c29; transform: translateY(-1px); }
        .btn-primary { border-radius: 14px; box-shadow: 0 8px 20px rgba(13,110,253,0.12); }
        .btn-primary:hover { transform: translateY(-1px); }
        .btn-outline-secondary { border-radius: 14px; }
        .back-btn {
            border-radius: 999px;
            border: 1px solid rgba(25,121,72,0.28);
            color: #197948;
            background: #fff;
            font-weight: 700;
            padding: 8px 16px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .back-btn:hover {
            color: #fff;
            background: linear-gradient(135deg, #197948, #2eac68);
            border-color: #197948;
            box-shadow: 0 10px 22px rgba(25,121,72,0.18);
            transform: translateY(-1px);
        }
        .card.card-body { border-radius: 18px; }
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }
        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: #f1fbf4;
            color: #197948;
            font-weight: 700;
        }
        .filter-card {
            border-radius: 18px !important;
            border: 1px solid #e2e8f0 !important;
            background: rgba(248, 250, 252, 0.92) !important;
        }
        .report-title {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .report-sub {
            margin-top: 6px;
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .form-select {
            border-radius: 12px;
            border-color: #d8e7dd;
        }
        .form-select:focus {
            border-color: #197948;
            box-shadow: 0 0 0 0.2rem rgba(25,121,72,0.15);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 767.98px) {
            .main-box { padding: 18px; border-radius: 18px; }
            .header-report { flex-direction: column; text-align: center; }
            .header-actions { flex-direction: column; align-items: flex-start; }
            .report-title { align-items: center; }
            .back-btn { width: 100%; justify-content: center; }
            .stat-pill { width: 100%; justify-content: center; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>
<body>

<div class="container mb-5">
    <div class="main-box">
        
        <div class="header-report">
            <img src="../assets/logo-ikpm2.png" alt="Logo IKPM">
            <div class="report-title">
                <h4 class="fw-bold text-success mb-0">LAPORAN TRACER STUDY ALUMNI</h4>
                <p class="mb-0 text-muted">Ikatan Keluarga Pondok Modern (IKPM) Gontor</p>
                <div class="report-sub">Tampilan data ekspor alumni dengan filter angkatan dan unduh CSV</div>
            </div>
        </div>

        <div class="header-actions">
            <a href="dashboard_admin.php" class="back-btn">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
            <span class="stat-pill"><i class="bi bi-bar-chart-line-fill"></i> Total: <?= $total_data ?> Data ditemukan</span>
        </div>

        <div class="card card-body filter-card mb-4 shadow-sm">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold">Pilih Angkatan / Tahun Lulus</label>
                    <select name="tahun_lulus" class="form-select">
                        <option value="">-- Semua Angkatan --</option>
                        <?php foreach ($tahunOptions as $th): ?>
                            <option value="<?= $th ?>" <?= ($tahunLulus == $th) ? 'selected' : '' ?>><?= $th ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                </div>
                <?php if ($total_data > 0): ?>
                <div class="col-md-auto ms-auto">
                    <a href="export_download.php?tahun_lulus=<?= $tahunLulus ?>" class="btn btn-primary">
                        <i class="bi bi-file-earmark-excel me-1"></i> Unduh CSV
                    </a>
                </div>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-center">
                    <tr>
                        <th>Stambuk</th>
                        <th>Nama Lengkap</th>
                        <th>Lulus</th>
                        <th>Aktivitas</th>
                        <th>Instansi</th>
                        <th>Kuesioner</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($total_data > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="text-center fw-bold small"><?= $row['stambuk'] ?? $row['nim'] ?></td>
                            <td class="small"><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                            <td class="text-center small"><?= $row['tahun_lulus'] ?></td>
                            <td><span class="badge bg-info-subtle text-info border-info"><?= $row['status_aktivitas'] ?: '-' ?></span></td>
                            <td class="small"><?= htmlspecialchars($row['nama_instansi'] ?? '-') ?></td>
                            <td class="text-center">
                                <?= $row['id_kuesioner'] ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-dash-circle text-muted"></i>' ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada data untuk ditampilkan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
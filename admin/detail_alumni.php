<?php
session_start();
include '../config/config.php'; 

// Proteksi Admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login_admin.php");
    exit();
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query ambil data lengkap alumni
$query = "SELECT * FROM tb_alumni WHERE id_alumni = '$id'";
$result = mysqli_query($conn, $query);
$alumni = mysqli_fetch_assoc($result);

if (!$alumni) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='data_alumni.php';</script>";
    exit();
}

// Ambil data pekerjaan terakhir
$query_kerja = "SELECT * FROM tb_pekerjaan WHERE id_alumni = '$id' ORDER BY id_pekerjaan DESC";
$res_kerja = mysqli_query($conn, $query_kerja);

// Ambil data kuesioner (jika ada)
$query_kuesioner = "SELECT * FROM tb_kuesioner WHERE id_alumni = '$id'";
$res_kuesioner = mysqli_query($conn, $query_kuesioner);
$sudah_kuesioner = mysqli_num_rows($res_kuesioner) > 0;

$current_page = 'data_alumni.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Alumni - <?= htmlspecialchars($alumni['nama_lengkap']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f6fafd 0%, #eefaf2 100%);
            font-family: 'Inter', sans-serif;
            color: #334155;
            overflow-x: hidden;
        }
        .main-content { padding: 36px 0 52px; position: relative; z-index: 1; }
        .page-shell {
            background: rgba(255,255,255,0.86);
            border: 1px solid rgba(255,255,255,0.45);
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            padding: 22px;
            backdrop-filter: blur(8px);
        }
        .card { border-radius: 18px; border: none; box-shadow: 0 10px 24px rgba(0,0,0,0.05); }
        .profile-header { background: linear-gradient(135deg, #197948 0%, #2eac68 100%); border-radius: 18px 18px 0 0; height: 120px; position: relative; overflow: hidden; }
        .profile-header::after { content: ''; position: absolute; inset: auto -40px -40px auto; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.08); }
        .profile-img { width: 130px; height: 130px; border-radius: 22px; object-fit: cover; border: 5px solid #fff; margin-top: -65px; background: #fff; box-shadow: 0 10px 28px rgba(25,121,72,0.12); }
        .label-detail { color: #64748b; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; margin-bottom: 2px; letter-spacing: 0.03em; }
        .value-detail { color: #1e293b; font-weight: 500; margin-bottom: 15px; line-height: 1.65; }
        .status-badge { font-size: 0.85rem; padding: 6px 15px; border-radius: 999px; font-weight: 600; }
        .badge-approved { background: #d1e7dd; color: #0f5132; }
        .badge-pending { background: #fff3cd; color: #664d03; }
        .hero-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }
        .hero-title h3,
        .hero-title p { margin-bottom: 0; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #197948;
            font-weight: 700;
            text-decoration: none;
        }
        .back-link:hover { color: #155c29; }
        .section-card { padding: 24px; }
        .table thead th { background: #f8fafc; color: #64748b; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; }
        .status-panel { background: rgba(25,121,72,0.05); border-radius: 16px; padding: 16px; }
        .info-card,
        .history-card,
        .kuesioner-card {
            overflow: hidden;
        }
        .panel-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .panel-heading h5,
        .panel-heading p {
            margin-bottom: 0;
        }
        .mini-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #f1fbf4;
            color: #197948;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .btn-success,
        .btn-outline-danger {
            border-radius: 14px;
        }
        .btn-success {
            background: linear-gradient(135deg, #197948, #2eac68);
            border-color: #197948;
            box-shadow: 0 10px 22px rgba(25,121,72,0.16);
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #155c29, #24995a);
            border-color: #155c29;
        }
        .btn-outline-danger:hover {
            transform: translateY(-1px);
        }
        .status-badge-wrap {
            margin-top: 14px;
        }
        @media (max-width: 767.98px) {
            .main-content { padding: 18px 0 40px; }
            .page-shell { padding: 14px; border-radius: 18px; }
            .hero-title { flex-direction: column; align-items: flex-start; }
            .panel-heading { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<div class="container main-content">
    <div class="page-shell">
    <div class="hero-title">
        <div>
            <h3 class="fw-bold">Detail Alumni</h3>
            <p class="text-muted">Ringkasan data alumni, pekerjaan, dan status kuesioner.</p>
        </div>
        <a href="data_alumni.php" class="back-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Alumni
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card text-center pb-4">
                <div class="profile-header"></div>
                <div class="px-3">
                    <?php $foto = !empty($alumni['foto']) ? '../'.$alumni['foto'] : '../assets/profile_placeholder.jpg'; ?>
                    <img src="<?= $foto ?>" class="profile-img shadow-sm mb-3" onerror="this.src='../assets/profile_placeholder.jpg'">
                    <h4 class="fw-bold mb-1"><?= htmlspecialchars($alumni['nama_lengkap']) ?></h4>
                    <p class="text-muted small mb-3">Stambuk: <?= $alumni['stambuk'] ?></p>
                    
                    <div class="status-badge-wrap">
                        <span class="status-badge <?= ($alumni['status_verifikasi'] == 'Approved') ? 'badge-approved' : 'badge-pending' ?> d-inline-block">
                            Status: <?= $alumni['status_verifikasi'] ?>
                        </span>
                    </div>

                    <hr>

                    <?php if($alumni['status_verifikasi'] == 'Pending'): ?>
                        <div class="d-grid gap-2">
                            <a href="proses_verifikasi.php?id=<?= $id ?>&action=approve" class="btn btn-success" onclick="return confirm('Setujui alumni ini?')">
                                <i class="bi bi-check-circle-fill me-2"></i> Setujui Verifikasi
                            </a>
                            <a href="proses_verifikasi.php?id=<?= $id ?>&action=reject" class="btn btn-outline-danger" onclick="return confirm('Tolak pendaftaran ini?')">
                                <i class="bi bi-x-circle me-2"></i> Tolak Data
                            </a>
                        </div>
                    <?php else: ?>
                        <p class="text-success small fw-bold"><i class="bi bi-shield-check"></i> Data Terverifikasi Sistem</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card section-card mb-4 info-card">
                <div class="panel-heading">
                    <div>
                        <h5 class="fw-bold text-success">Informasi Pribadi</h5>
                        <p class="text-muted small">Data utama yang tercatat pada profil alumni.</p>
                    </div>
                    <span class="mini-pill"><i class="bi bi-person-badge"></i> Profil</span>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="label-detail">Email</div>
                        <div class="value-detail"><?= $alumni['email'] ?></div>
                        
                        <div class="label-detail">WhatsApp / No. HP</div>
                        <div class="value-detail"><?= $alumni['no_hp'] ?: '-' ?></div>

                        <div class="label-detail">Marhalah (Angkatan)</div>
                        <div class="value-detail"><?= $alumni['marhalah'] ?> (Lulus <?= $alumni['tahun_lulus'] ?>)</div>
                    </div>
                    <div class="col-md-6">
                        <div class="label-detail">Konsulat</div>
                        <div class="value-detail"><?= $alumni['konsulat'] ?></div>

                        <div class="label-detail">Alamat Sekarang</div>
                        <div class="value-detail"><?= $alumni['alamat_sekarang'] ?: '-' ?></div>
                    </div>
                </div>
            </div>

            <div class="card section-card mb-4 history-card">
                <div class="panel-heading">
                    <div>
                        <h5 class="fw-bold text-success">Riwayat Pekerjaan & Aktivitas</h5>
                        <p class="text-muted small">Aktivitas terakhir yang pernah diisi alumni.</p>
                    </div>
                    <span class="mini-pill"><i class="bi bi-briefcase"></i> Aktivitas</span>
                </div>
                <?php if(mysqli_num_rows($res_kerja) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Status</th>
                                    <th>Instansi/Kampus</th>
                                    <th>Jabatan/Jurusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($k = mysqli_fetch_assoc($res_kerja)): ?>
                                <tr>
                                    <td><span class="badge bg-primary-subtle text-primary"><?= $k['status_aktivitas'] ?></span></td>
                                    <td><?= htmlspecialchars($k['nama_instansi']) ?></td>
                                    <td><?= htmlspecialchars($k['jabatan']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted small">Belum ada data aktivitas yang diisi.</p>
                <?php endif; ?>
            </div>

            <div class="card section-card kuesioner-card">
                <div class="panel-heading">
                    <div>
                        <h5 class="fw-bold text-success">Status Kuesioner Tracer Study</h5>
                        <p class="text-muted small">Menandai apakah alumni sudah mengisi tracer study.</p>
                    </div>
                    <span class="mini-pill"><i class="bi bi-journal-check"></i> Kuesioner</span>
                </div>
                <div class="d-flex align-items-center status-panel">
                    <div class="me-3">
                        <i class="bi <?= $sudah_kuesioner ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger' ?>" style="font-size: 2.5rem;"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold"><?= $sudah_kuesioner ? 'Sudah Mengisi Kuesioner' : 'Belum Mengisi Kuesioner' ?></h6>
                        <p class="text-muted small mb-0"><?= $sudah_kuesioner ? 'Alumni telah memberikan feedback evaluasi organisasi.' : 'Alumni ini belum berkontribusi dalam pengisian kuesioner evaluasi.' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
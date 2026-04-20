<?php
session_start();
include '../config/config.php'; 

// PROTEKSI ADMIN
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login_admin.php?error=not_logged_in");
    exit();
}

$admin_nama = $_SESSION['admin_nama'] ?? 'Administrator';
$current_page = 'kelola_berita.php';

// AMBIL DATA LAMA BERDASARKAN ID
$id = $_GET['id'];
$query_lama = mysqli_query($conn, "SELECT * FROM tb_berita WHERE id_berita = '$id'");
$data = mysqli_fetch_assoc($query_lama);

// JIKA DATA TIDAK DITEMUKAN
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='kelola_berita.php';</script>";
    exit();
}

// PROSES UPDATE
if (isset($_POST['update'])) {
    $judul   = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $isi     = mysqli_real_escape_string($conn, $_POST['isi']);

    $foto_name = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];

    if (!empty($foto_name)) {
        // JIKA GANTI FOTO
        $foto_ext = strtolower(pathinfo($foto_name, PATHINFO_EXTENSION));
        $foto_baru = "Berita_" . date('Ymd_His') . "." . $foto_ext;
        $path = "../uploads/berita/" . $foto_baru;

        if (move_uploaded_file($foto_tmp, $path)) {
            // Hapus foto lama agar tidak nyampah
            if (!empty($data['foto_kegiatan']) && file_exists("../uploads/berita/" . $data['foto_kegiatan'])) {
                unlink("../uploads/berita/" . $data['foto_kegiatan']);
            }
            $sql = "UPDATE tb_berita SET judul='$judul', tanggal_kegiatan='$tanggal', isi='$isi', foto_kegiatan='$foto_baru' WHERE id_berita='$id'";
        }
    } else {
        // JIKA TIDAK GANTI FOTO
        $sql = "UPDATE tb_berita SET judul='$judul', tanggal_kegiatan='$tanggal', isi='$isi' WHERE id_berita='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Berita berhasil diperbarui!'); window.location='kelola_berita.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Berita | Admin</title>
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
        }
        .page-wrap {
            min-height: 100vh;
            padding: 40px 0 56px;
        }
        .page-shell {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(255,255,255,0.45);
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            padding: 24px;
            backdrop-filter: blur(8px);
        }
        .card { border-radius: 18px; border: none; box-shadow: 0 10px 24px rgba(0,0,0,0.05); }
        .title-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px; }
        .back-link { color: #197948; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .back-link:hover { color: #155c29; }
        .form-control { border-radius: 14px; border-color: #d8e7dd; }
        .form-control:focus { border-color: #197948; box-shadow: 0 0 0 0.2rem rgba(25,121,72,0.16); }
        .btn-success { background: linear-gradient(135deg, #197948, #2eac68); border-color: #197948; border-radius: 14px; box-shadow: 0 10px 22px rgba(25,121,72,0.16); }
        .btn-success:hover { background: linear-gradient(135deg, #155c29, #24995a); border-color: #155c29; transform: translateY(-1px); }
        .btn-light { border-radius: 14px; }
        .preview-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 12px; }
        .preview-box img { max-width: 100%; }
        @media (max-width: 767.98px) {
            .page-wrap { padding: 18px 0 36px; }
            .page-shell { padding: 16px; border-radius: 18px; }
            .title-row { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

<div class="container page-wrap">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="page-shell">
            <div class="title-row">
                <div>
                    <h4 class="fw-bold mb-1 text-success"><i class="bi bi-pencil-square me-2"></i>Edit Berita / Kegiatan</h4>
                    <p class="text-muted mb-0">Perbarui isi berita tanpa mengubah alur data yang sudah ada.</p>
                </div>
                <a href="kelola_berita.php" class="back-link"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>

            <div class="card p-4">
                
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Judul Berita</label>
                                <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tanggal Kegiatan</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= $data['tanggal_kegiatan'] ?>" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Narasi Berita</label>
                                <textarea name="isi" class="form-control" rows="8" required><?= htmlspecialchars($data['isi']) ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Ganti Foto (Biarkan kosong jika tidak diganti)</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <div class="mt-2 preview-box">
                                    <small class="text-muted d-block mb-1">Foto saat ini:</small>
                                    <img src="../uploads/berita/<?= $data['foto_kegiatan'] ?>" width="150" class="rounded border shadow-sm">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 border-top pt-3">
                            <button type="submit" name="update" class="btn btn-success px-5 rounded-pill">
                                <i class="bi bi-check-circle-fill me-2"></i> Simpan Perubahan
                            </button>
                            <a href="kelola_berita.php" class="btn btn-light px-4 rounded-pill ms-2">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
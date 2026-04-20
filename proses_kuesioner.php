<?php
session_start();
include 'config/config.php';

// 1. Cek login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php?error=not_logged_in");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_alumni = $_SESSION['id_alumni'];

    // 2. Ambil data & Sinkronkan Nama Variabel dengan kuesioner.php
            // Bagian pengambilan data POST
            $kontribusi_ikpm = (int)$_POST['kontribusi_ikpm'];
            $manfaat_pondok  = (int)$_POST['manfaat_pondok'];
            $aktif_kegiatan  = (int)$_POST['aktif_kegiatan'];
            $saran_perbaikan = mysqli_real_escape_string($conn, $_POST['saran_perbaikan']);
            $tanggal_isi          = date('Y-m-d');

    // 3. Cek apakah alumni sudah pernah mengisi sebelumnya
    $cek = mysqli_query($conn, "SELECT id_kuesioner FROM tb_kuesioner WHERE id_alumni = '$id_alumni' LIMIT 1");
    
    if (mysqli_num_rows($cek) > 0) {
        // 4. Jika sudah ada, lakukan UPDATE
        $query = "UPDATE tb_kuesioner SET 
                    kontribusi_ikpm = $kontribusi_ikpm,
                    manfaat_pondok  = $manfaat_pondok,
                    aktif_kegiatan  = $aktif_kegiatan,
                    saran_perbaikan = '$saran_perbaikan',
                    tanggal_isi     = '$tanggal_isi'
                  WHERE id_alumni = '$id_alumni'";
    } else {
        // 5. Jika belum ada, lakukan INSERT
        $query = "INSERT INTO tb_kuesioner 
                    (id_alumni, kontribusi_ikpm, manfaat_pondok, aktif_kegiatan, saran_perbaikan, tanggal_isi) 
                  VALUES 
                    ('$id_alumni', $kontribusi_ikpm, $manfaat_pondok, $aktif_kegiatan, '$saran_perbaikan', '$tanggal_isi')";
    }

    // 6. Eksekusi Query
    if (mysqli_query($conn, $query)) {
        header("Location: kuesioner.php?success=1");
        exit();
    } else {
        // Log error untuk mempermudah debugging jika gagal
        echo "Error: " . mysqli_error($conn);
        // header("Location: kuesioner.php?error=1"); // Aktifkan jika sudah selesai debug
        exit();
    }
} else {
    header("Location: kuesioner.php");
    exit();
}
?>
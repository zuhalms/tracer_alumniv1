<?php
session_start();

// 1. Proteksi Admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login_admin.php'); 
    exit();
}

include '../config/config.php';

// 2. Ambil filter tahun dari URL
$tahunLulus = isset($_GET['tahun_lulus']) ? intval($_GET['tahun_lulus']) : '';
$where = $tahunLulus ? "WHERE a.tahun_lulus = '$tahunLulus'" : '';

/**
 * 3. Query Data Alumni
 * Menggunakan GROUP BY agar satu alumni tetap satu baris di Excel 
 * dan mengambil data pekerjaan TERBARU saja.
 */
$query = "
    SELECT 
        a.stambuk, a.nama_lengkap, a.marhalah, a.konsulat, a.tahun_lulus, a.email, a.no_hp, a.alamat_sekarang, a.status_verifikasi,
        p.status_aktivitas, p.nama_instansi, p.jabatan_jurusan,
        k.id_kuesioner
    FROM tb_alumni a
    LEFT JOIN (
        SELECT * FROM tb_pekerjaan WHERE id_pekerjaan IN (SELECT MAX(id_pekerjaan) FROM tb_pekerjaan GROUP BY id_alumni)
    ) p ON p.id_alumni = a.id_alumni
    LEFT JOIN tb_kuesioner k ON k.id_alumni = a.id_alumni
    $where
    GROUP BY a.id_alumni
    ORDER BY a.tahun_lulus DESC, a.nama_lengkap ASC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Terjadi kesalahan query: " . mysqli_error($conn));
}

// 4. Pengaturan Header File untuk CSV/Excel
$filename = "Tracer_IKPM_" . ($tahunLulus ? "Angkatan_$tahunLulus" : "Semua_Angkatan") . "_" . date('Ymd') . ".csv";

header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");

// 5. Proses Output Data
$output = fopen("php://output", "w");

// Tambahkan BOM (Byte Order Mark) agar Excel membaca karakter UTF-8 (seperti simbol atau spasi khusus) dengan benar
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header kolom di Excel
fputcsv($output, [
    'STAMBUK', 
    'NAMA LENGKAP', 
    'MARHALAH', 
    'KONSULAT', 
    'TAHUN LULUS', 
    'EMAIL', 
    'NO. HP / WA', 
    'ALAMAT SEKARANG', 
    'STATUS VERIFIKASI',
    'STATUS AKTIVITAS', 
    'NAMA INSTANSI/KAMPUS', 
    'JABATAN/JURUSAN',
    'STATUS KUESIONER'
]);

// Isi Data
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['stambuk'],
        $row['nama_lengkap'],
        $row['marhalah'],
        $row['konsulat'],
        $row['tahun_lulus'],
        $row['email'],
        $row['no_hp'],
        $row['alamat_sekarang'],
        $row['status_verifikasi'],
        $row['status_aktivitas'] ?? '-',
        $row['nama_instansi'] ?? '-',
        $row['jabatan_jurusan'] ?? '-',
        ($row['id_kuesioner'] ? 'Sudah Isi' : 'Belum Isi')
    ]);
}

fclose($output);
exit();
?>
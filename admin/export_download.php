<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    exit('Akses ditolak');
}

include '../config/config.php';

// 1. Ambil Filter Tahun
$tahunLulus = isset($_GET['tahun_lulus']) ? mysqli_real_escape_string($conn, $_GET['tahun_lulus']) : '';
$where = $tahunLulus ? "WHERE tahun_lulus = '$tahunLulus'" : "";

// 2. Query Data (Memastikan semua kolom ditarik)
$query = "SELECT * FROM tb_alumni $where ORDER BY nama_lengkap ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error Database: " . mysqli_error($conn));
}

// 3. Header agar Browser Mengenali sebagai File Excel (.xls)
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data_Alumni_IKPM_".date('Ymd').".xls");
header("Pragma: no-cache");
header("Expires: 0");

// 4. Output Tabel HTML yang dibaca rapi oleh Excel
?>
<table border="1">
    <tr>
        <th colspan="7" style="background-color: #197948; color: white; height: 35px; font-size: 14pt;">
            LAPORAN DATA ALUMNI IKPM (Dicetak: <?= date('d-m-Y H:i') ?>)
        </th>
    </tr>
    <tr style="background-color: #f2f2f2; font-weight: bold; text-align: center;">
        <th width="120">Stambuk/NIM</th>
        <th width="250">Nama Lengkap</th>
        <th width="150">Konsulat</th>
        <th width="100">Tahun Lulus</th>
        <th width="200">Email</th>
        <th width="150">No HP</th>
        <th width="300">Alamat</th>
    </tr>

    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        // Logika pemilihan kolom agar tidak error jika nama kolom di DB sedikit berbeda
        $stambuk  = $row['stambuk'] ?? $row['nim'] ?? '-';
        $konsulat = $row['konsulat'] ?? $row['cabang'] ?? $row['program_studi'] ?? '-';
        $alamat   = $row['alamat_sekarang'] ?? $row['alamat'] ?? '-';
        
        echo "<tr>";
        // Penggunaan tanda petik (') agar angka 0 di depan Stambuk dan No HP tidak hilang
        echo "<td style='text-align:center;'>'" . $stambuk . "</td>";
        echo "<td>" . htmlspecialchars(strtoupper($row['nama_lengkap'])) . "</td>";
        echo "<td style='text-align:center;'>" . htmlspecialchars($konsulat) . "</td>";
        echo "<td style='text-align:center;'>" . $row['tahun_lulus'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td style='text-align:center;'>'" . $row['no_hp'] . "</td>";
        echo "<td>" . htmlspecialchars($alamat) . "</td>";
        echo "</tr>";
    }
    ?>
</table>
<?php
exit();
?>
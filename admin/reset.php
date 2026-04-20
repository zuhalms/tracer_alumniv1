<?php
include '../config/config.php';

// Kita buat hash baru yang fresh
$password_baru = 'admin';
$hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

// Update langsung ke database
$query = "UPDATE tb_admin SET password = '$hash_baru' WHERE username = 'admin'";

if (mysqli_query($conn, $query)) {
    echo "<h3>Reset Berhasil!</h3>";
    echo "Password untuk username <b>admin</b> sekarang adalah: <b>admin</b><br>";
    echo "Silakan hapus file ini demi keamanan, lalu <a href='login_admin.php'>Login di sini</a>";
} else {
    echo "Gagal update: " . mysqli_error($conn);
}
?>
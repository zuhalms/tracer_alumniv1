<?php
include '../config/config.php';

// Data admin baru
$username_baru = 'penguruswilayah'; // Silakan ganti sesuai keinginan
$password_plain = 'ikpmgontor';      // Password yang akan diketik saat login
$nama_admin = 'Pengurus Wilayah IKPM Gontor'; // Nama lengkap admin
$level = 'Pengurus Wilayah';        // Sesuai enum di database

// Proses Hashing
$password_aman = password_hash($password_plain, PASSWORD_DEFAULT);

$query = "INSERT INTO tb_admin (username, password, nama_admin, level) 
          VALUES ('$username_baru', '$password_aman', '$nama_admin', '$level')";

if (mysqli_query($conn, $query)) {
    echo "Akun admin baru berhasil ditambahkan!<br>";
    echo "Username: $username_baru | Password: $password_plain";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>
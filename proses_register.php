<?php
include 'config/config.php';

// 1. Ambil data dari form registrasi.php
// mysqli_real_escape_string untuk mencegah SQL Injection
$stambuk       = mysqli_real_escape_string($conn, $_POST['nim']);
$nama_lengkap  = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$marhalah      = mysqli_real_escape_string($conn, $_POST['tahun_lulus']); // Diambil dari input tahun lulus
$tahun_lulus   = mysqli_real_escape_string($conn, $_POST['tahun_lulus']);
$email         = mysqli_real_escape_string($conn, $_POST['email']);
$no_hp         = mysqli_real_escape_string($conn, $_POST['no_hp']);
$alamat        = mysqli_real_escape_string($conn, $_POST['alamat']);
$konsulat      = mysqli_real_escape_string($conn, $_POST['konsulat']);
$password      = $_POST['password'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// Catatan: $_POST['tahun_masuk'] tersedia di form tapi tidak ada kolomnya di tb_alumni Anda saat ini.

// 2. Validasi kesamaan password
if($password != $konfirmasi_password) {
    header("Location: register.php?error=password_mismatch");
    exit();
}

// 3. Hash password menggunakan MD5 (Sesuai kode awal Anda)
$password_hash = md5($password);

// 4. Cek apakah Stambuk sudah terdaftar (Penting: Kolom di DB adalah 'stambuk')
$cek_stambuk = mysqli_query($conn, "SELECT * FROM tb_alumni WHERE stambuk='$stambuk'");
if(mysqli_num_rows($cek_stambuk) > 0) {
    header("Location: register.php?error=nim_exist");
    exit();
}

// 5. Cek apakah Email sudah terdaftar
$cek_email = mysqli_query($conn, "SELECT * FROM tb_alumni WHERE email='$email'");
if(mysqli_num_rows($cek_email) > 0) {
    header("Location: register.php?error=email_exist");
    exit();
}

/**
 * 6. Insert data ke tabel tb_alumni
 * Kolom yang disesuaikan:
 * - stambuk (dari input nim)
 * - marhalah (dari input tahun_lulus)
 * - alamat_sekarang (dari input alamat)
 * - status_verifikasi otomatis 'Pending' (default di DB)
 */
$query = "INSERT INTO tb_alumni 
          (stambuk, nama_lengkap, marhalah, konsulat, tahun_lulus, email, no_hp, alamat_sekarang, password) 
          VALUES 
          ('$stambuk', '$nama_lengkap', '$marhalah', '$konsulat', '$tahun_lulus', '$email', '$no_hp', '$alamat', '$password_hash')";

if(mysqli_query($conn, $query)) {
    // Registrasi berhasil, arahkan ke login
    header("Location: login.php?success=register");
    exit();
} else {
    // Jika gagal karena error database
    header("Location: register.php?error=failed");
    exit();
}
?>
<?php
session_start();
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Bersihkan input
    $nim_email = trim(mysqli_real_escape_string($conn, $_POST['nim_email']));
    $password = $_POST['password'];

    if (empty($nim_email) || empty($password)) {
        header("Location: login.php?error=empty_fields");
        exit();
    }

    // Ambil data user berdasar Stambuk (sebelumnya nim) atau email
    // Sesuaikan nama kolom dengan database db_tracer_ikpm: stambuk
    $query = "SELECT * FROM tb_alumni WHERE stambuk=? OR email=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $nim_email, $nim_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $data = mysqli_fetch_assoc($result);

        // Verifikasi password MD5
        if (md5($password) === $data['password']) {
            // Login berhasil, set session
            $_SESSION['id_alumni'] = $data['id_alumni'];
            $_SESSION['stambuk']   = $data['stambuk']; // Diubah dari nim ke stambuk
            $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['email']     = $data['email'];
            $_SESSION['marhalah']  = $data['marhalah']; // Menggantikan program_studi
            $_SESSION['konsulat']  = $data['konsulat']; // Menambahkan info konsulat ke session
            $_SESSION['is_login']  = true;

            header("Location: dashboard_alumni.php");
            exit();
        } else {
            header("Location: login.php?error=wrong_credentials");
            exit();
        }
    } else {
        header("Location: login.php?error=wrong_credentials");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
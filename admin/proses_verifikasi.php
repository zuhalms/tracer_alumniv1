<?php
session_start();
include '../config/config.php';

// 1. Proteksi Admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login_admin.php");
    exit();
}

// 2. Ambil Parameter dari URL
$id_alumni = isset($_GET['id']) ? intval($_GET['id']) : 0;
$action    = isset($_GET['action']) ? $_GET['action'] : '';

if ($id_alumni <= 0 || empty($action)) {
    header("Location: data_alumni.php?msg=invalid_params");
    exit();
}

// 3. Tentukan Status Berdasarkan Action
if ($action === 'approve') {
    $status = 'Approved';
} elseif ($action === 'reject') {
    $status = 'Rejected';
} else {
    header("Location: data_alumni.php?msg=invalid_action");
    exit();
}

// 4. Update Status di Database
$query = "UPDATE tb_alumni SET status_verifikasi = '$status' WHERE id_alumni = '$id_alumni'";

if (mysqli_query($conn, $query)) {
    // Berhasil: Alihkan kembali dengan status sukses
    header("Location: data_alumni.php?status=success&action=$action");
} else {
    // Gagal: Alihkan dengan pesan error
    header("Location: data_alumni.php?status=error&msg=" . mysqli_error($conn));
}
exit();
?>
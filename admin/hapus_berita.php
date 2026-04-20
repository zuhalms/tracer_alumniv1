<?php
include '../config/config.php';

$id = $_GET['id'];

// Ambil nama file foto sebelum dihapus dari DB
$query = mysqli_query($conn, "SELECT foto_kegiatan FROM tb_berita WHERE id_berita = '$id'");
$data  = mysqli_fetch_assoc($query);
$foto  = $data['foto_kegiatan'];

// Hapus file fisik di local
if (file_exists("../uploads/berita/" . $foto)) {
    unlink("../uploads/berita/" . $foto);
}

// Hapus dari Database
mysqli_query($conn, "DELETE FROM tb_berita WHERE id_berita = '$id'");

header("Location: kelola_berita.php");
?>
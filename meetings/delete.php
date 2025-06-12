<?php
include 'koneksi.php';

// Cek apakah parameter id tersedia
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Jalankan query DELETE
    $delete = mysqli_query($koneksi, "DELETE FROM meetings WHERE id = $id");

    if ($delete) {
        // Redirect ke halaman utama setelah berhasil hapus
        header("Location: meetings.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    // Jika parameter id tidak valid
    echo "ID tidak valid.";
}
?>

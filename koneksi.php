<?php
// koneksi.php
$host = 'localhost';   // Ganti dengan host database Anda
$dbname = 'TimetoMeet';  // Ganti dengan nama database Anda
$username = 'root';    // Ganti dengan username MySQL Anda
$password = '';        // Ganti dengan password MySQL Anda

// Membuat koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Cek apakah koneksi berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

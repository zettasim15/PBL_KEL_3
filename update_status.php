<?php
// Mengimpor file koneksi.php
include('koneksi.php');

// Mulai sesi untuk mendapatkan ID pengguna
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data yang dikirim dari form
$team_id = $_POST['team_id'];
$user_id = $_POST['user_id'];
$action = $_POST['action']; // Bisa 'accept' atau 'reject'

// Validasi aksi yang diterima
if ($action !== 'accept' && $action !== 'reject') {
    die("Invalid action.");
}

// Update status anggota tim
if ($action === 'accept') {
    // Jika diterima, status berubah menjadi 'active'
    $new_status = 'active';
} else {
    // Jika ditolak, status tetap 'pending' atau bisa dihapus, tergantung kebijakan
    $new_status = 'pending';
}

// Perbarui status di tabel 'team_members'
$sql_update = "UPDATE team_members SET status = ? WHERE team_id = ? AND user_id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("sii", $new_status, $team_id, $user_id);

if ($stmt_update->execute()) {
    // Redirect ke halaman yang sesuai setelah pembaruan
    header("Location: group.php"); // Atau ke halaman lain jika perlu
    exit();
} else {
    // Menangani kesalahan
    echo "Error updating status: " . $conn->error;
}
?>

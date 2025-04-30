<?php
// Mengimpor file koneksi.php
include('koneksi.php');

// Mulai sesi untuk mendapatkan ID pengguna
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // ID Manager yang sedang login

// Ambil data dari form
$team_id = $_POST['team_id'];
$member_id = $_POST['member_id'];

// Periksa apakah pengguna dengan ID member dan team ada di database
$sql_check = "SELECT * FROM team_members WHERE user_id = ? AND team_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $member_id, $team_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Jika member sudah ada di tim
    echo "Member is already in the team.";
} else {
    // Jika member belum ada di tim, insert ke database
    $sql_invite = "INSERT INTO team_members (user_id, team_id) VALUES (?, ?)";
    $stmt_invite = $conn->prepare($sql_invite);
    $stmt_invite->bind_param("ii", $member_id, $team_id);
    
    if ($stmt_invite->execute()) {
        echo "Member successfully invited to the team!";
    } else {
        echo "Error inviting member.";
    }
}
?>
 
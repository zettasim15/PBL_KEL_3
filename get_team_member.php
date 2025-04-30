<?php
include('koneksi.php');

// Cek apakah ada parameter team_id
if (isset($_GET['team_id'])) {
    $team_id = $_GET['team_id'];

    // Ambil anggota berdasarkan team_id
    $sql_members = "SELECT u.id, u.display_username FROM users u
                    JOIN team_members tm ON u.id = tm.user_id
                    WHERE tm.team_id = ?";
    $stmt = $conn->prepare($sql_members);
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $members = [];
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }

    // Kembalikan data anggota dalam format JSON
    echo json_encode(['members' => $members]);
}
?>

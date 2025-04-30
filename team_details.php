<?php
include('koneksi.php');

// Get the team ID from the URL
$team_id = $_GET['team_id'];

// Fetch the team members from the database
$sql = "SELECT users.username, team_members.role, team_members.status, team_members.invited_at 
        FROM team_members 
        JOIN users ON team_members.user_id = users.id 
        WHERE team_members.team_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $team_id);
$stmt->execute();
$members_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Details</title>
</head>
<body>
    <h1>Team Members</h1>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Invited At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($member = $members_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($member['username']); ?></td>
                    <td><?= htmlspecialchars($member['role']); ?></td>
                    <td><?= htmlspecialchars($member['status']); ?></td>
                    <td><?= htmlspecialchars($member['invited_at']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

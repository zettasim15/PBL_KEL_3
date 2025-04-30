<?php
// Include the database connection
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitizing and validating input
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Username for login
    $password = $_POST['password']; // Password
    $role = $_POST['role'];  // Role (Member/Manager)
    $display_username = $username; // Default value for display_username
    
    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Query to insert data into the database
    $sql = "INSERT INTO users (username, password, role, display_username) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $hashed_password, $role, $display_username);
    
    // Execute the query to save the data
    if ($stmt->execute()) {
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #bcddc3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #FFFFFF;
            border-radius: 20px;
            padding: 40px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .toggle-buttons {
            display: flex;
            margin-bottom: 20px;
        }
        .toggle-buttons button {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .toggle-buttons .active {
            background-color: #0A4A6B;
            color: white;
        }
        .toggle-buttons .inactive {
            background-color: #F5F8F7;
            color: #0A4A6B;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 14px;
        }
        .submit-button {
            display: flex;
            justify-content: flex-end;
        }
        .submit-button button {
            background-color: #333333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .login-link a {
            color: #0A4A6B;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Sign up</h1>
    <form action="register.php" method="POST">
        <div class="toggle-buttons">
            <button type="button" id="MemberBtn" class="active" onclick="setRole('Member')">Member</button>
            <button type="button" id="ManagerBtn" class="inactive" onclick="setRole('Manager')">Manager</button>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <input type="hidden" id="role" name="role" value="member"> <!-- Default Role -->
        <div class="submit-button">
            <button type="submit">Create Account</button>
        </div>
        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </form>
</div>

<script>
    // Function to set the role when a button is clicked
    function setRole(role) {
        document.getElementById("role").value = role;
        if (role === 'Member') {
            document.getElementById("MemberBtn").classList.add("active");
            document.getElementById("MemberBtn").classList.remove("inactive");
            document.getElementById("ManagerBtn").classList.remove("active");
            document.getElementById("ManagerBtn").classList.add("inactive");
        } else if (role === 'Manager'){
            document.getElementById("ManagerBtn").classList.add("active");
            document.getElementById("ManagerBtn").classList.remove("inactive");
            document.getElementById("MemberBtn").classList.remove("active");
            document.getElementById("MemberBtn").classList.add("inactive");
        }
    }
</script>
</body>
</html>

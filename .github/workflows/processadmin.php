<?php
// Include database configuration
require 'config.php';

// Establish database connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check if connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminUsername = trim($_POST['admin_username']);
    $adminPassword = trim($_POST['admin_password']);

    // Validate input
    if (empty($adminUsername) || empty($adminPassword)) {
        echo "<script>alert('Please fill in all fields.'); window.history.back();</script>";
        exit();
    }

    // Check if the username already exists
    $checkQuery = "SELECT * FROM tbl_admin WHERE admin_username = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $adminUsername);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Username already exists. Try a different one.'); window.history.back();</script>";
        exit();
    }

    // Hash the password for security
    $hashedPassword = password_hash($adminPassword, PASSWORD_DEFAULT);

    // Insert the new admin into the database
    $insertQuery = "INSERT INTO tbl_admin (admin_username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ss", $adminUsername, $hashedPassword);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Admin added successfully!'); window.location.href = 'cpanel.php';</script>";
    } else {
        echo "<script>alert('Error adding admin. Please try again.'); window.history.back();</script>";
    }

    // Close connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

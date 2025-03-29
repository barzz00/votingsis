<?php
session_start();
include "config.php";

if (!isset($_SESSION['email'])) {
    die("Session expired. Please register again.");
}

if (isset($_POST['verify'])) {
    $entered_otp = trim($_POST['otp']);
    $email = $_SESSION['email'];

    // Debugging: Check if OTP is in session
    if (!isset($_SESSION['otp'])) {
        die("OTP session is missing! OTP was never saved.");
    }
    echo "Session OTP: " . $_SESSION['otp'] . "<br>";

    // Fetch OTP from database
    $query = "SELECT otp FROM login WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("No OTP found for this email. Something went wrong.");
    }

    echo "Stored OTP: " . $user['otp'] . "<br>"; // Debugging

    // Compare OTP
    if ((string) $entered_otp === (string) $user['otp']) {
        // Clear OTP from database after successful verification
        $update = $conn->prepare("UPDATE login SET otp = NULL, verified = 1 WHERE email = ?");
        $update->bind_param("s", $email);
        $update->execute();

        $_SESSION['logged_in'] = true;

        echo "<script>
                alert('Your number is successfully verified! You can now vote.');
                window.location='vote.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Invalid OTP! Try again.');</script>";
    }
}
?>

<form method="POST">
    <input type="number" name="otp" placeholder="Enter OTP" required>
    <button type="submit" name="verify">Verify OTP</button>
</form>

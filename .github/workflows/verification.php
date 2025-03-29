<?php
session_start();
require_once('config.php');

// Check if email session exists
if (!isset($_SESSION['email'])) {
    echo "<script>alert('No OTP generated. Please register first.'); window.location='register.php';</script>";
    exit();
}

$email = $_SESSION['email'];

if (isset($_POST["verify"])) {
    $entered_otp = trim($_POST["otp"]);

    // Fetch OTP from database
    $query = $conn->prepare("SELECT otp FROM login WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    if ($user && $entered_otp == $user['otp']) {
        // Update verification status
        $update_query = $conn->prepare("UPDATE login SET verified = 1, otp = NULL WHERE email = ?");
        $update_query->bind_param("s", $email);

        if ($update_query->execute()) {
            // Unset OTP session
            unset($_SESSION['otp']);

            echo "<script>
                    alert('Your number is successfully verified! Vote wisely!');
                    window.location='login.php';
                  </script>";
            exit();
        } else {
            echo "<script>alert('Database error. Try again.');</script>";
        }
    } else {
        echo "<script>alert('Invalid OTP! Try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .otp-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .otp-input {
            text-align: center;
            letter-spacing: 5px;
            font-size: 22px;
            font-weight: bold;
        }
        .btn-primary {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="otp-container">
    <h3>Verify OTP</h3>
    <p>Enter the 6-digit OTP sent to your email.</p>

    <form action="verification.php" method="POST">
        <div class="mb-3">
            <input type="text" name="otp" class="form-control otp-input" maxlength="6" required placeholder="******">
        </div>
        <button type="submit" name="verify" class="btn btn-primary">Verify OTP</button>
    </form>
</div>

<script>
    document.querySelector('.otp-input').addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');  // Allow only numbers
    });
</script>

</body>
</html>

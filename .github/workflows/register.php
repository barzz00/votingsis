<?php
session_start();
require_once('config.php');

if (isset($_POST["register"])) {
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone"];
    $course = $_POST["course"];
    $year = $_POST["year"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if email already exists
    $check_query = $conn->prepare("SELECT * FROM login WHERE email = ?");
    $check_query->bind_param("s", $email);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('User with this email already exists!');</script>";
    } else {
        // Secure password hashing (bcrypt)
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $otp = rand(100000, 999999); // Generate OTP

        // Save OTP to session for verification
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Insert user into database with OTP
        $insert_query = $conn->prepare("INSERT INTO login (full_name, phone, course, year, email, password, otp, status) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
        $insert_query->bind_param("sssssss", $full_name, $phone, $course, $year, $email, $password_hash, $otp);

        if ($insert_query->execute()) {
            echo "<script>alert('Registration successful! Your OTP is $otp.'); window.location='verification.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error in registration! Try again.');</script>";
        }
    }
}
?>






<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>Register Form</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="register.php">Register Form</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form action="register.php" method="POST">
                            <div class="form-group row">
                                <label for="full_name" class="col-md-4 col-form-label text-md-right">Full Name</label>
                                <div class="col-md-6">
                                    <input type="text" id="full_name" class="form-control" name="full_name" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">Phone</label>
                                <div class="col-md-6">
                                    <input type="text" id="phone" class="form-control" name="phone" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="course" class="col-md-4 col-form-label text-md-right">Course</label>
                                <div class="col-md-6">
                                    <input type="text" id="course" class="form-control" name="course" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="year" class="col-md-4 col-form-label text-md-right">Year</label>
                                <div class="col-md-6">
                                    <input type="text" id="year" class="form-control" name="year" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                <div class="col-md-6">
                                    <input type="email" id="email" class="form-control" name="email" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="password" class="form-control" name="password" required>
                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                </div>
                            </div>

                            <div class="col-md-6 offset-md-4">
                                <input type="submit" value="Register" name="register" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>

<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    toggle.addEventListener('click', function(){
        if(password.type === "password"){
            password.type = 'text';
        } else {
            password.type = 'password';
        }
        this.classList.toggle('bi-eye');
    });
</script>

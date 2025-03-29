<?php
session_start();
include 'config.php';

// Handle login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare and execute the query
    $query = $conn->prepare("SELECT * FROM login WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    // Check if the user exists
    if ($user) {
        // Check if the user has already voted
        if ($user['status'] == 0) {
            echo "<script>alert('You already voted, see you next SSG election.'); window.location='login.php';</script>";
            exit();
        }

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            echo "<script>alert('Incorrect password! Please try again.');</script>";
        } else {
            // Successful login
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;

            echo "<script>
                    localStorage.setItem('showSuccessModal', 'true');
                    window.location.href = 'index.html';
                </script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Login Form</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .preloader {
            position: fixed;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .spinner-border {
            width: 4rem;
            height: 4rem;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="preloader" id="preloader">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow">
        <div class="container">
            <a class="navbar-brand" href="#">SIS Voting System in College Department</a>
        </div>
    </nav>

    <main class="login-form mt-5 hidden" id="loginPanel">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white">Login</div>
                        <div class="card-body">
                            <form action="login.php" method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-Mail Address</label>
                                    <input type="email" id="email" class="form-control" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" class="form-control" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" name="login">Login</button>
                            </form>
                            <div class="text-center mt-3">
                                <a href="register.php" class="btn btn-outline-primary w-100">Register</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        window.onload = function() {
            setTimeout(() => {
                document.getElementById('preloader').style.display = 'none';
                document.getElementById('loginPanel').classList.remove('hidden');
            }, 2000);
        };

        function redirectToDashboard() {
            document.getElementById('preloader').style.display = 'flex';
            document.getElementById('loginPanel').classList.add('hidden');
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 2000);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

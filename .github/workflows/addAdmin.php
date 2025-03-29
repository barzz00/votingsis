<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Admin - SVS</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <style>
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: white;
        }
        .error-message {
            color: red;
            display: none;
        }
    </style>
</head>
<body>

<div class="container" style="padding: 100px;">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-container">
                <h2 class="text-center">Add Admin</h2>
                <p class="text-center text-muted">Create a new administrator account</p>

                <form action="processAdmin.php" method="POST" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="adminUsername">Username:</label>
                        <input type="text" class="form-control" id="adminUsername" name="adminUsername" required>
                    </div>

                    <div class="form-group">
                        <label for="adminPassword">Password:</label>
                        <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                        <small class="error-message" id="passwordError">Passwords do not match!</small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Add Admin</button>
                    <a href="cpanel.php" class="btn btn-secondary btn-block">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function validateForm() {
        var password = document.getElementById("adminPassword").value;
        var confirmPassword = document.getElementById("confirmPassword").value;
        var passwordError = document.getElementById("passwordError");

        if (password !== confirmPassword) {
            passwordError.style.display = "block";
            return false;
        } else {
            passwordError.style.display = "none";
            return true;
        }
    }
</script>

</body>
</html>

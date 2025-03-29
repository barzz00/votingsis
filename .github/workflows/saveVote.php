<?php
session_start();
include "config.php"; // <-- Add this line to include the database connection

// If form is submitted, save vote and prevent re-voting
if (isset($_POST['submit'])) {
    $email = $_SESSION['email']; // Retrieve logged-in user's email
    $governor = $_POST['gov'];
    $viceGovernor = $_POST['vice_gov'];
    $representative = $_POST['rep'];
    $representative1 = $_POST['rep_1'];

    // Save vote to database
    $query = "INSERT INTO score (email, gov, vice_gov, rep, rep_1) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $email, $governor, $viceGovernor, $representative, $representative1);
    $stmt->execute();

    // Update login status to prevent re-voting
    $update_query = $conn->prepare("UPDATE login SET status = 0 WHERE email = ?"); // Change status to 0 (already voted)
    $update_query->bind_param("s", $email);
    $update_query->execute();

    // Set a cookie to remember that the user voted
    setcookie("voted", "yes", time() + (86400 * 365), "/"); // Cookie valid for 1 year

    // Destroy session (log user out)
    session_destroy();

    // Redirect to "already voted" page instead of login page
    header("Location: already_voted.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SVS - Voting</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container" style="padding-top:150px;">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 text-center" style="border:2px solid gray;padding:50px;">
                <h3>Vote Now</h3>
                <form action="" method="POST">
                    <label>Governor:</label>
                    <select name="gov" class="form-control" required>
                        <option value="John Doe">John Doe</option>
                        <option value="Mark Smith">Mark Smith</option>
                    </select>

                    <label>Vice-Governor:</label>
                    <select name="vice_gov" class="form-control" required>
                        <option value="Jane Doe">Jane Doe</option>
                        <option value="Alex Brown">Alex Brown</option>
                    </select>

                    <label>Representative:</label>
                    <select name="rep" class="form-control" required>
                        <option value="Alice Green">Alice Green</option>
                        <option value="David Lee">David Lee</option>
                    </select>

                    <label>Second Representative:</label>
                    <select name="rep_1" class="form-control" required>
                        <option value="Emily White">Emily White</option>
                        <option value="Chris Black">Chris Black</option>
                    </select>

                    <br>
                    <button type="submit" name="submit" class="btn btn-success">Submit Vote</button>
                </form>
            </div>
            <div class="col-sm-4"></div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>

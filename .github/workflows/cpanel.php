<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SVS</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>

    <style>
      .headerFont{
        font-family: 'Ubuntu', sans-serif;
        font-size: 24px;
      }

      .subFont{
        font-family: 'Raleway', sans-serif;
        font-size: 14px;
      }
      
      .specialHead{
        font-family: 'Oswald', sans-serif;
      }

      .normalFont{
        font-family: 'Roboto Condensed', sans-serif;
      }
    </style>

  </head>
  <body>
    
  <div class="container">
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#example-nav-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <div class="navbar-header">
          <a href="cpanel.php" class="navbar-brand headerFont text-lg">SIS Voting System In College Department</a>
        </div>

        <div class="collapse navbar-collapse" id="example-nav-collapse">
          <ul class="nav navbar-nav">
            <li><a href="nomination.html"><span class="subFont"><strong>Nominations</strong></span></a></li>
            <li><a href="changePassword.php"><span class="subFont"><strong>Change Password</strong></span></a></li>
            <li><a href="users.php"><span class="subFont"><strong>Voters</strong></span></a></li> 
            <li><a href="feedbackReport.php"><span class="subFont"><strong>Feedback Report</strong></span></a></li> 
          </ul>
          <span class="normalFont"><a href="index.html" class="btn btn-danger navbar-right navbar-btn" style="border-radius:0%">Logout</a></span></button>
        </div>
      </div>
    </nav>

    <div class="container" style="padding:100px;">
      <div class="row">
        <div class="col-sm-12" style="border:2px outset gray;">
          
          <div class="page-header text-center">
            <h2 class="specialHead">ADMIN PANEL</h2>
            <p class="normalFont">Select a position to view results</p>
          </div>
          
          <div class="col-sm-12">
            <?php
              require 'config.php';
              $conn = mysqli_connect($hostname, $username, $password, $database);
              if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
              }

              $positions = [
                'gov' => 'Governor', 
                'vice_gov' => 'Vice Governor', 
                'rep' => 'Representative', 
                'rep_1' => 'Representative 2'
              ];
              
              foreach ($positions as $column => $title) {
                echo "<h3><a href='position.php?position=$column' style='text-decoration: none; color: black;'><strong>$title</strong></a></h3><hr>";
              }
              
              // Fetch overall results
              $overallResults = [];
              foreach ($positions as $column => $title) {
                  $sql = "SELECT $column, COUNT(*) as votes FROM score GROUP BY $column";
                  $result = mysqli_query($conn, $sql);
                  while ($row = mysqli_fetch_assoc($result)) {
                      $overallResults[$row[$column]] = $row['votes'];
                  }
              }
              mysqli_close($conn);
            ?>

            <h3 class="text-center">Overall Voting Results</h3>
            <canvas id="overallChart"></canvas>
            <script>
              const ctx = document.getElementById('overallChart').getContext('2d');
              const overallResults = <?php echo json_encode($overallResults); ?>;
              const labels = Object.keys(overallResults);
              const data = Object.values(overallResults);

              new Chart(ctx, {
                  type: 'bar',
                  data: {
                      labels: labels,
                      datasets: [{
                          label: 'Votes',
                          data: data,
                          backgroundColor: 'rgba(54, 162, 235, 0.5)',
                          borderColor: 'rgba(54, 162, 235, 1)',
                          borderWidth: 1
                      }]
                  },
                  options: {
                      responsive: true,
                      scales: {
                          y: {
                              beginAtZero: true
                          }
                      }
                  }
              });
            </script>
          </div>
        </div>
      </div>
    </div>
  </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

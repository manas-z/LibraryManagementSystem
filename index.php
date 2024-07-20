<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    echo '<script>window.location.href = "registration.php";</script>';
   exit();
}

include 'database.php';
$memberId = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Welcome</title>
</head>
<body>
    <div class="container">
        <?php echo"<h1>Welcome ". $_SESSION['username'];  ?> </h1>
        <h3>What action would you like to perform?</h3>
        <p>To issue a book click <a href="issue.php">Here</a></p>
        <p>To return a book click <a href="return.php">Here</a></p>
        <a href="logout.php" class="btn btn-warning">Logout</a>
    </div>
</body>
</html>
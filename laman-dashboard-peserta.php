<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== 2){
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PPB</title>
</head>
<body>
    <?php
        echo "<h1>".htmlspecialchars($_SESSION['id'])."</h1>";
    ?>
    <a href="laman-dashboard-peserta.php">Dashboard</a>
    <a href="laman-berkas-pendaftaran.php">Berkas Pendaftaran</a>
</body>
</html>

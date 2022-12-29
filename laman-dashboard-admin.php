<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== 1) {
    header("location: laman-login-admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | PPB</title>
</head>
<body>
    <?php
        echo "<h1>".htmlspecialchars($_SESSION['username'])."</h1>";
    ?>
    <a href="laman-dashboard-admin.php">Dashboard</a>
    <a href="laman-verifikasi-berkas.php">Verifikasi Berkas</a>
</body>
</html>

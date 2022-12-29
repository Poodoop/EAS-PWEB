<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== 1) {
        header("location: laman-login-admin.php");
        exit;
    }

    include("config.php");
    $username = $_SESSION['username'];

    if(isset($_POST['cek'])) {

        $nama = $_POST['nama'];
        $nik = $_POST['nik'];
        $sql = "UPDATE admin SET u_nik = '$nik' WHERE a_username = '$username'";
        $stmt = mysqli_query($db, $sql);
    }

    if(isset($_POST['back'])) {

        $sql = "UPDATE admin SET u_nik = NULL WHERE a_username = '$username'";
        $stmt = mysqli_query($db, $sql);
    }

    if(isset($_POST['deny'])) {

        $nik = $_POST['nik'];
        $sql = "UPDATE peserta SET u_status = 2 WHERE u_nik = '$nik'";  
        $stmt = mysqli_query($db, $sql);
        $sql = "UPDATE admin SET u_nik = NULL WHERE a_username = '$username'";
        $stmt = mysqli_query($db, $sql);
    }

    if(isset($_POST['accept'])) {

        $sql = "SELECT p_id FROM peserta_ujian ORDER BY p_id DESC LIMIT 1";
        $stmt = mysqli_query($db, $sql);
        $lastid = mysqli_fetch_array($stmt);
        $id = 0;
        if($lastid) {
            $id = $lastid['p_id'];
        }
        $id++;
        $no_reg = "3016-923-000000$id";
        $nik = $_POST['nik'];
        $sql = "UPDATE peserta SET u_status = 3, p_no_reg = '$no_reg' WHERE u_nik = '$nik'";  
        $stmt = mysqli_query($db, $sql);
        $sql = "INSERT INTO peserta_ujian(p_id,p_no_reg,p_nik,j_id) VALUE ($id,'$no_reg','$nik',1)";  
        $stmt = mysqli_query($db, $sql);
        $sql = "UPDATE admin SET u_nik = NULL WHERE a_username = '$username'";
        $stmt = mysqli_query($db, $sql);
    }

    $sql = "SELECT * FROM admin WHERE a_username = '$username'";
    $stmt = mysqli_query($db, $sql);
    $admin = mysqli_fetch_array($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Berkas | PPB</title>
</head>
<body>
    <?php
        echo "<h1>".htmlspecialchars($_SESSION['username'])."</h1>";
    ?>
    <a href="laman-dashboard-admin.php">Dashboard</a>
    <a href="laman-verifikasi-berkas.php">Verifikasi Berkas</a>
    
    <?php
        if(empty($admin['u_nik'])) {
            $sql = "SELECT u_nik, u_nama FROM peserta WHERE u_status = 1";
            $query = mysqli_query($db, $sql);
    
            while($calon = mysqli_fetch_array($query)){
                echo "<form method='POST'>";
                echo "<input type='text' name='nama' readonly value=".$calon['u_nama'].">";
                echo "<input type='text' name='nik' readonly value=".$calon['u_nik'].">";
                echo "<input type='submit' name='cek' value='Cek Berkas'>";
                echo "</form>";
            }
        } else {
            echo "<form method='POST'>";
            echo "<input type='submit' name='back' value='Kembali'>";
            echo "</form>";
            $sql = "SELECT * FROM peserta WHERE u_nik = '".$admin['u_nik']."'";
            $query = mysqli_query($db, $sql);
            $calon = mysqli_fetch_array($query);
            echo "<form method='POST'>";
            echo "<input type='text' name='nama' readonly value=".$calon['u_nama'].">";
            echo "<input type='text' name='nik' readonly value=".$calon['u_nik'].">";
            echo "<input type='submit' name='deny' value='Tolak'>";
            echo "<input type='submit' name='accept' value='Terima Berkas'>";
            echo "</form>";
        }
    ?>
</body>
</html>

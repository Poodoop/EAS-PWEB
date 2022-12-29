<?php

include("config.php");

// cek apakah tombol daftar sudah diklik atau blum?
if(isset($_POST['signup'])){

    // ambil data dari formulir
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // buat query
    $sql = "INSERT INTO peserta (u_nik,u_password,u_nama,u_email) VALUE ('$nik','$pass','$nama','$email')";
    
    $query = mysqli_query($db, $sql);
    
    if( $query ) {
        // kalau berhasil alihkan ke halaman index.php dengan status=sukses
        $path = "images/$nik";
        mkdir($path, 0777, TRUE);
        header('Location: index.php?status=sukses');
    } else {
        // kalau gagal alihkan ke halaman indek.php dengan status=gagal
        header('Location: index.php?status=gagal');
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up | PPB</title>
</head>
<body>
    <form action="" method="POST">
        <label for="">Nama</label>
        <input type="text" name="nama" >
        <label for="">NIK</label>
        <input type="text" name="nik">
        <label for="">Email</label>
        <input type="email" name="email">
        <label for="">Password</label>
        <input type="password" name="pass">
        <label for="">Confirm Password</label>
        <input type="password" name="cpass">
        <input type="submit"  name="signup" value="Sign Up">
    </form>
    <a href="index.php">Sign In</a>
</body>
</html>
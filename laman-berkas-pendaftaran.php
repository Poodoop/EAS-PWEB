<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== 2){
        header("location: index.php");
        exit;
    } 

    include("config.php");
    require('fpdf/fpdf.php');

    $nik = $_SESSION['id'];

    if(isset($_POST['batal'])) {

        $query = "UPDATE peserta SET u_status = 0 WHERE u_nik = '$nik'";
        $stmt = mysqli_query($db, $query);
    }

    function simpan_data ($db) {
        $sql = "SELECT * FROM peserta WHERE u_nik = '".$_SESSION['id']."'";
        $stmt = mysqli_query($db, $sql);
        $pegawai = mysqli_fetch_array($stmt);

        $nama = ($_POST['nama']);
        $nik = $_POST['nik'];
        $email = $_POST['email'];
        $jk = isset($_POST['jk']) ? $_POST['jk'] : '';
        $tl = $_POST['tl'];
        $domisili = $_POST['dom'];
        $alamat = $_POST['almt'];
        $hp = $_POST['hp'];
        $instansi = $_POST['int'];
        $a_instansi = $_POST['almt_int'];
        $jabatan = $_POST['jab'];
        $pasfoto = $_FILES['pasfoto']['name'];
        $ktp = $_FILES['ktp']['name'];
        $ijazah = $_FILES['ijazah']['name'];
        
        if(empty($pasfoto)) {
            $pasfoto_dir = $pegawai['u_pas_foto'] ? $pegawai['u_pas_foto'] : "";
        }
        else {
            $pasfoto_tmp = $_FILES['pasfoto']['tmp_name'];
            $pasfoto_dir = "pasfoto_$pasfoto";
            $pasfoto_dir = move_uploaded_file($pasfoto_tmp, "images/$nik/$pasfoto_dir") ? $pasfoto_dir : "";
        }

        if(empty($ktp)) {
            $ktp_dir = $pegawai['u_ktp'] ? $pegawai['u_ktp'] : "";
        }
        else {
            $ktp_tmp = $_FILES['ktp']['tmp_name'];
            $ktp_dir = "ktp_$ktp";
            $ktp_dir = move_uploaded_file($ktp_tmp, "images/$nik/$ktp_dir") ? $ktp_dir : "";
        }

        if(empty($ijazah)) {
            $ijazah_dir = $pegawai['u_ijazah'] ? $pegawai['u_ijazah'] : "";
        }
        else {
            $ijazah_tmp = $_FILES['ijazah']['tmp_name'];
            $ijazah_dir = "ijazah_$ijazah";
            $ijazah_dir = move_uploaded_file($ijazah_tmp, "images/$nik/$ijazah_dir") ? $ijazah_dir : "";
        }

        $query = "UPDATE peserta SET 
        u_nama = '$nama', 
        u_email = '$email',
        u_kelamin = ".(empty($jk) ? 'NULL' : "'$jk'").", 
        u_tanggal_lahir = ".(empty($tl) ? 'NULL' : "'$tl'").", 
        u_domisili = ".(empty($domisili) ? 'NULL' : "'$domisili'").", 
        u_alamat = ".(empty($alamat) ? 'NULL' : "'$alamat'").", 
        u_hp = ".(empty($hp) ? 'NULL' : "'$hp'").", 
        u_instansi = ".(empty($instansi) ? 'NULL' : "'$instansi'").", 
        u_alamat_instansi = ".(empty($a_instansi) ? 'NULL' : "'$a_instansi'").", 
        u_jabatan = ".(empty($jabatan) ? 'NULL' : "'$jabatan'").",
        u_pas_foto = ".(empty($pasfoto_dir) ? 'NULL' : "'$pasfoto_dir'").",
        u_ktp = ".(empty($ktp_dir) ? 'NULL' : "'$ktp_dir'").",
        u_ijazah = ".(empty($ijazah_dir) ? 'NULL' : "'$ijazah_dir'")."
        WHERE u_nik = '$nik'";

        $stmt = mysqli_query($db, $query);

        return $stmt;
    }

    if(isset($_POST['simpan'])) {

        if(simpan_data($db)) {
            echo "<script type='text/javascript'>alert('Data disimpan');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Data gagal disimpan');</script>";
        }
    }

    if(isset($_POST['ajukan'])) {
        
        if(simpan_data($db)) {
            $sql = "SELECT * FROM peserta WHERE u_nik = '".$_SESSION['id']."'";
            $stmt = mysqli_query($db, $sql);
            $row = mysqli_fetch_array($stmt);
            $is_valid = true;
            
            for($i = 0; $i < 15; $i++) {
                // echo $row[$i];
                if(empty($row[$i])) {
                    $is_valid = false;
                    break;
                }
            }
            if($is_valid) {
                $sql = "UPDATE peserta SET u_status = 1 WHERE u_nik = '$nik'";
                $stmt = mysqli_query($db, $sql);
            } else {
                echo "Mohon lengkapi seluruh berkas sebelum membuat ajuan";
            }
            
        } else {
            echo "<script type='text/javascript'>alert('Gagal membuat ajuan');</script>";
        }
    }

    if(isset($_POST['pdf'])) {
        $pdf = new FPDF('l','mm','A5');
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(190,9,'KARTU PESERTA UJIAN PENERIMAAN PEGAWAI BARU',0,1,'C');
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(190,8,'KEMENTERIAN KELAUTAN DAN PERIKANAN',0,1,'C');
        
        $pdf->Cell(10,7,'',0,1);

        $sql = "SELECT * FROM peserta WHERE u_nik = '".$_SESSION['id']."'";
        $stmt = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($stmt);

        $pdf->SetFont('Arial','',11);
        $pdf->Cell(50,6,'Instansi',0,0);
        $pdf->Cell(140,6,$row['u_instansi'],0,1);
        $pdf->Cell(50,6,'Alamat Instansi',0,0);
        $pdf->Cell(140,6,$row['u_alamat_instansi'],0,1);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(50,7,'NIK',0,0);
        $pdf->Cell(140,7,$row['u_nik'],0,1);
        $pdf->SetFont('Arial','',16);
        $pdf->Cell(50,8,'No Peserta',0,0);
        $pdf->Cell(140,8,$row['p_no_reg'],0,1);
        $pdf->SetFont('Arial','',14);
        $pdf->Cell(50,7,'Nama',0,0);
        $pdf->Cell(140,7,$row['u_nama'],0,1);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(50,6,'Jenis Kelamin',0,0);
        $pdf->Cell(140,6,$row['u_kelamin'],0,1);
        $pdf->Cell(50,6,'Tanggal Lahir',0,0);
        $pdf->Cell(140,6,$row['u_tanggal_lahir'],0,1);
        $pdf->Cell(50,6,'Domisili',0,0);
        $pdf->Cell(140,6,$row['u_domisili'],0,1);
        $pdf->Cell(50,6,'Jabatan',0,0);
        $pdf->Cell(140,6,$row['u_jabatan'],0,1);
        $pdf->Cell(10,3,'',0,1);
        $pdf->Image("images/".$row['u_nik']."/".$row['u_pas_foto']."", 170, 35, 30, 40);

        $sql = "SELECT * FROM jadwal_ujian WHERE j_id = (SELECT j_id FROM peserta_ujian WHERE p_nik = '".$row['u_nik']."')";
        $stmt = mysqli_query($db, $sql);
        $row = mysqli_fetch_array($stmt);

        $pdf->Cell(50,6,'Tanggal Pelaksanaan Tes',0,0);
        $pdf->Cell(140,6,$row['j_hari'],0,1);
        $pdf->Cell(50,6,'Lokasi Tes',0,0);
        $pdf->Cell(140,6,$row['j_lokasi'],0,1);
        $pdf->Cell(50,6,'Sesi Tes',0,0);
        $pdf->Cell(140,6,$row['j_jam'],0,1);
        $pdf->Cell(50,6,'Ruangan Tes',0,0);
        $pdf->Cell(140,6,$row['j_ruangan'],0,1);

        


        $pdf->Output("I");
    }

    $sql = "SELECT * FROM peserta WHERE u_nik = '$nik'";
    $stmt = mysqli_query($db, $sql);
    $pegawai = mysqli_fetch_array($stmt);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        echo "<h1>".htmlspecialchars($_SESSION['id'])."</h1>";
    ?>
    <a href="laman-dashboard-peserta.php">Dashboard</a>
    <a href="laman-berkas-pendaftaran.php">Berkas Pendaftaran</a>
    <?php
        if($pegawai['u_status'] == 2) {
            echo "Berkas ditolak oleh admin, silakan cek kelengkapan berkas sebelum mengajukan ulang";
        } elseif($pegawai['u_status'] == 3) {
            echo "Berkas anda telah diterima, mohon segera cetak kartu ujian";
            echo "<form method='POST'>";
            echo "<input type='submit' name='pdf' value='Cetak Kartu Ujian'>";
            echo "</form>";
        }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Nama</label>
        <input type="text" name="nama" value="<?php echo $pegawai['u_nama']?>">
        <label>NIK</label>
        <input type="text" name="nik"  value="<?php echo $pegawai['u_nik']?>">
        <label>Email</label>
        <input type="email" name="email" value="<?php echo $pegawai['u_email']?>">
        <label>Jenis Kelamin</label>
        <?php $jk = $pegawai['u_kelamin']; ?>
            <label><input type="radio" name="jk" value="Laki-laki" <?php echo ($jk == 'Laki-laki') ? "checked": "" ?> /> Laki-laki</label>
            <label><input type="radio" name="jk" value="Perempuan" <?php echo ($jk == 'Perempuan') ? "checked": "" ?> /> Perempuan</label>
        <label>Tanggal Lahir</label>
        <input type="date" name="tl" value="<?php echo $pegawai['u_tanggal_lahir']?>">
        <label>Domisili</label>
        <input type="text" name="dom" value="<?php echo $pegawai['u_domisili']?>">
        <label>Alamat</label>
        <textarea name="almt"><?php echo $pegawai['u_alamat']?></textarea>
        <label>No Telepon</label>
        <input type="text" name="hp" value="<?php echo $pegawai['u_hp']?>">
        <label>Instansi</label>
        <input type="text" name="int" value="<?php echo $pegawai['u_instansi']?>">
        <label>Alamat Instansi</label>
        <textarea name="almt_int"><?php echo $pegawai['u_alamat_instansi']?></textarea>
        <label>Jabatan</label>
        <input type="text" name="jab" value="<?php echo $pegawai['u_jabatan']?>">
        <label>Pas Foto</label>
        <?php if($pegawai['u_pas_foto']) 
            echo "<img width='300px' src='images/".$pegawai['u_nik']."/".$pegawai['u_pas_foto']."'>" ?>
        <input type="file" name="pasfoto">
        <label>KTP</label>
        <?php if($pegawai['u_ktp']) 
            echo "<img width='300px' src='images/".$pegawai['u_nik']."/".$pegawai['u_ktp']."'>" ?>
        <input type="file" name="ktp">
        <label>Ijazah Terakhir</label>
        <?php if($pegawai['u_ijazah']) 
            echo "<img width='300px' src='images/".$pegawai['u_nik']."/".$pegawai['u_ijazah']."'>" ?>
        <input type="file" name="ijazah">
        <?php
            if($pegawai['u_status'] == 0 || $pegawai['u_status'] == 2) { echo "
                <input type='submit' name='ajukan' value='Buat Ajuan'>
                <input type='submit' name='simpan' value='Simpan'>";} 
            elseif($pegawai['u_status'] == 1)
                echo "<input type='submit' name='batal' value='Batalkan Ajuan'>";
        ?>
    </form>
</body>
</html>
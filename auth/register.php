<?php
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");
$msg = "";

if(isset($_POST['register'])){
    $nama = $_POST['nama'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $nomor_kamar = $_POST['nomor_kamar'];
    $password = $_POST['password'];

    // cek email
    $cekEmail = mysqli_query($conn,"SELECT * FROM penghuni WHERE email='$email'");
    if(mysqli_num_rows($cekEmail) > 0){
        $msg = "Email sudah terdaftar!";
    } else {

        // cek kamar
        $cekKamar = mysqli_query($conn,"
            SELECT * FROM kamar 
            WHERE nomor_kamar='$nomor_kamar' 
            AND status='kosong'
        ");

        if(mysqli_num_rows($cekKamar) == 0){
            $msg = "Kamar tidak tersedia / sudah terisi!";
        } else {

            // upload foto
            $foto = "";
            if(!empty($_FILES['foto']['name'])){
                $foto = time() . "_" . $_FILES['foto']['name'];
                move_uploaded_file(
                    $_FILES['foto']['tmp_name'],
                    "../assets/img/penghuni/".$foto
                );
            }

            mysqli_query($conn,"
                INSERT INTO penghuni
                (foto,nama,no_hp,email,alamat,nomor_kamar,password,status)
                VALUES
                (
                    '$foto',
                    '$nama',
                    '$no_hp',
                    '$email',
                    '$alamat',
                    '$nomor_kamar',
                    '$password',
                    'pending'
                )
            ");

            mysqli_query($conn,"
                INSERT INTO notifikasi(tipe,judul,pesan,status)
                VALUES(
                    'register',
                    'Penghuni Baru',
                    'Ada penghuni baru mendaftar: $nama',
                    'belum_dibaca'
                )
            ");

            $msg = "Register berhasil! Tunggu approval admin.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Penghuni</title>
    <style>
        body{
            background:#f5f7fb;
            font-family:Arial;
        }

        .box{
            width:420px;
            margin:40px auto;
            background:white;
            padding:30px;
            border-radius:20px;
            box-shadow:0 4px 20px rgba(0,0,0,.08);
        }

        h2{
            text-align:center;
            margin-bottom:25px;
        }

        input, textarea{
            width:100%;
            padding:14px;
            margin-bottom:15px;
            border:1px solid #ddd;
            border-radius:10px;
            box-sizing:border-box;
        }

        textarea{
            height:80px;
            resize:none;
        }

        button{
            width:100%;
            padding:14px;
            border:none;
            background:#2563eb;
            color:white;
            border-radius:10px;
            font-size:16px;
            cursor:pointer;
        }

        .msg{
            margin-bottom:15px;
            color:red;
            text-align:center;
            font-weight:bold;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Register Penghuni</h2>

    <?php if($msg!=""){ ?>
        <div class="msg"><?= $msg ?></div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">
        <input name="nama" placeholder="Nama" required>
        <input name="no_hp" placeholder="No HP" required>
        <input name="email" type="email" placeholder="Email" required">
        <input name="password" type="password" placeholder="Password" required>
        <textarea name="alamat" placeholder="Alamat"></textarea>
        <input name="nomor_kamar" placeholder="Nomor Kamar (contoh A01)" required>
        <input type="file" name="foto">
        <button name="register">Register</button>
    </form>
</div>

</body>
</html>
<?php
include "../auth/user_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$user_id = $_SESSION['user_id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM penghuni
WHERE id='$user_id'
"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Akun</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial;
        }

        body{
            background:#f5f7fb;
        }

        .container{
            padding:35px;
        }

        .title-box{
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
        }

        .profile-box{
            margin-top:25px;
            background:white;
            padding:30px;
            border-radius:18px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
            display:grid;
            grid-template-columns:280px 1fr;
            gap:30px;
            align-items:center;
        }

        .photo-box{
            text-align:center;
        }

        .photo-box img{
            width:180px;
            height:180px;
            object-fit:cover;
            border-radius:50%;
            border:4px solid #dbeafe;
        }

        .info p{
            margin-bottom:18px;
            font-size:16px;
        }

        .label{
            font-weight:bold;
            display:inline-block;
            width:140px;
        }

        .status{
            display:inline-block;
            padding:6px 12px;
            border-radius:10px;
            font-size:14px;
            font-weight:bold;
            background:#dcfce7;
            color:#15803d;
        }

        .btn-box{
            margin-top:30px;
            text-align:center;
        }

        .btn{
            background:#2563eb;
            color:white;
            padding:14px 26px;
            text-decoration:none;
            border-radius:12px;
            font-weight:bold;
            display:inline-block;
        }

        .btn:hover{
            background:#1d4ed8;
        }
    </style>
</head>
<body>

<?php include "../includes/user_navbar.php"; ?>

<div class="container">

    <div class="title-box">
        <h2>Akun Saya</h2>
        <p>Kelola profil penghuni</p>
    </div>

    <div class="profile-box">

        <div class="photo-box">
            <img src="../assets/img/penghuni/<?= $user['foto'] ?>" alt="Foto">
        </div>

        <div class="info">
            <p><span class="label">Nama</span>: <?= $user['nama'] ?></p>
            <p><span class="label">No HP</span>: <?= $user['no_hp'] ?></p>
            <p><span class="label">Email</span>: <?= $user['email'] ?></p>
            <p><span class="label">Alamat</span>: <?= $user['alamat'] ?></p>
            <p><span class="label">Nomor Kamar</span>: <?= $user['nomor_kamar'] ?></p>

            <p>
                <span class="label">Status</span>:
                <span class="status"><?= ucfirst($user['status']) ?></span>
            </p>
        </div>

    </div>

    <div class="btn-box">
        <a href="edit_akun.php" class="btn">Edit Profil</a>
    </div>

</div>

</body>
</html>
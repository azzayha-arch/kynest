<?php
include "../auth/auth_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

/* TANDAI DIBACA */
if(isset($_GET['read'])){
    $id = (int)$_GET['read'];
    mysqli_query($conn,"UPDATE notifikasi SET status='dibaca' WHERE id=$id");
    header("Location: notifikasi.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .notif-card{
            background:white;
            padding:20px;
            border-radius:18px;
            margin-bottom:16px;
            box-shadow:0 4px 15px rgba(0,0,0,.08);
            border-left:7px solid #2563eb;
        }

        .notif-title{
            font-size:18px;
            font-weight:bold;
            margin-bottom:8px;
        }

        .notif-pesan{
            color:#444;
            margin-bottom:10px;
        }

        .notif-time{
            color:gray;
            font-size:13px;
        }

        .dibaca{
            opacity:0.6;
        }

        .btn{
            display:inline-block;
            margin-top:12px;
            padding:8px 14px;
            background:#2563eb;
            color:white;
            border-radius:8px;
            text-decoration:none;
        }

        .penghuni{
            border-left-color:#3b82f6;
        }

        .pembayaran{
            border-left-color:#10b981;
        }

        .tunggakan{
            border-left-color:#ef4444;
        }

        .keluar{
            border-left-color:#f59e0b;
        }
    </style>
</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<div style="margin-left:270px;padding:30px;">
    <h1>Notifikasi</h1>

    <?php
    $data = mysqli_query($conn,"SELECT * FROM notifikasi ORDER BY created_at DESC");

    while($row=mysqli_fetch_assoc($data)){
    ?>
        <div class="notif-card <?= $row['tipe'] ?> <?= $row['status']=='dibaca' ? 'dibaca' : '' ?>">
            <div class="notif-title">
                <?= $row['judul'] ?>
            </div>

            <div class="notif-pesan">
                <?= $row['pesan'] ?>
            </div>

            <div class="notif-time">
                <?= $row['created_at'] ?>
            </div>

            <?php if($row['status']=='belum_dibaca'){ ?>
                <a class="btn" href="?read=<?= $row['id'] ?>">
                    Tandai Dibaca
                </a>
            <?php } ?>
        </div>
    <?php } ?>

</div>
</body>
</html>
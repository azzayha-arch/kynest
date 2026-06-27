<?php
include "../auth/user_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$data = mysqli_query($conn,"
SELECT * FROM notifikasi
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi</title>

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

        .notif-box{
            margin-top:25px;
            display:flex;
            flex-direction:column;
            gap:18px;
        }

        .notif-card{
            background:white;
            padding:22px;
            border-radius:16px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
            border-left:5px solid #2563eb;
        }

        .notif-card h3{
            margin-bottom:10px;
            font-size:18px;
        }

        .notif-card p{
            color:#555;
            margin-bottom:12px;
        }

        .time{
            color:gray;
            font-size:14px;
        }

        .empty{
            margin-top:25px;
            background:white;
            padding:40px;
            border-radius:18px;
            text-align:center;
            color:gray;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<?php include "../includes/user_navbar.php"; ?>

<div class="container">

    <div class="title-box">
        <h2>Notifikasi</h2>
        <p>Lihat informasi terbaru dari admin</p>
    </div>

    <?php if(mysqli_num_rows($data) > 0){ ?>
        <div class="notif-box">

            <?php while($row = mysqli_fetch_assoc($data)){ ?>
                <div class="notif-card">
                    <h3><?= $row['judul'] ?></h3>
                    <p><?= $row['pesan'] ?></p>
                    <div class="time"><?= $row['created_at'] ?></div>
                </div>
            <?php } ?>

        </div>
    <?php } else { ?>
        <div class="empty">
            <h3>Belum ada notifikasi</h3>
        </div>
    <?php } ?>

</div>

</body>
</html>
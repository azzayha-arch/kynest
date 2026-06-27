<?php
include "../auth/user_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$user_id = $_SESSION['user_id'];

$data = mysqli_query($conn,"
SELECT * FROM pembayaran
WHERE penghuni_id='$user_id'
ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pembayaran</title>

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

        .table-box{
            margin-top:25px;
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
            overflow-x:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th, td{
            padding:16px;
            text-align:center;
            border-bottom:1px solid #eee;
        }

        th{
            background:#f8fafc;
        }

        .status{
            padding:7px 12px;
            border-radius:10px;
            font-size:14px;
            font-weight:bold;
        }

        .pending{
            background:#fef3c7;
            color:#b45309;
        }

        .lunas{
            background:#dcfce7;
            color:#15803d;
        }

        .ditolak{
            background:#fee2e2;
            color:#b91c1c;
        }

        .bukti-btn{
            text-decoration:none;
            background:#2563eb;
            color:white;
            padding:8px 14px;
            border-radius:10px;
            font-size:14px;
        }

        .empty{
            text-align:center;
            padding:40px;
            color:gray;
        }
    </style>
</head>
<body>

<?php include "../includes/user_navbar.php"; ?>

<div class="container">

    <div class="title-box">
        <h2>Riwayat Pembayaran</h2>
        <p>Lihat pembayaran yang sudah dilakukan</p>
    </div>

    <div class="table-box">
        <?php if(mysqli_num_rows($data) > 0){ ?>

        <table>
            <tr>
                <th>Bulan</th>
                <th>Total Tagihan</th>
                <th>Jumlah Bayar</th>
                <th>Status</th>
                <th>Bukti</th>
            </tr>

            <?php while($row = mysqli_fetch_assoc($data)){ ?>
            <tr>
                <td><?= $row['bulan'] ?></td>
                <td>Rp <?= number_format($row['total_tagihan']) ?></td>
                <td>Rp <?= number_format($row['jumlah_bayar']) ?></td>
                <td>
                    <span class="status <?= $row['status'] ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td>
                    <?php if($row['bukti_bayar']){ ?>
                        <a class="bukti-btn"
                           href="../assets/img/bukti/<?= $row['bukti_bayar'] ?>"
                           target="_blank">
                           Lihat
                        </a>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>

        <?php } else { ?>
            <div class="empty">
                <h3>Belum ada riwayat pembayaran</h3>
            </div>
        <?php } ?>
    </div>

</div>

</body>
</html>
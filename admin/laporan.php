<?php
include "../auth/auth_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

/* SUMMARY */
$total_penghuni = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM penghuni"));

$total_lunas = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM pembayaran WHERE status='lunas'"));

$total_pending = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM pembayaran WHERE status='pending'"));

$pendapatan = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(jumlah_bayar) as total FROM pembayaran WHERE status='lunas'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .cards{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 4px 15px rgba(0,0,0,.08);
        }

        .card h3{
            margin:0;
            color:#666;
            font-size:16px;
        }

        .card h2{
            margin-top:15px;
            color:#1e3a8a;
        }

        .page-box{
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 4px 15px rgba(0,0,0,.08);
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th{
            background:#1e3a8a;
            color:white;
            padding:15px;
        }

        td{
            padding:15px;
            border-bottom:1px solid #ddd;
            text-align:center;
        }

        .badge{
            padding:6px 14px;
            color:white;
            border-radius:20px;
            font-size:13px;
            font-weight:bold;
        }

        .pending{background:#f59e0b;}
        .lunas{background:#10b981;}
        .ditolak{background:#ef4444;}
    </style>
</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<div style="margin-left:270px;padding:30px;">
    <h1>Laporan Pembayaran</h1>

    <div class="cards">
        <div class="card">
            <h3>Total Penghuni</h3>
            <h2><?= $total_penghuni ?></h2>
        </div>

        <div class="card">
            <h3>Pembayaran Lunas</h3>
            <h2><?= $total_lunas ?></h2>
        </div>

        <div class="card">
            <h3>Pending</h3>
            <h2><?= $total_pending ?></h2>
        </div>

        <div class="card">
            <h3>Total Pendapatan</h3>
            <h2>Rp <?= number_format($pendapatan['total'] ?? 0) ?></h2>
        </div>
    </div>

    <div class="page-box">
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kamar</th>
                <th>Bulan</th>
                <th>Tagihan</th>
                <th>Dibayar</th>
                <th>Status</th>
            </tr>

            <?php
            $query = mysqli_query($conn,"
                SELECT pembayaran.*, penghuni.nama, penghuni.nomor_kamar
                FROM pembayaran
                JOIN penghuni ON pembayaran.penghuni_id = penghuni.id
                ORDER BY pembayaran.id DESC
            ");

            while($row=mysqli_fetch_assoc($query)){
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['nomor_kamar'] ?></td>
                <td><?= $row['bulan'] ?></td>
                <td>Rp <?= number_format($row['total_tagihan']) ?></td>
                <td>Rp <?= number_format($row['jumlah_bayar']) ?></td>
                <td>
                    <span class="badge <?= $row['status'] ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
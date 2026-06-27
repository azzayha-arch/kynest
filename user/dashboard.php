<?php
include "../auth/user_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$user_id = $_SESSION['user_id'];

$user = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM penghuni WHERE id='$user_id'"));

$tagihan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM pembayaran
WHERE penghuni_id='$user_id'
ORDER BY id DESC
LIMIT 1
"));

$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(jumlah_bayar) as total_bayar
FROM pembayaran
WHERE penghuni_id='$user_id'
"));

$riwayat = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM pembayaran
WHERE penghuni_id='$user_id'
AND status='lunas'
ORDER BY id DESC
LIMIT 1
"));

$total_bayar = $total['total_bayar'] ?? 0;
?>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard User</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

        .welcome{
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
        }

        .welcome h2{
            margin-bottom:8px;
        }

        .cards{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:20px;
            margin-top:25px;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:16px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
        }

        .card p{
            color:gray;
            margin-bottom:10px;
        }

        .card h2{
            font-size:22px;
        }

        .quick-actions{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:20px;
            margin-top:30px;
        }

        .action-card{
            background:white;
            border-radius:18px;
            padding:20px;
            display:flex;
            align-items:center;
            gap:15px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
            cursor:pointer;
            transition:all 0.25s ease;
            border:2px solid transparent;
            
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover{
            transform:translateY(-4px);
            background:#f3f4f6;
            box-shadow:0 8px 20px rgba(0,0,0,0.08);
        }

        .action-card:active{
            transform:scale(0.98);
        }

        .action-card i{
            font-size:34px;
        }

        .action-text b{
            display:block;
            margin-bottom:5px;
            color:#111827;
            font-size:16px;
        }

        .action-text small{
            color:#6b7280;
        }

        .blue{
            border:2px solid #3b82f6;
            background:#eff6ff;
        }

        .blue i{
            color:#3b82f6;
        }

        .green{
            border:2px solid #22c55e;
            background:#f0fdf4;
        }

        .green i{
            color:#22c55e;
        }

        .red{
            border:2px solid #ef4444;
            background:#fef2f2;
        }

        .red i{
            color:#ef4444;
        }

        .bottom{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
            margin-top:30px;
        }

        .box{
            background:white;
            padding:25px;
            border-radius:16px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
            min-height:180px;
        }
    </style>
</head>
<body>

<?php include "../includes/user_navbar.php"; ?>

<div class="container">

    <div class="welcome">
        <h2>Halo, <?= $user['nama'] ?> 👋</h2>
        <p>Nomor Kamar: <?= $user['nomor_kamar'] ?></p>
    </div>

    <div class="cards">
        <div class="card">
            <p>Tagihan Bulan Ini</p>
            <h2>Rp <?= number_format($tagihan['total_tagihan'] ?? 0) ?></h2>
        </div>

        <div class="card">
            <p>Total Pembayaran</p>
            <h2>Rp <?= number_format($total_bayar) ?></h2>
        </div>

        <div class="card">
            <p>Riwayat Terakhir</p>
            <h2>Rp <?= number_format($riwayat['jumlah_bayar'] ?? 0) ?></h2>
        </div>

        <div class="card">
            <p>Status</p>
            <h2><?= $tagihan['status'] ?? '-' ?></h2>
        </div>
    </div>

    <div class="quick-actions">
        <div class="action-card blue">
            <i class="bi bi-wallet2"></i>
            <div class="action-text">
                <b>Bayar Sekarang</b>
                <small>Lakukan pembayaran tagihan</small>
            </div>
        </div>

        <a href="perpanjang.php" class="action-card green">
            <i class="bi bi-arrow-repeat"></i>
            <div class="action-text">
                <b>Perpanjang Tempo</b>
                <small>Ajukan perpanjangan jatuh tempo</small>
            </div>
        </a>

        <div class="action-card red">
            <i class="bi bi-box-arrow-right"></i>
            <div class="action-text">
                <b>Keluar dari Kos</b>
                <small>Ajukan keluar dari kos</small>
            </div>
        </div>
    </div>

    <div class="bottom">
        <div class="box">
            <h3>Informasi</h3>
            <br>
            <p>Pastikan pembayaran dilakukan sebelum jatuh tempo.</p>
        </div>

        <div class="box">
            <h3>Grafik Pembayaran</h3>
            <br>
            <p>(sementara dummy dulu)</p>
        </div>
    </div>

</div>

</body>
</html>
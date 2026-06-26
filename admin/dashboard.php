<?php
include "../auth/auth_check.php";

$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$total_penghuni = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM penghuni"));
$kamar_terisi = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM kamar WHERE status='terisi'"));
$kamar_kosong = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM kamar WHERE status='kosong'"));

$total_uang_query = mysqli_query($conn,"SELECT SUM(jumlah_bayar) as total FROM pembayaran");
$total_uang = mysqli_fetch_assoc($total_uang_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<div class="main">
    <?php include "../includes/navbar.php"; ?>

    <div class="content">
        <h1>Dashboard</h1>
        <br>

        <div class="cards">
            <div class="card">
                <p>Total Penghuni</p>
                <h2><?= $total_penghuni ?></h2>
            </div>

            <div class="card">
                <p>Kamar Terisi</p>
                <h2><?= $kamar_terisi ?></h2>
            </div>

            <div class="card">
                <p>Kamar Kosong</p>
                <h2><?= $kamar_kosong ?></h2>
            </div>

            <div class="card">
                <p>Pemasukan</p>
                <h2>Rp <?= $total_uang['total'] ?? 0 ?></h2>
            </div>
        </div>
    </div>
</div>

</body>
</html>
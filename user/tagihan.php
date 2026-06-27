<?php
include "../auth/user_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$user_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

if(isset($_POST['upload_bukti'])){
    $id_pembayaran = $_POST['id_pembayaran'];

    if($_FILES['bukti']['name'] != ""){
        $nama_file = time().'_'.$_FILES['bukti']['name'];
        $tmp = $_FILES['bukti']['tmp_name'];
        $path = "../assets/img/bukti/".$nama_file;

        move_uploaded_file($tmp, $path);

        mysqli_query($conn,"
            UPDATE pembayaran 
            SET bukti_bayar='$nama_file',
                jumlah_bayar=total_tagihan,
                status='pending'
            WHERE id='$id_pembayaran'
        ");
    }

    header("Location: tagihan.php");
    exit;
}

$tagihan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM pembayaran
WHERE penghuni_id='$user_id'
ORDER BY id DESC
LIMIT 1
"));
?>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tagihan</title>
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

        .navbar{
            width:100%;
            background:white;
            padding:18px 40px;
            display:grid;
            grid-template-columns:180px 1fr 180px;
            align-items:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.05);
        }

        .logo{
            font-size:22px;
            font-weight:bold;
            color:#2563eb;
        }

        .menu{
            display:flex;
            justify-content:center;
            gap:18px;
        }

        .menu a{
            text-decoration:none;
            color:#444;
            font-weight:500;
            padding:10px 16px;
            border-radius:12px;
            position:relative;
            transition:all 0.25s ease;
        }

        .menu a:hover{
            background:#f3f4f6;
            color:#2563eb;
        }

        .menu a.active{
            
            color:#2563eb;
            font-weight:600;
        }

        .menu a.active::after{
            content:"";
            position:absolute;
            bottom:-10px;
            left:50%;
            transform:translateX(-50%);
            width:70%;
            height:3px;
            background:#2563eb;
            border-radius:10px;
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

        .content{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
            margin-top:25px;
        }

        .card{
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
        }

        .card h3{
            margin-bottom:20px;
        }

        .info{
            margin-bottom:15px;
        }

        .status{
            display:inline-block;
            padding:8px 14px;
            border-radius:12px;
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

        input[type=file]{
            width:100%;
            margin:15px 0;
            padding:10px;
        }

        button{
            width:100%;
            background:#2563eb;
            color:white;
            border:none;
            padding:14px;
            border-radius:12px;
            cursor:pointer;
            font-weight:bold;
        }

        button:hover{
            background:#1d4ed8;
        }

        .payment-box{
            margin-top:25px;
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 3px 12px rgba(0,0,0,0.05);
        }

        .payment-box p{
            margin:10px 0;
        }

        .empty{
            text-align:center;
            color:gray;
            padding:50px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">KOS APP</div>

    <div class="menu">
        <a href="dashboard.php">Beranda</a>
        <a href="tagihan.php" class="active">Tagihan</a>
        <a href="riwayat.php">Riwayat</a>
        <a href="notifikasi.php">Notifikasi</a>
        <a href="akun.php">Akun</a>
    </div>
</div>

<div class="container">
    <div class="title-box">
        <h2>Tagihan Saya</h2>
        <p>Lihat dan bayar tagihan kos kamu</p>
    </div>

    <?php if($tagihan){ ?>
    <div class="content">

        <div class="card">
            <h3>Tagihan Aktif</h3>

            <div class="info"><b>Bulan:</b> <?= $tagihan['bulan'] ?></div>
            <div class="info"><b>Total:</b> Rp <?= number_format($tagihan['total_tagihan']) ?></div>

            <div class="info">
                <b>Status:</b>
                <span class="status <?= $tagihan['status'] ?>">
                    <?= $tagihan['status'] ?>
                </span>
            </div>
        </div>

        <div class="card">
            <h3>Upload Bukti Pembayaran</h3>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_pembayaran" value="<?= $tagihan['id'] ?>">
                <input type="file" name="bukti" required>
                <button name="upload_bukti">Kirim Pembayaran</button>
            </form>
        </div>

    </div>

    <div class="payment-box">
        <h3>Metode Pembayaran</h3>

        <br>
        <b>Transfer Bank</b>
        <p>BCA : 1234567890</p>
        <p>Mandiri : 9876543210</p>
        <p>A/N : KOS APP</p>

        <br>
        <b>E-Wallet</b>
        <p>Dana : 08123456789</p>
        <p>OVO : 08123456789</p>
        <p>GoPay : 08123456789</p>
    </div>

    <?php } else { ?>
        <div class="empty">
            <h3>Belum ada tagihan</h3>
        </div>
    <?php } ?>
</div>

</body>
</html>
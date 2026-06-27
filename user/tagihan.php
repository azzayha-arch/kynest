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

$denda = 0;
$total_final = 0;

if($tagihan){
    $today = strtotime(date("Y-m-d"));
    $jatuh_tempo = strtotime($tagihan['jatuh_tempo']);

    if(
        $today > $jatuh_tempo &&
        $tagihan['status'] != 'lunas'
    ){
        $selisih_bulan = floor(
            ($today - $jatuh_tempo) / (30 * 24 * 60 * 60)
        );

        $denda = $selisih_bulan * 50000;
    }

    $total_final = $tagihan['total_tagihan'] + $denda;
}

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

        .payment-summary{
            margin-top:20px;
            padding:15px;
            background:#f8fafc;
            border-radius:12px;
        }

        .row-line{
            display:flex;
            justify-content:space-between;
            margin:12px 0;
            font-size:16px;
        }

        .payment-summary hr{
            border:none;
            border-top:1px dashed #cbd5e1;
            margin:14px 0;
        }

        .total-line{
            font-weight:bold;
            font-size:20px;
            color:#2563eb;
        }
        
    </style>
</head>
<body>

<?php include "../includes/user_navbar.php"; ?>

<div class="container">
    <div class="title-box">
        <h2>Tagihan Saya</h2>
        <p>Lihat dan bayar tagihan kos kamu</p>
    </div>

    <?php if($tagihan){ ?>
    <div class="content">

        <div class="card">
            <h3>Tagihan Aktif</h3>

            <div class="info">
                <b>Bulan:</b> <?= $tagihan['bulan'] ?>
            </div>

            <div class="info">
                <b>Status:</b>
                <span class="status <?= $tagihan['status'] ?>">
                    <?= $tagihan['status'] ?>
                </span>
            </div>

            <div class="payment-summary">
                <div class="row-line">
                    <span>Tagihan</span>
                    <span>Rp <?= number_format($tagihan['total_tagihan']) ?></span>
                </div>

                <div class="row-line">
                    <span>Denda</span>
                    <span>Rp <?= number_format($denda) ?></span>
                </div>

                <hr>

                <div class="row-line total-line">
                    <span>Total</span>
                    <span>Rp <?= number_format($total_final) ?></span>
                </div>
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
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
                status='menunggu_verifikasi'
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
        font-family:'Segoe UI',sans-serif;
    }

    body{background:#f4f7fb;}
    .container{padding:35px;}

    .title-box{
        background:#fff;
        border-radius:20px;
        padding:25px;
        box-shadow:0 8px 25px rgba(0,0,0,.05);
    }

    .title-box h2{color:#1e3a8a;}
    .title-box p{
        color:#64748b;
        margin-top:8px;
    }

    .content{
        display:grid;
        grid-template-columns:380px 1fr;
        gap:25px;
        margin-top:25px;
    }

    .card{
        background:#fff;
        border-radius:20px;
        padding:25px;
        box-shadow:0 8px 25px rgba(0,0,0,.05);
    }

    .card h3{
        color:#1e3a8a;
        margin-bottom:20px;
    }

    .info{margin-bottom:15px;}

    .status{
        display:inline-block;
        padding:8px 16px;
        border-radius:30px;
        font-size:13px;
        font-weight:bold;
    }

    .belum_bayar{
        background:#e5e7eb;
        color:#374151;
    }

    .menunggu_verifikasi{
        background:#fff7ed;
        color:#b45309;
    }

    .lunas{
        background:#dcfce7;
        color:#15803d;
    }

    .ditolak{
        background:#fee2e2;
        color:#dc2626;
    }

    .payment-summary{
        margin-top:25px;
        background:#f8fafc;
        border-radius:15px;
        padding:18px;
    }

    .row-line{
        display:flex;
        justify-content:space-between;
        margin:12px 0;
    }

    .total-line{
        font-size:22px;
        font-weight:bold;
        color:#2563eb;
    }

    .payment-summary hr{
        border:none;
        border-top:1px dashed #cbd5e1;
        margin:15px 0;
    }

    .upload-box{
        margin-top:30px;
        border-top:1px solid #e5e7eb;
        padding-top:25px;
    }

    .upload-box h4{
        margin-bottom:15px;
    }

    input[type=file]{
        width:100%;
        padding:12px;
        border:2px dashed #cbd5e1;
        border-radius:12px;
        margin:15px 0;
    }

    button{
        border:none;
        cursor:pointer;
    }

    .upload-btn{
        width:100%;
        background:#2563eb;
        color:#fff;
        padding:15px;
        border-radius:12px;
        font-weight:bold;
        transition:.3s;
    }

    .upload-btn:hover{
        background:#1d4ed8;
    }

    .accordion-item{
        margin-bottom:15px;
    }

    .accordion-btn{

        width:100%;
        padding:16px 18px;
        background:#f8fafc;
        border:1px solid #e5e7eb;
        border-radius:12px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        cursor:pointer;
        font-size:16px;
        font-weight:600;
        color:#1e293b;
    }

    .accordion-btn:hover{
        background:#eef2ff;
    }

    .accordion-content{
        display:none;
        margin-top:15px;
    }

    .payment-card{
        display:flex;
        justify-content:space-between;
        align-items:center;
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:14px;
        padding:15px;
        margin-bottom:12px;
    }

    .payment-card h5{
        color:#1e3a8a;
    }

    .payment-card small{
        color:#64748b;
    }

    .payment-card p{
        margin-top:5px;
        font-weight:bold;
        font-size:18px;
    }

    .copy-btn{
        background:#2563eb;
        color:#fff;
        padding:10px 18px;
        border-radius:8px;
    }

    .copy-btn:hover{
        background:#1d4ed8;
    }

    .success-box{
        background:#dcfce7;
        color:#15803d;
        padding:18px;
        border-radius:12px;
        font-weight:bold;
    }

    .warning-box{
        background:#fff7ed;
        color:#b45309;
        padding:18px;
        border-radius:12px;
        font-weight:bold;
    }

    .empty{
        text-align:center;
        padding:60px;
        color:#64748b;
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

            <?php
                $statusText = [
                    'belum_bayar' => 'Belum Bayar',
                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                    'lunas' => 'Lunas',
                    'ditolak' => 'Ditolak'
                ];
                ?>

                <div class="info">
                    <b>Status:</b>
                    <span class="status <?= $tagihan['status'] ?>">
                        <?= $statusText[$tagihan['status']] ?>
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

    <h3><i class="bi bi-credit-card"></i> Pembayaran Tagihan</h3>

    <p style="color:#64748b;margin-bottom:20px;">
        Pilih metode pembayaran lalu lakukan transfer sesuai nominal tagihan.
    </p>

    <!-- ================= TRANSFER BANK ================= -->

    <div class="accordion-item">

        <button type="button"
                class="accordion-btn"
                onclick="toggleAccordion('bank')">

            <span>
                <i class="bi bi-bank"></i>
                Transfer Bank
            </span>

            <i class="bi bi-chevron-down"></i>

        </button>

        <div id="bank" class="accordion-content">

            <div class="payment-card">

                <div>

                    <h5>BCA</h5>

                    <small>A/N KOS APP</small>

                    <p id="bca">1234567890</p>

                </div>

                <button
                    class="copy-btn"
                    onclick="copyText('1234567890')">

                    <i class="bi bi-copy"></i>
                    Salin

                </button>

            </div>

            <div class="payment-card">

                <div>

                    <h5>Mandiri</h5>

                    <small>A/N KOS APP</small>

                    <p>9876543210</p>

                </div>

                <button
                    class="copy-btn"
                    onclick="copyText('9876543210')">

                    <i class="bi bi-copy"></i>
                    Salin

                </button>

            </div>

        </div>

    </div>

    <!-- ================= EWALLET ================= -->

    <div class="accordion-item">

        <button type="button"
                class="accordion-btn"
                onclick="toggleAccordion('wallet')">

            <span>
                <i class="bi bi-phone"></i>
                E-Wallet
            </span>

            <i class="bi bi-chevron-down"></i>

        </button>

        <div id="wallet" class="accordion-content">

            <div class="payment-card">

                <div>

                    <h5>DANA</h5>

                    <small>Atas Nama KOS APP</small>

                    <p>08123456789</p>

                </div>

                <button
                    class="copy-btn"
                    onclick="copyText('08123456789')">

                    <i class="bi bi-copy"></i>
                    Salin

                </button>

            </div>

            <div class="payment-card">

                <div>

                    <h5>OVO</h5>

                    <small>Atas Nama KOS APP</small>

                    <p>08123456789</p>

                </div>

                <button
                    class="copy-btn"
                    onclick="copyText('08123456789')">

                    <i class="bi bi-copy"></i>
                    Salin

                </button>

            </div>

            <div class="payment-card">

                <div>

                    <h5>GoPay</h5>

                    <small>Atas Nama KOS APP</small>

                    <p>08123456789</p>

                </div>

                <button
                    class="copy-btn"
                    onclick="copyText('08123456789')">

                    <i class="bi bi-copy"></i>
                    Salin

                </button>

            </div>

        </div>

    </div>

    <!-- ================= UPLOAD ================= -->

    <div class="upload-box">

        <h4>
            <i class="bi bi-cloud-upload"></i>
            Upload Bukti Pembayaran
        </h4>

        <?php if($tagihan['status']=='belum_bayar' || $tagihan['status']=='ditolak'){ ?>

            <form method="POST" enctype="multipart/form-data">

                <input
                    type="hidden"
                    name="id_pembayaran"
                    value="<?= $tagihan['id'] ?>">

                <input
                    type="file"
                    name="bukti"
                    required>

                <button
                    type="submit"
                    name="upload_bukti"
                    class="upload-btn">

                    <i class="bi bi-send"></i>
                    Kirim Bukti Pembayaran

                </button>

            </form>

        <?php } elseif($tagihan['status']=='menunggu_verifikasi'){ ?>

            <div class="warning-box">

                <i class="bi bi-hourglass-split"></i>

                Bukti pembayaran sudah dikirim.

                <br><br>

                Menunggu verifikasi admin.

            </div>

        <?php } else { ?>

            <div class="success-box">

                <i class="bi bi-check-circle-fill"></i>

                Pembayaran berhasil diverifikasi.

            </div>

        <?php } ?>

    </div>

</div>

<?php } else { ?>

<div class="empty">
    <h3>Belum ada tagihan</h3>
</div>

<?php } ?>

</div>

<script>

function toggleAccordion(id){

    let content = document.getElementById(id);

    if(content.style.display==="block"){

        content.style.display="none";

    }else{

        document.querySelectorAll(".accordion-content").forEach(function(item){

            item.style.display="none";

        });

        content.style.display="block";

    }

}

function copyText(text){

    navigator.clipboard.writeText(text);

    showToast("Nomor berhasil disalin");

}

function showToast(message){

    let toast=document.createElement("div");

    toast.innerHTML='<i class="bi bi-check-circle-fill"></i> '+message;

    toast.style.position="fixed";
    toast.style.bottom="30px";
    toast.style.right="30px";
    toast.style.background="#2563eb";
    toast.style.color="#fff";
    toast.style.padding="14px 22px";
    toast.style.borderRadius="12px";
    toast.style.boxShadow="0 10px 25px rgba(0,0,0,.2)";
    toast.style.fontWeight="600";
    toast.style.zIndex="9999";
    toast.style.opacity="0";
    toast.style.transition=".3s";

    document.body.appendChild(toast);

    setTimeout(function(){

        toast.style.opacity="1";

    },100);

    setTimeout(function(){

        toast.style.opacity="0";

        setTimeout(function(){

            toast.remove();

        },300);

    },2000);

}

</script>

</body>
</html>
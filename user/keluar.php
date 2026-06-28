<?php
include "../auth/user_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$user_id = $_SESSION['user_id'];

/* CEK TAGIHAN BELUM LUNAS */
$cek = mysqli_query($conn,"
SELECT * FROM pembayaran
WHERE penghuni_id='$user_id'
AND status != 'lunas'
");

$masih_ada_tagihan = mysqli_num_rows($cek) > 0;

/* SUBMIT FORM */
if(isset($_POST['submit']) && !$masih_ada_tagihan){
    $alasan = $_POST['alasan'];
    $tanggal_keluar = $_POST['tanggal_keluar'];

    mysqli_query($conn,"
    INSERT INTO permintaan_keluar
    (penghuni_id, alasan, tanggal_keluar)
    VALUES
    ('$user_id','$alasan','$tanggal_keluar')
    ");

    echo "<script>alert('Permintaan keluar berhasil dikirim!');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Keluar dari Kos</title>

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

.card{
    background:white;
    max-width:650px;
    margin:auto;
    padding:30px;
    border-radius:18px;
    box-shadow:0 3px 12px rgba(0,0,0,0.05);
}

h2{
    margin-bottom:20px;
}

label{
    display:block;
    margin-top:18px;
    margin-bottom:8px;
    font-weight:bold;
}

input, textarea{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:10px;
}

textarea{
    height:120px;
}

button{
    width:100%;
    margin-top:25px;
    padding:15px;
    border:none;
    border-radius:12px;
    background:#ef4444;
    color:white;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#dc2626;
}

.warning{
    background:#fee2e2;
    color:#991b1b;
    padding:20px;
    border-radius:14px;
    margin-top:20px;
}
</style>
</head>
<body>

<?php include "../includes/user_navbar.php"; ?>

<div class="container">
    <div class="card">
        <h2>Ajukan Keluar dari Kos</h2>

        <?php if($masih_ada_tagihan){ ?>
            <div class="warning">
                Anda masih memiliki tagihan yang belum lunas.<br><br>
                Silakan lunasi tagihan terlebih dahulu sebelum keluar dari kos.
            </div>
        <?php } else { ?>

            <form method="POST">

                <label>Alasan Keluar</label>
                <textarea name="alasan" required></textarea>

                <label>Tanggal Keluar</label>
                <input type="date" name="tanggal_keluar" required>

                <button name="submit">Ajukan Keluar</button>

            </form>

        <?php } ?>

    </div>
</div>

</body>
</html>
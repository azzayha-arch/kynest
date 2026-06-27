<?php
include "../auth/user_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$user_id = $_SESSION['user_id'];

if(isset($_POST['submit'])){
    $durasi = $_POST['durasi'];
    $satuan = $_POST['satuan'];
    $catatan = $_POST['catatan'];

    mysqli_query($conn,"
    INSERT INTO perpanjangan
    (penghuni_id,durasi,satuan,catatan)
    VALUES
    ('$user_id','$durasi','$satuan','$catatan')
    ");

    echo "<script>alert('Permintaan perpanjangan berhasil dikirim!')</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Perpanjang Kos</title>
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
    max-width:600px;
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

input, select, textarea{
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
    background:#22c55e;
    color:white;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    background:#16a34a;
}
</style>
</head>
<body>

<?php include "../includes/user_navbar.php"; ?>

<div class="container">
    <div class="card">
        <h2>Ajukan Perpanjangan Kos</h2>

        <form method="POST">

            <label>Durasi</label>
            <input type="number" name="durasi" required>

            <label>Satuan</label>
            <select name="satuan">
                <option value="hari">Hari</option>
                <option value="minggu">Minggu</option>
                <option value="bulan">Bulan</option>
            </select>

            <label>Catatan</label>
            <textarea name="catatan"></textarea>

            <button name="submit">Ajukan</button>

        </form>
    </div>
</div>

</body>
</html>
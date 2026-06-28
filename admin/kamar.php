<?php
include "../auth/auth_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

/* TAMBAH KAMAR */
if(isset($_POST['tambah'])){
    $nomor_kamar = $_POST['nomor_kamar'];
    $tipe = $_POST['tipe'];
    $harga = $_POST['harga'];

    mysqli_query($conn,"INSERT INTO kamar (nomor_kamar, tipe, harga, status)
    VALUES ('$nomor_kamar','$tipe','$harga','kosong')");

    header("Location: kamar.php");
    exit;
}

/* HAPUS */
if(isset($_GET['hapus'])){
    $id = (int)$_GET['hapus'];
    mysqli_query($conn,"DELETE FROM kamar WHERE id=$id");
    header("Location: kamar.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Kamar</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .btn-add{
            background:#2563eb;
            color:white;
            padding:12px 18px;
            border:none;
            border-radius:10px;
            cursor:pointer;
            font-weight:bold;
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
            text-align:center;
            border-bottom:1px solid #ddd;
        }

        .status{
            color:white;
            padding:6px 14px;
            border-radius:20px;
            font-size:13px;
            font-weight:bold;
        }

        .kosong{background:#ef4444;}
        .terisi{background:#10b981;}

        .delete{
            background:#ef4444;
            color:white;
            padding:8px 14px;
            border-radius:8px;
            text-decoration:none;
        }

        .modal{
            display:none;
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.5);
            justify-content:center;
            align-items:center;
        }

        .modal-box{
            background:white;
            width:400px;
            padding:30px;
            border-radius:20px;
        }

        input, select{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:8px;
        }

        .save-btn{
            width:100%;
            background:#2563eb;
            color:white;
            border:none;
            padding:14px;
            border-radius:10px;
            font-weight:bold;
        }
    </style>
</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<div style="margin-left:270px;padding:30px;">

    <div class="top-bar">
        <h1>Data Kamar</h1>
        <button class="btn-add" onclick="openModal()">+ Tambah Kamar</button>
    </div>

    <div class="page-box">
        <table>
            <tr>
                <th>No Kamar</th>
                <th>Tipe</th>
                <th>Harga</th>
                <th>Penghuni</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php
            $data = mysqli_query($conn,"SELECT * FROM kamar ORDER BY nomor_kamar ASC");

            while($row = mysqli_fetch_assoc($data)){

                $nomor = $row['nomor_kamar'];
                $penghuni = mysqli_query($conn,
                    "SELECT nama FROM penghuni 
                    WHERE nomor_kamar='$nomor' AND status='aktif'
                    LIMIT 1");

                $penghuniData = mysqli_fetch_assoc($penghuni);
                $namaPenghuni = $penghuniData ? $penghuniData['nama'] : '-';

                $status = ($namaPenghuni == '-') ? 'kosong' : 'terisi';
            ?>
            <tr>
                <td><?= $row['nomor_kamar'] ?></td>
                <td><?= $row['tipe'] ?></td>
                <td>Rp <?= number_format($row['harga']) ?></td>
                <td><?= $namaPenghuni ?></td>
                <td>
                    <span class="status <?= $status ?>">
                        <?= ucfirst($status) ?>
                    </span>
                </td>
                <td>
                    <a class="delete" href="?hapus=<?= $row['id'] ?>">Hapus</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<div class="modal" id="modal">
    <div class="modal-box">
        <h2>Tambah Kamar</h2>

        <form method="POST">
            <input name="nomor_kamar" placeholder="Nomor Kamar" required>

            <select name="tipe">
                <option>kecil</option>
                <option>besar</option>
            </select>

            <input name="harga" placeholder="Harga" required>

            <button class="save-btn" name="tambah">Simpan</button>
        </form>
    </div>
</div>

<script>
function openModal(){
    document.getElementById("modal").style.display="flex";
}

window.onclick=function(e){
    let modal=document.getElementById("modal");
    if(e.target==modal){
        modal.style.display="none";
    }
}
</script>

</body>
</html>
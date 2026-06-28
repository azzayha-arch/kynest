<?php
include "../auth/auth_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$tab = $_GET['tab'] ?? 'bayar';

/* ================= PEMBAYARAN ================= */
if(isset($_GET['approve'])){
    $id = (int) $_GET['approve'];

    // Ambil data pembayaran yang disetujui
    $bayar = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT *
        FROM pembayaran
        WHERE id=$id
    "));

    // Ubah status menjadi lunas
    mysqli_query($conn,"
        UPDATE pembayaran
        SET status='lunas'
        WHERE id=$id
    ");

    // Buat tagihan bulan berikutnya
    $bulan = [
        "Januari","Februari","Maret","April","Mei","Juni",
        "Juli","Agustus","September","Oktober","November","Desember"
    ];

    $bulanSekarang = array_search($bayar['bulan'],$bulan);

    if($bulanSekarang===11){
        $bulanBaru = $bulan[0];
    }else{
        $bulanBaru = $bulan[$bulanSekarang+1];
    }

    mysqli_query($conn,"
        INSERT INTO pembayaran(
            penghuni_id,
            bulan,
            total_tagihan,
            jumlah_bayar,
            bukti_bayar,
            status,
            tanggal,
            jatuh_tempo,
            denda
        ) VALUES(
            '".$bayar['penghuni_id']."',
            '$bulanBaru',
            '".$bayar['total_tagihan']."',
            0,
            '',
            'belum_bayar',
            CURDATE(),
            DATE_ADD(CURDATE(),INTERVAL 1 MONTH),
            0
        )
    ");

    header("Location: pembayaran.php?tab=bayar");
    exit;
}

if(isset($_GET['reject'])){
    $id = (int) $_GET['reject'];
    mysqli_query($conn,"UPDATE pembayaran SET status='ditolak' WHERE id=$id");
    header("Location: pembayaran.php?tab=bayar");
    exit;
}

/* ================= PERPANJANGAN ================= */
if(isset($_GET['approve_perpanjang'])){
    $id = (int) $_GET['approve_perpanjang'];
    mysqli_query($conn,"UPDATE perpanjangan SET status='approved' WHERE id=$id");
    header("Location: pembayaran.php?tab=perpanjang");
    exit;
}

if(isset($_GET['reject_perpanjang'])){
    $id = (int) $_GET['reject_perpanjang'];
    mysqli_query($conn,"UPDATE perpanjangan SET status='rejected' WHERE id=$id");
    header("Location: pembayaran.php?tab=perpanjang");
    exit;
}

/* ================= KELUAR ================= */
if(isset($_GET['approve_keluar'])){
    $id = (int) $_GET['approve_keluar'];

    $req = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT permintaan_keluar.*, penghuni.nomor_kamar, penghuni.id as pid
        FROM permintaan_keluar
        JOIN penghuni ON permintaan_keluar.penghuni_id = penghuni.id
        WHERE permintaan_keluar.id=$id
    "));

    if($req){
        mysqli_query($conn,"UPDATE permintaan_keluar SET status='approved' WHERE id=$id");
        mysqli_query($conn,"UPDATE penghuni SET status='keluar' WHERE id=".$req['pid']);
        mysqli_query($conn,"UPDATE kamar SET status='kosong' WHERE nomor_kamar='".$req['nomor_kamar']."'");
    }

    header("Location: pembayaran.php?tab=keluar");
    exit;
}

if(isset($_GET['reject_keluar'])){
    $id = (int) $_GET['reject_keluar'];
    mysqli_query($conn,"UPDATE permintaan_keluar SET status='rejected' WHERE id=$id");
    header("Location: pembayaran.php?tab=keluar");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .tabs{
            display:flex;
            gap:12px;
            margin:20px 0;
        }

        .tabs a{
            text-decoration:none;
            padding:12px 18px;
            border-radius:12px;
            background:#e5e7eb;
            color:#333;
            font-weight:600;
        }

        .tabs a.active{
            background:#2563eb;
            color:white;
        }

        .thumb{
            width:55px;
            height:55px;
            object-fit:cover;
            border-radius:8px;
            cursor:pointer;
            border:1px solid #ddd;
        }

        .badge{
            padding:6px 12px;
            border-radius:20px;
            color:white;
            font-size:13px;
            font-weight:bold;
        }

        .belum_bayar{background:#6b7280;}
        .menunggu_verifikasi{background:#f59e0b;}
        .lunas,.approved{background:#10b981;}
        .ditolak,.rejected{background:#ef4444;}

        .btn{
            padding:8px 12px;
            border-radius:6px;
            text-decoration:none;
            color:white;
            font-size:13px;
        }

        .approve{background:#10b981;}
        .reject{background:#ef4444;}

        .modal{
            display:none;
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,.7);
            justify-content:center;
            align-items:center;
        }

        .modal img{
            max-width:80%;
            max-height:80%;
            border-radius:15px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th, td{
            padding:12px;
            text-align:center;
            border-bottom:1px solid #eee;
        }
    </style>
</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<div class="main">
<?php include "../includes/navbar.php"; ?>

<div class="content">
    <h1>Data Pembayaran</h1>

    <div class="tabs">
        <a href="?tab=bayar" class="<?= $tab=='bayar' ? 'active' : '' ?>">Pembayaran User</a>
        <a href="?tab=perpanjang" class="<?= $tab=='perpanjang' ? 'active' : '' ?>">Request Perpanjang</a>
        <a href="?tab=keluar" class="<?= $tab=='keluar' ? 'active' : '' ?>">Request Keluar</a>
    </div>

    <div class="page-box">
<?php if($tab=='bayar'){ ?>
        <table>
            <tr>
                <th>Bukti</th>
                <th>ID</th>
                <th>Nama</th>
                <th>Kamar</th>
                <th>Bulan</th>
                <th>Tagihan</th>
                <th>Denda</th>
                <th>Dibayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

<?php
$data = mysqli_query($conn,"
SELECT pembayaran.*, penghuni.nama, penghuni.nomor_kamar
FROM pembayaran
JOIN penghuni ON pembayaran.penghuni_id=penghuni.id
ORDER BY pembayaran.id DESC
");
$statusText = [
    'belum_bayar' => 'Belum Bayar',
    'menunggu_verifikasi' => 'Menunggu Verifikasi',
    'lunas' => 'Lunas',
    'ditolak' => 'Ditolak'
];
while($row=mysqli_fetch_assoc($data)){
?>
            <tr>
                <td>
                    <?php if(!empty($row['bukti_bayar'])){ ?>
                    <img src="../uploads/<?= $row['bukti_bayar'] ?>" class="thumb" onclick="showImage(this.src)">
                    <?php } else { echo "-"; } ?>
                </td>

                <td><?= $row['id'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['nomor_kamar'] ?></td>
                <td><?= $row['bulan'] ?></td>
                <td>Rp <?= number_format($row['total_tagihan']) ?></td>

                <td>Rp <?= number_format($row['denda']) ?></td>
                <td>Rp <?= number_format($row['jumlah_bayar']) ?></td>

                <td>
                    <span class="badge <?= $row['status'] ?>">
                    <?= $statusText[$row['status']] ?>
                </span>
                </td>

                <td>
                    <?php if($row['status']=='menunggu_verifikasi'){ ?>
                        <a class="btn approve" href="?tab=bayar&approve=<?= $row['id'] ?>">Approve</a>
                        <a class="btn reject" href="?tab=bayar&reject=<?= $row['id'] ?>">Reject</a>
                    <?php } else { echo "-"; } ?>
                </td>
            </tr>
<?php } ?>
        </table>

<?php } elseif($tab=='perpanjang'){ ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kamar</th>
                <th>Durasi</th>
                <th>Catatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

<?php
$data = mysqli_query($conn,"
SELECT perpanjangan.*, penghuni.nama, penghuni.nomor_kamar
FROM perpanjangan
JOIN penghuni ON perpanjangan.penghuni_id=penghuni.id
ORDER BY perpanjangan.id DESC
");

while($row=mysqli_fetch_assoc($data)){
?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['nomor_kamar'] ?></td>
                <td><?= $row['durasi']." ".$row['satuan'] ?></td>
                <td><?= $row['catatan'] ?></td>

                <td>
                    <span class="badge <?= $row['status'] ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>

                <td>
                    <?php if($row['status']=='menunggu_verifikasi'){ ?>
                        <a class="btn approve" href="?tab=perpanjang&approve_perpanjang=<?= $row['id'] ?>">Approve</a>
                        <a class="btn reject" href="?tab=perpanjang&reject_perpanjang=<?= $row['id'] ?>">Reject</a>
                    <?php } else { echo "-"; } ?>
                </td>
            </tr>
<?php } ?>
        </table>

<?php } else { ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Kamar</th>
                <th>Alasan</th>
                <th>Tanggal Keluar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

<?php
$data = mysqli_query($conn,"
SELECT permintaan_keluar.*, penghuni.nama, penghuni.nomor_kamar
FROM permintaan_keluar
JOIN penghuni ON permintaan_keluar.penghuni_id=penghuni.id
ORDER BY permintaan_keluar.id DESC
");

while($row=mysqli_fetch_assoc($data)){
?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['nomor_kamar'] ?></td>
                <td><?= $row['alasan'] ?></td>
                <td><?= $row['tanggal_keluar'] ?></td>

                <td>
                    <span class="badge <?= $row['status'] ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>

                <td>
                    <?php if($row['status']=='menunggu_verifikasi'){ ?>
                        <a class="btn approve" href="?tab=keluar&approve_keluar=<?= $row['id'] ?>">Approve</a>
                        <a class="btn reject" href="?tab=keluar&reject_keluar=<?= $row['id'] ?>">Reject</a>
                    <?php } else { echo "-"; } ?>
                </td>
            </tr>
<?php } ?>
        </table>

<?php } ?>
    </div>
</div>
</div>

<div class="modal" id="imageModal" onclick="closeModal()">
    <img id="modalImg">
</div>

<script>
function showImage(src){
    document.getElementById("imageModal").style.display="flex";
    document.getElementById("modalImg").src=src;
}

function closeModal(){
    document.getElementById("imageModal").style.display="none";
}
</script>

</body>
</html>

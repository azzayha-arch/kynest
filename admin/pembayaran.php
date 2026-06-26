<?php
include "../auth/auth_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

/* APPROVE */
if(isset($_GET['approve'])){
    $id = (int) $_GET['approve'];
    mysqli_query($conn,"UPDATE pembayaran SET status='lunas' WHERE id=$id");
    header("Location: pembayaran.php");
    exit;
}

/* REJECT */
if(isset($_GET['reject'])){
    $id = (int) $_GET['reject'];
    mysqli_query($conn,"UPDATE pembayaran SET status='ditolak' WHERE id=$id");
    header("Location: pembayaran.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
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

        .pending{background:#f59e0b;}
        .lunas{background:#10b981;}
        .ditolak{background:#ef4444;}

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
    </style>
</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<div class="main">
<?php include "../includes/navbar.php"; ?>

<div class="content">
    <h1>Data Pembayaran</h1>

    <div class="page-box">
        <table>
            <tr>
                <th>Bukti</th>
                <th>ID</th>
                <th>Penghuni ID</th>
                <th>Bulan</th>
                <th>Tagihan</th>
                <th>Dibayar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php
            $data = mysqli_query($conn,"SELECT * FROM pembayaran ORDER BY id DESC");

            while($row=mysqli_fetch_assoc($data)){
            ?>
            <tr>
                <td>
                    <?php if(!empty($row['bukti_bayar'])){ ?>
                        <img 
                            src="../uploads/<?= $row['bukti_bayar'] ?>" 
                            class="thumb"
                            onclick="showImage(this.src)"
                        >
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>

                <td><?= $row['id'] ?></td>
                <td><?= $row['penghuni_id'] ?></td>
                <td><?= $row['bulan'] ?></td>
                <td>Rp <?= number_format($row['total_tagihan']) ?></td>
                <td>Rp <?= number_format($row['jumlah_bayar']) ?></td>

                <td>
                    <span class="badge <?= $row['status'] ?>">
                        <?= $row['status'] ?>
                    </span>
                </td>

                <td>
                    <a class="btn approve" href="?approve=<?= $row['id'] ?>">Approve</a>
                    <a class="btn reject" href="?reject=<?= $row['id'] ?>">Reject</a>
                </td>
            </tr>
            <?php } ?>
        </table>
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
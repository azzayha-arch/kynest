<?php
include "../auth/auth_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

/* APPROVE */
if(isset($_GET['approve'])){
    $id = (int)$_GET['approve'];
    mysqli_query($conn,"UPDATE penghuni SET status='aktif' WHERE id=$id");
    header("Location: penghuni.php");
    exit;
}

/* NONAKTIF */
if(isset($_GET['disable'])){
    $id = (int)$_GET['disable'];
    mysqli_query($conn,"UPDATE penghuni SET status='nonaktif' WHERE id=$id");
    header("Location: penghuni.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Penghuni</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .page-box{
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
        }

        .status{
            padding:6px 14px;
            border-radius:20px;
            font-size:13px;
            font-weight:bold;
            color:white;
        }

        .pending{background:#f59e0b;}
        .aktif{background:#10b981;}
        .nonaktif{background:#ef4444;}

        .btn{
            padding:8px 14px;
            border-radius:8px;
            color:white;
            text-decoration:none;
            font-size:13px;
            font-weight:bold;
            margin-right:5px;
        }

        .approve{background:#10b981;}
        .disable{background:#ef4444;}

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
            border-bottom:1px solid #ddd;
            text-align:center;
        }
    </style>
</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<div style="margin-left:270px;padding:30px;">
    <h1>Data Penghuni</h1>

    <div class="page-box">
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>No HP</th>
                <th>Email</th>
                <th>Kamar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php
            $data = mysqli_query($conn,"SELECT * FROM penghuni ORDER BY id DESC");

            while($row = mysqli_fetch_assoc($data)){
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['no_hp'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['nomor_kamar'] ?></td>

                <td>
                    <span class="status <?= $row['status'] ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>

                <td>
                    <?php if($row['status'] != 'aktif'){ ?>
                        <a class="btn approve" href="?approve=<?= $row['id'] ?>">
                            Approve
                        </a>
                    <?php } ?>

                    <?php if($row['status'] == 'aktif'){ ?>
                        <a class="btn disable" href="?disable=<?= $row['id'] ?>">
                            Nonaktif
                        </a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
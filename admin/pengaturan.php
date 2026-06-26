<?php
include "../auth/auth_check.php";
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

/* SIMPAN */
if(isset($_POST['simpan'])){
    $nama_web = $_POST['nama_web'];
    $nama_admin = $_POST['nama_admin'];
    $email_admin = $_POST['email_admin'];
    $alamat = $_POST['alamat'];
    $whatsapp = $_POST['whatsapp'];
    $harga_default = $_POST['harga_default'];

    // Upload Foto Admin
    if(!empty($_FILES['foto_admin']['name'])){
        $foto_admin = time() . "_" . $_FILES['foto_admin']['name'];
        move_uploaded_file(
            $_FILES['foto_admin']['tmp_name'],
            "../assets/img/admin/" . $foto_admin
        );

        mysqli_query($conn,"
            UPDATE pengaturan SET
            foto_admin='$foto_admin'
            WHERE id=1
        ");
    }

    // Upload Logo Web
    if(!empty($_FILES['logo']['name'])){
        $logo = time() . "_" . $_FILES['logo']['name'];
        move_uploaded_file(
            $_FILES['logo']['tmp_name'],
            "../assets/img/" . $logo
        );

        mysqli_query($conn,"
            UPDATE pengaturan SET
            logo='$logo'
            WHERE id=1
        ");
    }

    mysqli_query($conn,"
        UPDATE pengaturan SET
        nama_web='$nama_web',
        nama_admin='$nama_admin',
        email_admin='$email_admin',
        alamat='$alamat',
        whatsapp='$whatsapp',
        harga_default='$harga_default'
        WHERE id=1
    ");

    header("Location: pengaturan.php");
    exit;
}

$data = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM pengaturan WHERE id=1")
);

if(!$data){
    $data = [
        'nama_web' => '',
        'nama_admin' => '',
        'email_admin' => '',
        'alamat' => '',
        'whatsapp' => '',
        'harga_default' => '',
        'foto_admin' => '',
        'logo' => ''
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengaturan</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        .wrapper{
            display:flex;
            gap:25px;
        }

        .left-box{
            width:35%;
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 4px 15px rgba(0,0,0,.08);
        }

        .right-box{
            width:65%;
            background:white;
            padding:25px;
            border-radius:18px;
            box-shadow:0 4px 15px rgba(0,0,0,.08);
        }

        .profile{
            text-align:center;
            margin-bottom:25px;
        }

        .profile img{
            width:130px;
            height:130px;
            border-radius:50%;
            object-fit:cover;
            border:4px solid #2563eb;
        }

        input, textarea{
            width:100%;
            padding:14px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:10px;
            box-sizing:border-box;
        }

        textarea{
            resize:none;
            height:90px;
        }

        button{
            background:#2563eb;
            color:white;
            border:none;
            padding:14px 22px;
            border-radius:10px;
            cursor:pointer;
            font-weight:bold;
        }

        label{
            font-weight:bold;
            display:block;
            margin-bottom:8px;
        }

        h2{
            margin-top:0;
            margin-bottom:20px;
        }
    </style>
</head>
<body>

<?php include "../includes/sidebar.php"; ?>

<div style="margin-left:270px;padding:30px;">
    <h1>Pengaturan Sistem</h1>

    <form method="POST" enctype="multipart/form-data">
        <div class="wrapper">

            <!-- KIRI -->
            <div class="left-box">
                <h2>Profil Admin</h2>

                <div class="profile">
                    <?php if(!empty($data['foto_admin'])){ ?>
                        <img src="../assets/img/admin/<?= $data['foto_admin'] ?>">
                    <?php } else { ?>
                        <img src="../assets/img/default.png">
                    <?php } ?>
                </div>

                <label>Nama Admin</label>
                <input type="text" name="nama_admin" value="<?= $data['nama_admin'] ?>">

                <label>Email Admin</label>
                <input type="email" name="email_admin" value="<?= $data['email_admin'] ?>">

                <label>Upload Foto</label>
                <input type="file" name="foto_admin">
            </div>

            <!-- KANAN -->
            <div class="right-box">
                <h2>Pengaturan Sistem</h2>

                <label>Nama Web</label>
                <input type="text" name="nama_web" value="<?= $data['nama_web'] ?>">

                <label>Logo Web</label>
                <input type="file" name="logo">

                <label>Alamat Kos</label>
                <textarea name="alamat"><?= $data['alamat'] ?></textarea>

                <label>WhatsApp Admin</label>
                <input type="text" name="whatsapp" value="<?= $data['whatsapp'] ?>">

                <label>Harga Default</label>
                <input type="number" name="harga_default" value="<?= $data['harga_default'] ?>">

                <button name="simpan">Simpan Pengaturan</button>
            </div>

        </div>
    </form>
</div>

</body>
</html>
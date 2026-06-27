<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "db_pembayaran_kos");

$msg = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM penghuni WHERE email='$email'");

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);

        if ($password == $user['password']) {

            if ($user['status'] == "pending") {
                $msg = "Akun masih menunggu persetujuan admin!";
            } elseif ($user['status'] == "keluar") {
                $msg = "Akun sudah tidak aktif!";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nama'] = $user['nama'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_kamar'] = $user['nomor_kamar'];
                $_SESSION['user_foto'] = $user['foto'];

                header("Location: ../user/dashboard.php");
                exit;
            }

        } else {
            $msg = "Password salah!";
        }

    } else {
        $msg = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Penghuni</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body{
            margin:0;
            background:#f4f7fb;
            font-family:Arial;
        }

        .login-box{
            width:420px;
            background:white;
            padding:40px;
            border-radius:20px;
            margin:80px auto;
            box-shadow:0 4px 20px rgba(0,0,0,0.08);
        }

        h1{
            text-align:center;
            margin-bottom:30px;
        }

        input{
            width:100%;
            padding:14px;
            margin-bottom:15px;
            border:1px solid #ddd;
            border-radius:10px;
            box-sizing:border-box;
        }

        button{
            width:100%;
            padding:14px;
            background:#2563eb;
            color:white;
            border:none;
            border-radius:10px;
            font-size:16px;
            cursor:pointer;
        }

        .msg{
            color:red;
            text-align:center;
            margin-bottom:15px;
        }

        .register-link{
            text-align:center;
            margin-top:20px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h1>Login Penghuni</h1>

    <?php if($msg!=""){ ?>
        <div class="msg"><?= $msg ?></div>
    <?php } ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>

    <div class="register-link">
        Belum punya akun? <a href="register.php">Register</a>
    </div>
</div>

</body>
</html>
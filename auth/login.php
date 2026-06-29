<?php
session_start();
$conn = mysqli_connect("localhost","root","","db_pembayaran_kos");

$msg = "";

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query($conn,"SELECT * FROM admin
    WHERE username='$username' AND password='$password'");

    if(mysqli_num_rows($query)>0){

        $_SESSION['admin'] = true;

        header("Location: ../admin/dashboard.php");
        exit;

    }else{
        $msg = "Username atau Password salah!";
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>

    <style>

        body{
            margin:0;
            background:#f4f7fb;
            font-family:Arial, Helvetica, sans-serif;
        }

        .login-box{
            width:420px;
            background:#fff;
            padding:40px;
            border-radius:20px;
            margin:80px auto;
            box-shadow:0 4px 20px rgba(0,0,0,.08);
        }

        h1{
            text-align:center;
            margin-bottom:10px;
            color:#1f2937;
        }

        p{
            text-align:center;
            color:#666;
            margin-bottom:30px;
        }

        input{
            width:100%;
            padding:14px;
            margin-bottom:15px;
            border:1px solid #ddd;
            border-radius:10px;
            box-sizing:border-box;
            font-size:15px;
        }

        input:focus{
            outline:none;
            border-color:#2563eb;
        }

        button{
            width:100%;
            padding:14px;
            border:none;
            border-radius:10px;
            background:#2563eb;
            color:white;
            font-size:16px;
            cursor:pointer;
            transition:.3s;
        }

        button:hover{
            background:#1d4ed8;
        }

        .msg{
            text-align:center;
            color:red;
            margin-bottom:15px;
        }

        .back{
            text-align:center;
            margin-top:20px;
        }

        .back a{
            text-decoration:none;
            color:#2563eb;
        }

    </style>

</head>
<body>

<div class="login-box">

    <h1>Login Admin</h1>

    <p>Silakan masuk ke halaman administrator</p>

    <?php if($msg!=""){ ?>
        <div class="msg"><?= $msg ?></div>
    <?php } ?>

    <form method="POST">

        <input
            type="text"
            name="username"
            placeholder="Username"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button name="login">
            Login
        </button>

    </form>


</div>

</body>
</html>
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
        .navbar{
            width:100%;
            background:white;
            padding:18px 40px;
            display:grid;
            grid-template-columns:180px 1fr 180px;
            align-items:center;
            box-shadow:0 2px 8px rgba(0,0,0,0.05);
        }

        .logo{
            font-size:22px;
            font-weight:bold;
            color:#2563eb;
        }

        .menu{
            display:flex;
            justify-content:center;
            gap:18px;
        }

        .menu a{
            text-decoration:none;
            color:#444;
            font-weight:500;
            padding:10px 16px;
            border-radius:12px;
            position:relative;
            transition:all 0.25s ease;
        }

        .menu a:hover{
            background:#f3f4f6;
            color:#2563eb;
        }

        .menu a.active{
            
            color:#2563eb;
            font-weight:600;
        }

        .menu a.active::after{
            content:"";
            position:absolute;
            bottom:-10px;
            left:50%;
            transform:translateX(-50%);
            width:70%;
            height:3px;
            background:#2563eb;
            border-radius:10px;
        }
</style>

<div class="navbar">
    <div class="logo">KOS APP</div>

    <div class="menu">
        <a href="dashboard.php"
           class="<?= ($current_page=='dashboard.php') ? 'active' : '' ?>">
            Beranda
        </a>

        <a href="tagihan.php"
           class="<?= ($current_page=='tagihan.php') ? 'active' : '' ?>">
            Tagihan
        </a>

        <a href="riwayat.php"
           class="<?= ($current_page=='riwayat.php') ? 'active' : '' ?>">
            Riwayat
        </a>

        <a href="notifikasi.php"
           class="<?= ($current_page=='notifikasi.php') ? 'active' : '' ?>">
            Notifikasi
        </a>

        <a href="akun.php"
           class="<?= ($current_page=='akun.php') ? 'active' : '' ?>">
            Akun
        </a>
    </div>
</div>
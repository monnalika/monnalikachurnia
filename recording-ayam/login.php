<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);

    $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username = '$username' AND password = '$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['admin_id']   = $data['id'];
        $_SESSION['admin_nama'] = $data['nama'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: linear-gradient(135deg, #fff0f5, #ffe4e9); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .box { background: white; border-radius: 25px; padding: 40px 35px; width: 100%; max-width: 400px; box-shadow: 0 10px 40px rgba(255,182,193,0.35); }
        h1 { text-align: center; color: #c71585; font-size: 24px; margin-bottom: 8px; }
        p { text-align: center; color: #d8a0b0; font-size: 13px; margin-bottom: 25px; }
        label { display: block; margin-bottom: 6px; color: #d63384; font-weight: 600; font-size: 14px; }
        input { width: 100%; padding: 13px 15px; border: 2px solid #ffc0cb; border-radius: 12px; margin-bottom: 18px; font-size: 15px; outline: none; }
        input:focus { border-color: #ff69b4; }
        button { width: 100%; padding: 14px; border: none; border-radius: 30px; background: #ff69b4; color: white; font-size: 16px; cursor: pointer; }
        button:hover { background: #ff1493; }
        .error { background: #ffe0e6; color: #c71585; padding: 12px; border-radius: 12px; margin-bottom: 18px; text-align: center; font-size: 14px; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #d8a0b0; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🌸 Login Admin</h1>
        <p>Recording Ayam Broiler<br>Yayasan Putra Jaya Jabal Rahma</p>
        <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required autofocus>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
        <div class="footer"> Masukkan Username dan Password </div>
    </div>
</body>
</html>
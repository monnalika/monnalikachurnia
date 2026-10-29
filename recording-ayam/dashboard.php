<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$total_recording = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM recording"))['jml'];
$total_mati = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(mati) as jml FROM recording"))['jml'] ?? 0;
$total_afkir = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(afkir) as jml FROM recording"))['jml'] ?? 0;
$total_pakan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_pakan) as jml FROM recording"))['jml'] ?? 0;
$sisa_ayam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT sisa_ayam FROM recording ORDER BY tanggal DESC, id DESC LIMIT 1"))['sisa_ayam'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: linear-gradient(135deg, #fff0f5, #ffe4e9); min-height: 100vh; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; }
        header { background: #ffb6c1; color: white; padding: 20px 30px; border-radius: 20px; text-align: center; margin-bottom: 20px; }
        header h1 { font-size: 24px; }
        .menu { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn { background: #ff69b4; color: white; padding: 10px 18px; border-radius: 30px; text-decoration: none; font-size: 14px; }
        .btn-secondary { background: #ffc0cb; color: #c71585; }
        .btn-active { background: #ff1493; }
        .btn-logout { background: #ff6b6b; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; }
        .card { background: white; border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 4px 15px rgba(255,182,193,0.25); }
        .card h3 { color: #c71585; font-size: 13px; margin-bottom: 8px; }
        .card .angka { font-size: 26px; font-weight: bold; color: #ff69b4; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🌸 Dashboard Recording Ayam Broiler</h1>
            <p>Yayasan Putra Jaya Jabal Rahma</p>
        </header>
        <div class="menu">
            <a href="dashboard.php" class="btn btn-active">🏠 Dashboard</a>
            <a href="index.php" class="btn btn-secondary">📋 Data Recording</a>
            <a href="tambah.php" class="btn btn-secondary">+ Tambah Data</a>
            <a href="panen.php" class="btn btn-secondary">🍗 Panen</a>
            <a href="laporan.php" class="btn btn-secondary">📊 Laporan</a>
            <a href="performance.php" class="btn btn-secondary">📈 Performance</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
        <div class="cards">
            <div class="card"><h3>Total Recording</h3><div class="angka"><?= $total_recording ?></div></div>
            <div class="card"><h3>Total Ayam Mati</h3><div class="angka"><?= number_format($total_mati) ?></div></div>
            <div class="card"><h3>Total Afkir</h3><div class="angka"><?= number_format($total_afkir) ?></div></div>
            <div class="card"><h3>Sisa Ayam</h3><div class="angka"><?= number_format($sisa_ayam) ?></div></div>
            <div class="card"><h3>Total Pakan (sak)</h3><div class="angka"><?= number_format($total_pakan, 1) ?></div></div>
        </div>
    </div>
</body>
</html>
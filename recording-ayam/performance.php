<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$q_awal = mysqli_query($koneksi, "SELECT ayam_masuk FROM recording WHERE ayam_masuk > 0 ORDER BY tanggal ASC LIMIT 1");
$populasi_awal = mysqli_fetch_assoc($q_awal)['ayam_masuk'] ?? 0;

$dep = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(mati) as mati, SUM(afkir) as afkir FROM recording"));
$total_deplesi = ($dep['mati'] ?? 0) + ($dep['afkir'] ?? 0);
$dep_persen = ($populasi_awal > 0) ? ($total_deplesi / $populasi_awal) * 100 : 0;

$total_pakan_sak = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_pakan) as t FROM recording"))['t'] ?? 0;
$total_pakan_kg = $total_pakan_sak * 50;

$panen = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jumlah_ekor) as ekor, SUM(total_kg) as kg FROM panen"));
$total_panen_ekor = $panen['ekor'] ?? 0;
$total_panen_kg = $panen['kg'] ?? 0;
$rata_bw = ($total_panen_ekor > 0) ? ($total_panen_kg * 1000) / $total_panen_ekor : 0;

$umur = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT umur FROM recording ORDER BY tanggal DESC LIMIT 1"))['umur'] ?? 0;
$livability = ($populasi_awal > 0) ? (($populasi_awal - $total_deplesi) / $populasi_awal) * 100 : 0;
$fcr = ($total_panen_kg > 0) ? $total_pakan_kg / $total_panen_kg : 0;
$adg = ($umur > 0) ? ($rata_bw - 40) / $umur : 0;
$ip = ($fcr > 0 && $umur > 0) ? ($livability * ($rata_bw/1000)) / ($fcr * $umur) * 100 : 0;
$sisa_ayam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT sisa_ayam FROM recording ORDER BY tanggal DESC LIMIT 1"))['sisa_ayam'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Performance</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:linear-gradient(135deg,#fff0f5,#ffe4e9); min-height:100vh; padding:20px; }
        .container { max-width:800px; margin:0 auto; }
        header { background:#ffb6c1; color:white; padding:20px; border-radius:20px; text-align:center; margin-bottom:20px; }
        .menu { display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; }
        .btn { background:#ff69b4; color:white; padding:10px 16px; border-radius:30px; text-decoration:none; font-size:13px; }
        .btn-secondary { background:#ffc0cb; color:#c71585; }
        .btn-active { background:#ff1493; }
        .btn-logout { background:#ff6b6b; }
        .card { background:white; border-radius:20px; padding:30px; }
        h2 { color:#c71585; text-align:center; margin-bottom:20px; }
        table { width:100%; border-collapse:collapse; font-size:15px; }
        th { background:#ffb6c1; color:white; padding:14px 18px; text-align:left; width:45%; }
        td { padding:14px 18px; border-bottom:1px solid #ffe4e9; font-weight:600; color:#ff69b4; }
    </style>
</head>
<body>
    <div class="container">
        <header><h1>🌸 Laporan Performance</h1></header>
        <div class="menu">
            <a href="dashboard.php" class="btn btn-secondary">🏠 Dashboard</a>
            <a href="index.php" class="btn btn-secondary">📋 Data Recording</a>
            <a href="tambah.php" class="btn btn-secondary">+ Tambah Data</a>
            <a href="panen.php" class="btn btn-secondary">🍗 Panen</a>
            <a href="laporan.php" class="btn btn-secondary">📊 Laporan</a>
            <a href="performance.php" class="btn btn-active">📈 Performance</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
        <div class="card">
            <h2>Hasil Akhir Periode</h2>
            <table>
                <tr><th>Total Panen</th><td><?= number_format($total_panen_ekor) ?> ekor / <?= number_format($total_panen_kg,1) ?> kg</td></tr>
                <tr><th>BW (Body Weight)</th><td><?= number_format($rata_bw,0) ?> gram</td></tr>
                <tr><th>DEP (Deplesi)</th><td><?= number_format($dep_persen,2) ?> % (<?= number_format($total_deplesi) ?> ekor)</td></tr>
                <tr><th>Pakan</th><td><?= number_format($total_pakan_sak,1) ?> sak (<?= number_format($total_pakan_kg) ?> kg)</td></tr>
                <tr><th>FCR</th><td><?= number_format($fcr,3) ?></td></tr>
                <tr><th>ADG</th><td><?= number_format($adg,1) ?> gram/hari</td></tr>
                <tr><th>IP (Index Performance)</th><td><?= number_format($ip,1) ?></td></tr>
                <tr><th>Livability</th><td><?= number_format($livability,2) ?> %</td></tr>
                <tr><th>Umur</th><td><?= $umur ?> hari</td></tr>
                <tr><th>Populasi Awal</th><td><?= number_format($populasi_awal) ?> ekor</td></tr>
                <tr><th>Sisa Ayam</th><td><?= number_format($sisa_ayam) ?> ekor</td></tr>
            </table>
        </div>
    </div>
</body>
</html>
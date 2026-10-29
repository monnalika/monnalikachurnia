<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$dari = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';

$sql = "SELECT * FROM recording WHERE 1=1";
if ($dari && $sampai) $sql .= " AND tanggal BETWEEN '$dari' AND '$sampai'";
$sql .= " ORDER BY tanggal DESC";
$query = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:linear-gradient(135deg,#fff0f5,#ffe4e9); min-height:100vh; padding:20px; }
        .container { max-width:1200px; margin:0 auto; }
        header { background:#ffb6c1; color:white; padding:20px; border-radius:20px; text-align:center; margin-bottom:20px; }
        .menu { display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; }
        .btn { background:#ff69b4; color:white; padding:10px 16px; border-radius:30px; text-decoration:none; font-size:13px; }
        .btn-secondary { background:#ffc0cb; color:#c71585; }
        .btn-active { background:#ff1493; }
        .btn-logout { background:#ff6b6b; }
        .card { background:white; border-radius:20px; padding:25px; }
        .filter { display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; align-items:end; }
        label { display:block; margin-bottom:5px; color:#d63384; font-size:13px; font-weight:600; }
        input { padding:10px; border:2px solid #ffc0cb; border-radius:10px; }
        button { padding:11px 20px; border:none; border-radius:30px; background:#ff69b4; color:white; cursor:pointer; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        th { background:#ffb6c1; color:white; padding:10px 6px; }
        td { padding:9px 6px; border-bottom:1px solid #ffe4e9; }
    </style>
</head>
<body>
    <div class="container">
        <header><h1>🌸 Laporan Recording</h1></header>
        <div class="menu">
            <a href="dashboard.php" class="btn btn-secondary">🏠 Dashboard</a>
            <a href="index.php" class="btn btn-secondary">📋 Data Recording</a>
            <a href="tambah.php" class="btn btn-secondary">+ Tambah Data</a>
            <a href="panen.php" class="btn btn-secondary">🍗 Panen</a>
            <a href="laporan.php" class="btn btn-active">📊 Laporan</a>
            <a href="performance.php" class="btn btn-secondary">📈 Performance</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
        <div class="card">
            <form method="GET" class="filter">
                <div><label>Dari Tanggal</label><input type="date" name="dari" value="<?= $dari ?>"></div>
                <div><label>Sampai Tanggal</label><input type="date" name="sampai" value="<?= $sampai ?>"></div>
                <button type="submit">🔍 Tampilkan</button>
            </form>
            <table>
                <thead>
                    <tr><th>Tanggal</th><th>Umur</th><th>Masuk</th><th>Mati</th><th>Afkir</th><th>Sisa</th><th>BW</th><th>Total Pakan</th></tr>
                </thead>
                <tbody>
                <?php while ($r = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($r['tanggal'])) ?></td>
                        <td><?= $r['umur'] ?></td>
                        <td><?= $r['ayam_masuk'] ?></td>
                        <td><?= $r['mati'] ?></td>
                        <td><?= $r['afkir'] ?></td>
                        <td><?= number_format($r['sisa_ayam']) ?></td>
                        <td><?= $r['body_weight'] ?></td>
                        <td><?= $r['total_pakan'] ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
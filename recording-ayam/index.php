<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM recording ORDER BY tanggal DESC");

$sisa_sb10 = $sisa_sb11 = $sisa_sb12 = 0;
$q_stok = mysqli_query($koneksi, "SELECT masuk_sb10, masuk_sb11, masuk_sb12, pakan_sb10, pakan_sb11, pakan_sb12 FROM recording");
while ($r = mysqli_fetch_assoc($q_stok)) {
    $sisa_sb10 += $r['masuk_sb10'] - $r['pakan_sb10'];
    $sisa_sb11 += $r['masuk_sb11'] - $r['pakan_sb11'];
    $sisa_sb12 += $r['masuk_sb12'] - $r['pakan_sb12'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Recording</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: linear-gradient(135deg, #fff0f5, #ffe4e9); min-height: 100vh; padding: 20px; }
        .container { max-width: 1300px; margin: 0 auto; }
        header { background: #ffb6c1; color: white; padding: 20px; border-radius: 20px; text-align: center; margin-bottom: 20px; }
        .menu { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn { background: #ff69b4; color: white; padding: 10px 16px; border-radius: 30px; text-decoration: none; font-size: 13px; }
        .btn-secondary { background: #ffc0cb; color: #c71585; }
        .btn-active { background: #ff1493; }
        .btn-logout { background: #ff6b6b; }
        .stok { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; }
        .stok-card { background: white; border-radius: 14px; padding: 14px; text-align: center; }
        .stok-card h3 { color: #c71585; font-size: 12px; }
        .stok-card .angka { font-size: 20px; font-weight: bold; color: #ff69b4; }
        .card { background: white; border-radius: 20px; padding: 20px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { background: #ffb6c1; color: white; padding: 10px 6px; text-align: left; }
        td { padding: 9px 6px; border-bottom: 1px solid #ffe4e9; }
        .badge { background: #ff69b4; color: white; padding: 3px 8px; border-radius: 20px; font-size: 11px; }
        .aksi a { text-decoration: none; margin-right: 4px; font-size: 12px; padding: 4px 8px; border-radius: 20px; }
        .btn-edit { background: #ffc0cb; color: #c71585; }
        .btn-hapus { background: #ff6b6b; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <header><h1>🌸 Data Recording Harian</h1></header>
        <div class="menu">
            <a href="dashboard.php" class="btn btn-secondary">🏠 Dashboard</a>
            <a href="index.php" class="btn btn-active">📋 Data Recording</a>
            <a href="tambah.php" class="btn btn-secondary">+ Tambah Data</a>
            <a href="panen.php" class="btn btn-secondary">🍗 Panen</a>
            <a href="laporan.php" class="btn btn-secondary">📊 Laporan</a>
            <a href="performance.php" class="btn btn-secondary">📈 Performance</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
        <div class="stok">
            <div class="stok-card"><h3>Sisa SB10</h3><div class="angka"><?= number_format($sisa_sb10,1) ?> sak</div></div>
            <div class="stok-card"><h3>Sisa SB11</h3><div class="angka"><?= number_format($sisa_sb11,1) ?> sak</div></div>
            <div class="stok-card"><h3>Sisa SB12</h3><div class="angka"><?= number_format($sisa_sb12,1) ?> sak</div></div>
        </div>
        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th><th>Umur</th><th>Masuk</th><th>Mati</th><th>Afkir</th>
                        <th>Sisa</th><th>BW</th><th>Pakan Masuk</th><th>Pakan Pakai</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($query) > 0): while ($row = mysqli_fetch_assoc($query)): ?>
                    <tr>
                        <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                        <td><span class="badge"><?= $row['umur'] ?>h</span></td>
                        <td><?= $row['ayam_masuk'] ?></td>
                        <td><?= $row['mati'] ?></td>
                        <td><?= $row['afkir'] ?></td>
                        <td><b><?= number_format($row['sisa_ayam']) ?></b></td>
                        <td><?= $row['body_weight'] > 0 ? number_format($row['body_weight'],0) : '-' ?></td>
                        <td style="font-size:11px">
                            <?php
                            $m = [];
                            if ($row['masuk_sb10'] > 0) $m[] = "10:".$row['masuk_sb10'];
                            if ($row['masuk_sb11'] > 0) $m[] = "11:".$row['masuk_sb11'];
                            if ($row['masuk_sb12'] > 0) $m[] = "12:".$row['masuk_sb12'];
                            echo $m ? implode('<br>', $m) : '-';
                            ?>
                        </td>
                        <td style="font-size:11px">
                            <?php
                            $p = [];
                            if ($row['pakan_sb10'] > 0) $p[] = "10:".$row['pakan_sb10'];
                            if ($row['pakan_sb11'] > 0) $p[] = "11:".$row['pakan_sb11'];
                            if ($row['pakan_sb12'] > 0) $p[] = "12:".$row['pakan_sb12'];
                            echo $p ? implode('<br>', $p) : '-';
                            ?>
                        </td>
                        <td class="aksi">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit">✏️</a>
                            <a href="hapus.php?id=<?= $row['id'] ?>" class="btn-hapus" onclick="return confirm('Yakin hapus?')">🗑️</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="10" style="text-align:center;padding:30px;color:#d8a0b0;">Belum ada data</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
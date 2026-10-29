<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'];
    $jumlah_ekor = (int)$_POST['jumlah_ekor'];
    $total_kg = (float)$_POST['total_kg'];
    $keterangan = $_POST['keterangan'];
    $rata_bw = ($jumlah_ekor > 0) ? ($total_kg * 1000) / $jumlah_ekor : 0;
    mysqli_query($koneksi, "INSERT INTO panen (tanggal, jumlah_ekor, total_kg, rata_bw, keterangan) VALUES ('$tanggal','$jumlah_ekor','$total_kg','$rata_bw','$keterangan')");
    header("Location: panen.php");
    exit;
}

$data = mysqli_query($koneksi, "SELECT * FROM panen ORDER BY tanggal DESC");
$tot = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jumlah_ekor) as ekor, SUM(total_kg) as kg FROM panen"));
$total_ekor = $tot['ekor'] ?? 0;
$total_kg = $tot['kg'] ?? 0;
$rata_all = ($total_ekor > 0) ? ($total_kg * 1000) / $total_ekor : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Panen</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:linear-gradient(135deg,#fff0f5,#ffe4e9); min-height:100vh; padding:20px; }
        .container { max-width:1000px; margin:0 auto; }
        header { background:#ffb6c1; color:white; padding:20px; border-radius:20px; text-align:center; margin-bottom:20px; }
        .menu { display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap; }
        .btn { background:#ff69b4; color:white; padding:10px 16px; border-radius:30px; text-decoration:none; font-size:13px; }
        .btn-secondary { background:#ffc0cb; color:#c71585; }
        .btn-active { background:#ff1493; }
        .btn-logout { background:#ff6b6b; }
        .grid { display:grid; grid-template-columns:1fr 1.2fr; gap:20px; }
        .card { background:white; border-radius:20px; padding:25px; }
        h2 { color:#c71585; margin-bottom:15px; font-size:18px; }
        label { display:block; margin-bottom:5px; color:#d63384; font-weight:600; font-size:13px; }
        input, textarea { width:100%; padding:11px; border:2px solid #ffc0cb; border-radius:12px; margin-bottom:14px; }
        button { width:100%; padding:13px; border:none; border-radius:30px; background:#ff69b4; color:white; font-size:15px; cursor:pointer; }
        button:hover { background:#ff1493; }
        table { width:100%; border-collapse:collapse; font-size:13px; }
        th { background:#ffb6c1; color:white; padding:10px 8px; }
        td { padding:10px 8px; border-bottom:1px solid #ffe4e9; }
        .stats { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:15px; }
        .stat { background:#fff0f5; border-radius:12px; padding:12px; text-align:center; }
        .stat h3 { color:#c71585; font-size:12px; }
        .stat .angka { font-size:18px; font-weight:bold; color:#ff69b4; }
        .btn-edit { background:#ffc0cb; color:#c71585; padding:5px 12px; border-radius:20px; text-decoration:none; font-size:12px; }
        @media (max-width:768px) { .grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <header><h1>🌸 Data Panen</h1></header>
        <div class="menu">
            <a href="dashboard.php" class="btn btn-secondary">🏠 Dashboard</a>
            <a href="index.php" class="btn btn-secondary">📋 Data Recording</a>
            <a href="tambah.php" class="btn btn-secondary">+ Tambah Data</a>
            <a href="panen.php" class="btn btn-active">🍗 Panen</a>
            <a href="laporan.php" class="btn btn-secondary">📊 Laporan</a>
            <a href="performance.php" class="btn btn-secondary">📈 Performance</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>
        <div class="grid">
            <div class="card">
                <h2>➕ Input Panen</h2>
                <form method="POST">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>">
                    <label>Jumlah Ekor</label>
                    <input type="number" name="jumlah_ekor" required min="1">
                    <label>Total Berat (kg)</label>
                    <input type="number" step="0.01" name="total_kg" required>
                    <label>Keterangan</label>
                    <textarea name="keterangan" rows="2"></textarea>
                    <button type="submit" name="simpan">💾 Simpan Panen</button>
                </form>
            </div>
            <div class="card">
                <h2>📋 Riwayat Panen</h2>
                <div class="stats">
                    <div class="stat"><h3>Total Ekor</h3><div class="angka"><?= number_format($total_ekor) ?></div></div>
                    <div class="stat"><h3>Total Kg</h3><div class="angka"><?= number_format($total_kg,1) ?></div></div>
                    <div class="stat"><h3>Rata BW</h3><div class="angka"><?= number_format($rata_all,0) ?>g</div></div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Ekor</th>
                            <th>Kg</th>
                            <th>BW</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($data) > 0): ?>
                        <?php while ($r = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td><?= date('d-m-Y', strtotime($r['tanggal'])) ?></td>
                            <td><?= number_format($r['jumlah_ekor']) ?></td>
                            <td><?= number_format($r['total_kg'],1) ?></td>
                            <td><?= number_format($r['rata_bw'],0) ?></td>
                            <td>
                                <a href="edit_panen.php?id=<?= $r['id'] ?>" class="btn-edit">✏️ Edit</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px; color:#d8a0b0;">Belum ada data panen</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
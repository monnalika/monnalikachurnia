<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$q = mysqli_query($koneksi, "SELECT sisa_ayam FROM recording ORDER BY tanggal DESC, id DESC LIMIT 1");
$sisa_kemarin = mysqli_fetch_assoc($q)['sisa_ayam'] ?? 0;

if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'];
    $umur = (int)$_POST['umur'];
    $ayam_masuk = (int)$_POST['ayam_masuk'];
    $mati = (int)$_POST['mati'];
    $afkir = (int)$_POST['afkir'];
    $body_weight = (float)$_POST['body_weight'];
    $masuk_sb10 = (float)$_POST['masuk_sb10'];
    $masuk_sb11 = (float)$_POST['masuk_sb11'];
    $masuk_sb12 = (float)$_POST['masuk_sb12'];
    $pakan_sb10 = (float)$_POST['pakan_sb10'];
    $pakan_sb11 = (float)$_POST['pakan_sb11'];
    $pakan_sb12 = (float)$_POST['pakan_sb12'];
    $keterangan = $_POST['keterangan'];

    if ($ayam_masuk > 0) {
        $sisa_ayam = $ayam_masuk - $mati - $afkir;
    } else {
        $sisa_ayam = $sisa_kemarin - $mati - $afkir;
    }
    if ($sisa_ayam < 0) $sisa_ayam = 0;
    $total_pakan = $pakan_sb10 + $pakan_sb11 + $pakan_sb12;

    mysqli_query($koneksi, "INSERT INTO recording 
        (tanggal, umur, ayam_masuk, mati, afkir, sisa_ayam, body_weight, masuk_sb10, masuk_sb11, masuk_sb12, pakan_sb10, pakan_sb11, pakan_sb12, total_pakan, keterangan) 
        VALUES ('$tanggal','$umur','$ayam_masuk','$mati','$afkir','$sisa_ayam','$body_weight','$masuk_sb10','$masuk_sb11','$masuk_sb12','$pakan_sb10','$pakan_sb11','$pakan_sb12','$total_pakan','$keterangan')");
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Recording</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:linear-gradient(135deg,#fff0f5,#ffe4e9); min-height:100vh; padding:30px 20px; }
        .container { max-width:650px; margin:0 auto; background:white; border-radius:25px; padding:30px; box-shadow:0 8px 30px rgba(255,182,193,0.3); }
        h1 { text-align:center; color:#c71585; margin-bottom:20px; font-size:22px; }
        label { display:block; margin-bottom:5px; color:#d63384; font-weight:600; font-size:13px; }
        input, textarea { width:100%; padding:11px 14px; border:2px solid #ffc0cb; border-radius:12px; margin-bottom:14px; font-size:14px; outline:none; }
        .row { display:flex; gap:12px; }
        .row > div { flex:1; }
        .section { background:#ffb6c1; color:white; padding:8px 14px; border-radius:10px; font-size:14px; margin:15px 0 12px; }
        .btn-group { display:flex; gap:12px; margin-top:10px; }
        button, .batal { flex:1; padding:13px; border:none; border-radius:30px; font-size:15px; cursor:pointer; text-align:center; text-decoration:none; }
        button { background:#ff69b4; color:white; }
        .batal { background:#ffc0cb; color:#c71585; }
        .info { background:#fff0f5; border:1px solid #ffc0cb; border-radius:12px; padding:12px; margin-bottom:18px; font-size:13px; color:#c71585; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🌸 Tambah Recording Harian</h1>
        <div class="info">📌 Sisa ayam kemarin: <b><?= number_format($sisa_kemarin) ?> ekor</b></div>
        <form method="POST">
            <label>Tanggal</label>
            <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>">
            <div class="row">
                <div><label>Umur (hari)</label><input type="number" name="umur" required></div>
                <div><label>Ayam Masuk</label><input type="number" name="ayam_masuk" value="0" min="0"></div>
            </div>
            <div class="row">
                <div><label>Ayam Mati</label><input type="number" name="mati" value="0" min="0"></div>
                <div><label>Afkir</label><input type="number" name="afkir" value="0" min="0"></div>
            </div>
            <label>Body Weight (gram)</label>
            <input type="number" step="0.01" name="body_weight" value="0">
            <div class="section">📥 Pakan Masuk</div>
            <div class="row">
                <div><label>SB10</label><input type="number" step="0.01" name="masuk_sb10" value="0"></div>
                <div><label>SB11</label><input type="number" step="0.01" name="masuk_sb11" value="0"></div>
                <div><label>SB12</label><input type="number" step="0.01" name="masuk_sb12" value="0"></div>
            </div>
            <div class="section">🌾 Pakan Pakai</div>
            <div class="row">
                <div><label>SB10</label><input type="number" step="0.01" name="pakan_sb10" value="0"></div>
                <div><label>SB11</label><input type="number" step="0.01" name="pakan_sb11" value="0"></div>
                <div><label>SB12</label><input type="number" step="0.01" name="pakan_sb12" value="0"></div>
            </div>
            <label>Keterangan</label>
            <textarea name="keterangan" rows="2"></textarea>
            <div class="btn-group">
                <a href="index.php" class="batal">Batal</a>
                <button type="submit" name="simpan">💾 Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
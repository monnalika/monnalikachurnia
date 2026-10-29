<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$id = (int)$_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM recording WHERE id='$id'"));
if (!$data) { echo "Data tidak ditemukan"; exit; }

if (isset($_POST['update'])) {
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

    $q = mysqli_query($koneksi, "SELECT sisa_ayam FROM recording WHERE (tanggal < '$tanggal') OR (tanggal='$tanggal' AND id < '$id') ORDER BY tanggal DESC, id DESC LIMIT 1");
    $sisa_sebelum = mysqli_fetch_assoc($q)['sisa_ayam'] ?? 0;

    if ($ayam_masuk > 0) $sisa_ayam = $ayam_masuk - $mati - $afkir;
    else $sisa_ayam = $sisa_sebelum - $mati - $afkir;
    if ($sisa_ayam < 0) $sisa_ayam = 0;
    $total_pakan = $pakan_sb10 + $pakan_sb11 + $pakan_sb12;

    mysqli_query($koneksi, "UPDATE recording SET 
        tanggal='$tanggal', umur='$umur', ayam_masuk='$ayam_masuk', mati='$mati', afkir='$afkir',
        sisa_ayam='$sisa_ayam', body_weight='$body_weight',
        masuk_sb10='$masuk_sb10', masuk_sb11='$masuk_sb11', masuk_sb12='$masuk_sb12',
        pakan_sb10='$pakan_sb10', pakan_sb11='$pakan_sb11', pakan_sb12='$pakan_sb12',
        total_pakan='$total_pakan', keterangan='$keterangan' WHERE id='$id'");
    
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Recording</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:linear-gradient(135deg,#fff0f5,#ffe4e9); min-height:100vh; padding:30px 20px; }
        .container { max-width:650px; margin:0 auto; background:white; border-radius:25px; padding:30px; }
        h1 { text-align:center; color:#c71585; margin-bottom:20px; }
        label { display:block; margin-bottom:5px; color:#d63384; font-weight:600; font-size:13px; }
        input, textarea { width:100%; padding:11px 14px; border:2px solid #ffc0cb; border-radius:12px; margin-bottom:14px; }
        .row { display:flex; gap:12px; } .row > div { flex:1; }
        .section { background:#ffb6c1; color:white; padding:8px 14px; border-radius:10px; margin:12px 0 10px; font-size:14px; }
        .btn-group { display:flex; gap:12px; }
        button, .batal { flex:1; padding:13px; border:none; border-radius:30px; text-align:center; text-decoration:none; font-size:15px; }
        button { background:#ff69b4; color:white; cursor:pointer; }
        .batal { background:#ffc0cb; color:#c71585; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Edit Recording</h1>
        <form method="POST">
            <label>Tanggal</label>
            <input type="date" name="tanggal" required value="<?= $data['tanggal'] ?>">
            <div class="row">
                <div><label>Umur</label><input type="number" name="umur" value="<?= $data['umur'] ?>" required></div>
                <div><label>Ayam Masuk</label><input type="number" name="ayam_masuk" value="<?= $data['ayam_masuk'] ?>"></div>
            </div>
            <div class="row">
                <div><label>Mati</label><input type="number" name="mati" value="<?= $data['mati'] ?>"></div>
                <div><label>Afkir</label><input type="number" name="afkir" value="<?= $data['afkir'] ?>"></div>
            </div>
            <label>Body Weight (gram)</label>
            <input type="number" step="0.01" name="body_weight" value="<?= $data['body_weight'] ?>">
            <div class="section">📥 Pakan Masuk</div>
            <div class="row">
                <div><label>SB10</label><input type="number" step="0.01" name="masuk_sb10" value="<?= $data['masuk_sb10'] ?>"></div>
                <div><label>SB11</label><input type="number" step="0.01" name="masuk_sb11" value="<?= $data['masuk_sb11'] ?>"></div>
                <div><label>SB12</label><input type="number" step="0.01" name="masuk_sb12" value="<?= $data['masuk_sb12'] ?>"></div>
            </div>
            <div class="section">🌾 Pakan Pakai</div>
            <div class="row">
                <div><label>SB10</label><input type="number" step="0.01" name="pakan_sb10" value="<?= $data['pakan_sb10'] ?>"></div>
                <div><label>SB11</label><input type="number" step="0.01" name="pakan_sb11" value="<?= $data['pakan_sb11'] ?>"></div>
                <div><label>SB12</label><input type="number" step="0.01" name="pakan_sb12" value="<?= $data['pakan_sb12'] ?>"></div>
            </div>
            <label>Keterangan</label>
            <textarea name="keterangan" rows="2"><?= $data['keterangan'] ?></textarea>
            <div class="btn-group">
                <a href="index.php" class="batal">Batal</a>
                <button type="submit" name="update">💾 Update</button>
            </div>
        </form>
    </div>
</body>
</html>
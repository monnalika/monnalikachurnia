<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$id = (int)$_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM panen WHERE id='$id'"));
if (!$data) { echo "Data tidak ditemukan"; exit; }

if (isset($_POST['update'])) {
    $tanggal = $_POST['tanggal'];
    $jumlah_ekor = (int)$_POST['jumlah_ekor'];
    $total_kg = (float)$_POST['total_kg'];
    $keterangan = $_POST['keterangan'];
    $rata_bw = ($jumlah_ekor > 0) ? ($total_kg * 1000) / $jumlah_ekor : 0;

    mysqli_query($koneksi, "UPDATE panen SET 
        tanggal='$tanggal',
        jumlah_ekor='$jumlah_ekor',
        total_kg='$total_kg',
        rata_bw='$rata_bw',
        keterangan='$keterangan'
        WHERE id='$id'");
    
    header("Location: panen.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Panen</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
        body { background:linear-gradient(135deg,#fff0f5,#ffe4e9); min-height:100vh; padding:30px 20px; }
        .container { max-width:500px; margin:0 auto; background:white; border-radius:25px; padding:30px; box-shadow:0 8px 30px rgba(255,182,193,0.3); }
        h1 { text-align:center; color:#c71585; margin-bottom:20px; font-size:22px; }
        label { display:block; margin-bottom:5px; color:#d63384; font-weight:600; font-size:13px; }
        input, textarea { width:100%; padding:11px 14px; border:2px solid #ffc0cb; border-radius:12px; margin-bottom:14px; font-size:14px; outline:none; }
        input:focus, textarea:focus { border-color:#ff69b4; }
        .btn-group { display:flex; gap:12px; margin-top:10px; }
        button, .batal { flex:1; padding:13px; border:none; border-radius:30px; font-size:15px; cursor:pointer; text-align:center; text-decoration:none; }
        button { background:#ff69b4; color:white; }
        button:hover { background:#ff1493; }
        .batal { background:#ffc0cb; color:#c71585; }
    </style>
</head>
<body>
    <div class="container">
        <h1>✏️ Edit Data Panen</h1>
        <form method="POST">
            <label>Tanggal</label>
            <input type="date" name="tanggal" required value="<?= $data['tanggal'] ?>">

            <label>Jumlah Ekor</label>
            <input type="number" name="jumlah_ekor" required min="1" value="<?= $data['jumlah_ekor'] ?>">

            <label>Total Berat (kg)</label>
            <input type="number" step="0.01" name="total_kg" required value="<?= $data['total_kg'] ?>">

            <label>Keterangan</label>
            <textarea name="keterangan" rows="2"><?= $data['keterangan'] ?></textarea>

            <div class="btn-group">
                <a href="panen.php" class="batal">Batal</a>
                <button type="submit" name="update">💾 Update</button>
            </div>
        </form>
    </div>
</body>
</html>
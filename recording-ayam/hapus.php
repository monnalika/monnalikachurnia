<?php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
include 'koneksi.php';

$id = (int)$_GET['id'];
mysqli_query($koneksi, "DELETE FROM recording WHERE id='$id'");
header("Location: index.php");
exit;
?>
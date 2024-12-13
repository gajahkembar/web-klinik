<?php
// Mulai sesi jika belum dimulai
session_start();

// Menghancurkan semua data sesi
session_destroy();

// Menghapus variabel sesi jika ada
unset($_SESSION['nik']);
unset($_SESSION['username']);

// Redirect ke halaman login atau halaman yang diinginkan setelah logout
header("Location: login.php");
exit;
?>

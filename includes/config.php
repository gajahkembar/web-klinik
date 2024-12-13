<?php
if (!defined('DB_SERVER')) {
    define('DB_SERVER', 'localhost');  // Ganti dengan host database Anda
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');     // Ganti dengan username database Anda
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');         // Ganti dengan password database Anda
}

if (!defined('DB_DATABASE')) {
    define('DB_DATABASE', 'klinik');   // Ganti dengan nama database Anda
}

// Koneksi ke database menggunakan PDO
try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

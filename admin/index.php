<?php
session_start();
// Include database connection file
include('../includes/config.php');
include('../includes/db.php');

// Cek apakah admin sudah login
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: login.php");
    exit;
}

// Ambil data pengguna dari session
$username = $_SESSION['username'];
$nama = $_SESSION['nama'];

// Query untuk menghitung jumlah data
$query_dokter = $pdo->query("SELECT COUNT(*) as total FROM dokter");
$count_dokter = $query_dokter->fetch()['total'];

$query_pasien = $pdo->query("SELECT COUNT(*) as total FROM pasien");
$count_pasien = $query_pasien->fetch()['total'];

$query_poli = $pdo->query("SELECT COUNT(*) as total FROM poli");
$count_poli = $query_poli->fetch()['total'];

$query_obat = $pdo->query("SELECT COUNT(*) as total FROM obat");
$count_obat = $query_obat->fetch()['total'];

// Query to count the number of unexamined patients
$query_antrian = $pdo->query("
    SELECT COUNT(*) as total 
    FROM daftar_poli dp
    JOIN pasien p ON dp.id_pasien = p.id
    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
    JOIN dokter d ON jp.id_dokter = d.id
    JOIN poli pl ON d.id_poli = pl.id
    WHERE NOT EXISTS (
        SELECT 1 FROM periksa WHERE periksa.id_daftar_poli = dp.id
    )
");
$count_antrian = $query_antrian->fetch()['total'];
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Dashboard Cards -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $count_dokter; ?></h3>
                            <p>Dokter</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <a href="dokter.php" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3><?= $count_antrian; ?></h3>
                            <p>Antrian Pasien</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <a href="antrian.php" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $count_poli; ?></h3>
                            <p>Poli</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <a href="poli.php" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3><?= $count_obat; ?></h3>
                            <p>Obat</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-pills"></i>
                        </div>
                        <a href="obat.php" class="small-box-footer">
                            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- AdminLTE JS (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
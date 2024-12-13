<?php
// Start session and check login
require_once '../includes/config.php';

// Ambil data dokter berdasarkan session
$id_dokter = $_SESSION['id'];
$stmt = $pdo->prepare("SELECT d.id, d.nama, d.alamat, d.no_hp, d.id_poli, p.nama_poli 
                       FROM dokter d 
                       JOIN poli p ON d.id_poli = p.id
                       WHERE d.id = :id");
$stmt->execute([':id' => $id_dokter]);
$dokter = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika data dokter tidak ditemukan
if (!$dokter) {
    header("Location: login.php");
    exit;
}

// Filter data jadwal periksa berdasarkan id_dokter di session
$id_dokter = $_SESSION['id']; // Ambil id_dokter dari sesi pengguna
$sql = "SELECT jp.*, d.nama AS nama_dokter 
        FROM jadwal_periksa jp 
        JOIN dokter d ON jp.id_dokter = d.id 
        WHERE jp.id_dokter = :id_dokter";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_dokter' => $id_dokter]);
$jadwal_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Dokter | Klinik Mitra Waras</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <style>
    html, body {
        height: 100%; /* Pastikan body dan html penuh */
        margin: 0;    /* Hilangkan margin default */
    }

    .wrapper {
        min-height: 100%; /* Wrapper mengambil setidaknya 100% tinggi layar */
        display: flex;
        flex-direction: column; /* Menggunakan flexbox untuk kolom vertikal */
    }

    .content-wrapper {
        flex: 1; /* Mengisi sisa ruang */
    }

    .nav-link.active {
        background-color: #007bff;
        color: white;
    }
    /* Customize pagination with a border */
    .dataTables_paginate {
        display: flex;
        justify-content: center;
        margin-top: 15px;
        margin-bottom: 50px;
    }

    .dataTables_paginate .paginate_button {
        border: 1px solid #ddd; /* Add border */
        padding: 5px 10px;
        margin: 0 2px;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Highlight active page */
    .dataTables_paginate .paginate_button.current {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff; /* Optional: Add border to active page button */
    }

    /* Hover effect for pagination buttons */
    .dataTables_paginate .paginate_button:hover {
        background-color: #007bff;
        color: white;
    }

    /* Add icons for pagination buttons */
    .dataTables_paginate .paginate_button.previous,
    .dataTables_paginate .paginate_button.next {
        font-size: 16px; /* Adjust size for pagination arrows */
    }

    /* Remove padding/margin from surrounding elements */
    .container-fluid, .row {
        padding: 0;
        margin: 0;
    }

    table {
        width: 100% !important;
        table-layout: fixed; /* Ensures the table stretches to the right edge */
    }

    th, td {
        text-align: left;
        padding: 8px; /* Adjust padding as per your design */
    }

    table td {
        word-wrap: break-word; /* Breaks long text if necessary */
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link">
      <img src="../assets/klinik.png" alt="Klinik Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Klinik Mitra Waras</span>
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <i class="fas fa-user-circle img-circle elevation-2" style="font-size: 3rem; color: white;"></i>
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo htmlspecialchars($dokter['nama']); ?></a>
          <span class="d-block text-sm" style="color: white;">Poli: <?php echo htmlspecialchars($dokter['nama_poli']); ?></span>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-accordion="false">
          <li class="nav-item">
          <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="jadwal_periksa.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'jadwal_periksa.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Jadwal Periksa</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="memeriksa_pasien.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'memeriksa_pasien.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-user-md"></i>
              <p>Memeriksa Pasien</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="riwayat_pasien.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'riwayat_pasien.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-history"></i>
              <p>Riwayat Pasien</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="profil.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'profil.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-user"></i>
              <p>Profil</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'logout.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
  <!-- /.sidebar -->
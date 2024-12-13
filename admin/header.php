<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Klinik</title>
    <!-- AdminLTE CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <!-- Font Awesome (optional, jika belum ada) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

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

        /* Menandai item yang aktif dengan warna berbeda */
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

        /* Footer Style */
        footer {
            margin-left: -250px;
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 50px; /* Tinggi footer */
            background: #f8f9fa; /* Warna footer */
            text-align: center;
            padding: 10px 0;
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
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">Klinik Admin</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i> <!-- Dashboard Icon -->
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="dokter.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dokter.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-user-md"></i> <!-- Doctor Icon -->
                                <p>Dokter</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="pasien.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'pasien.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-users"></i> <!-- Users Icon -->
                                <p>Pasien</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="poli.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'poli.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-hospital"></i> <!-- Hospital Icon -->
                                <p>Poli</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="obat.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'obat.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-pills"></i> <!-- Pills Icon -->
                                <p>Obat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="jadwal_periksa.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'jadwal_periksa.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-calendar-alt"></i> <!-- Calendar Icon -->
                                <p>Jadwal Periksa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="riwayat_periksa.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'riwayat_periksa.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-history"></i> <!-- History Icon -->
                                <p>Riwayat Periksa</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="antrian.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'antrian.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-list"></i> <!-- Queue Icon -->
                                <p>Antrian</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'logout.php') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-sign-out-alt"></i> <!-- Logout Icon -->
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
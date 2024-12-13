<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Mitra Waras</title>
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .brand-logo {
            width: 600px;
            height: auto;
        }
        .slogan {
            font-size: 18px;
            color: #6c757d;
        }
        .btn-masuk {
            padding: 15px 30px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <section class="content-header text-center mt-5">
            <div class="container">
                <img src="assets/klinik.png" alt="Klinik Mitra Waras" class="brand-logo">
                <h1 class="mt-3">Klinik Mitra Waras</h1>
                <p class="slogan">"Mitra Kesehatan Anda yang Terpercaya"</p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="content mt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <!-- Card Masuk Pasien -->
                    <div class="col-md-4">
                        <div class="card card-primary">
                            <div class="card-header text-center">
                                <h5 class="card-title">Masuk Sebagai Pasien</h5>
                            </div>
                            <div class="card-body text-center">
                                <p>Akses halaman pasien untuk membuat janji, melihat resep, dan lainnya.</p>
                                <a href="pasien/login.php" class="btn btn-primary btn-masuk">
                                    <i class="fas fa-user-injured"></i> Masuk
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Masuk Dokter -->
                    <div class="col-md-4">
                        <div class="card card-success">
                            <div class="card-header text-center">
                                <h5 class="card-title">Masuk Sebagai Dokter</h5>
                            </div>
                            <div class="card-body text-center">
                                <p>Kelola jadwal, pasien, dan layanan melalui halaman dokter.</p>
                                <a href="dokter/login.php" class="btn btn-success btn-masuk">
                                    <i class="fas fa-user-md"></i> Masuk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>

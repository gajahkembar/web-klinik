<?php
// Pastikan sesi dimulai dan hanya pengguna yang login dapat mengakses halaman ini
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit;
}

// Simulasi data berita kesehatan, jika menggunakan API atau database, ganti dengan logika yang sesuai
$beritaKesehatan = [
    [
        'judul' => 'Cara Menjaga Kesehatan Jantung',
        'tanggal' => '2024-12-10',
        'konten' => 'Menjaga kesehatan jantung sangat penting untuk memastikan tubuh tetap sehat dan bugar. Beberapa tips untuk menjaga kesehatan jantung termasuk olahraga teratur dan makan makanan yang sehat...',
        'link' => '#'
    ],
    [
        'judul' => 'Pentingnya Tidur Cukup bagi Kesehatan',
        'tanggal' => '2024-12-09',
        'konten' => 'Tidur yang cukup adalah salah satu faktor penting untuk menjaga kesehatan tubuh. Kurang tidur dapat menyebabkan berbagai masalah kesehatan, termasuk gangguan mental dan fisik...',
        'link' => '#'
    ],
    [
        'judul' => 'Manfaat Olahraga di Pagi Hari',
        'tanggal' => '2024-12-08',
        'konten' => 'Olahraga di pagi hari dapat memberikan banyak manfaat bagi tubuh, termasuk meningkatkan energi dan metabolisme tubuh. Cobalah berjalan cepat atau berlari ringan di pagi hari...',
        'link' => '#'
    ],
    [
        'judul' => 'Mengatasi Stres dengan Teknik Relaksasi',
        'tanggal' => '2024-12-07',
        'konten' => 'Stres dapat mempengaruhi kesehatan mental dan fisik. Dengan teknik relaksasi seperti meditasi atau pernapasan dalam, Anda dapat mengurangi tingkat stres dan menjaga keseimbangan tubuh...',
        'link' => '#'
    ],
    [
        'judul' => 'Pentingnya Cek Kesehatan Rutin',
        'tanggal' => '2024-12-06',
        'konten' => 'Melakukan cek kesehatan rutin adalah cara terbaik untuk memastikan bahwa tubuh Anda dalam keadaan sehat. Beberapa pemeriksaan penting yang perlu dilakukan setiap tahun...',
        'link' => '#'
    ]
];
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Berita Kesehatan Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <?php foreach ($beritaKesehatan as $berita): ?>
                                <div class="mb-3">
                                    <h5><a href="<?php echo $berita['link']; ?>"><?php echo htmlspecialchars($berita['judul']); ?></a></h5>
                                    <p class="text-muted"><i><?php echo $berita['tanggal']; ?></i></p>
                                    <p><?php echo substr($berita['konten'], 0, 150); ?>...</p>
                                    <a href="<?php echo $berita['link']; ?>" class="btn btn-info btn-sm">Baca Selengkapnya</a>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Footer -->
<footer class="main-footer">
    <strong>&copy; 2024 Klinik Mitra Waras</strong>
</footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>
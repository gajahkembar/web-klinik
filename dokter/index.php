<?php
// Start session and check login
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
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
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Selamat Datang, <?php echo htmlspecialchars($dokter['nama']); ?>!</h3>
              </div>
              <div class="card-body">
                <p>Poli Anda: <?php echo htmlspecialchars($dokter['nama_poli']); ?></p>
                <p>Alamat: <?php echo htmlspecialchars($dokter['alamat']); ?></p>
                <p>Nomor HP: <?php echo htmlspecialchars($dokter['no_hp']); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>&copy; 2024 Klinik Mitra Waras.</strong> All rights reserved.
  </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>

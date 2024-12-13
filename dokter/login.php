<?php
// Koneksi ke database
require_once '../includes/config.php';

// Notifikasi status
$alert = "";

// Handle form submission untuk login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];

    // Validasi login menggunakan nama dan alamat
    $stmt = $pdo->prepare("SELECT * FROM dokter WHERE nama = :nama AND alamat = :alamat");
    $stmt->execute([':nama' => $nama, ':alamat' => $alamat]);
    $dokter = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($dokter) {
        // Login berhasil
        session_start();
        $_SESSION['id'] = $dokter['id'];
        $_SESSION['nama'] = $dokter['nama'];
        $_SESSION['id_poli'] = $dokter['id_poli'];
        $_SESSION['id_dokter'] = $dokter['id']; // ID dokter yang login
        $_SESSION['nama_dokter'] = $dokter['nama']; // Nama dokter yang login
        header("Location: index.php"); // Halaman setelah login
        exit;
    } else {
        $alert = "error|Nama atau alamat salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Dokter | Klinik Mitra Waras</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../index.php" class="h1"><b>Klinik</b> Mitra Waras</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Masuk Dokter Klinik Mitra Waras</p>

      <!-- Form Login -->
      <form action="" method="POST">
        <div class="input-group mb-3">
          <input type="text" name="nama" class="form-control" placeholder="Nama" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user-md"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-home"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.min.js"></script>

<?php if (!empty($alert)) : ?>
<script>
    const [type, message] = "<?php echo $alert; ?>".split('|');
    Swal.fire({
        icon: type,
        title: message,
        confirmButtonText: 'OK'
    });
</script>
<?php endif; ?>

</body>
</html>

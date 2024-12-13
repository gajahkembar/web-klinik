<?php
// Koneksi ke database
require_once '../includes/config.php';

// Fungsi untuk generate No RM
function generateNoRM($pdo) {
    $randomNumber = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    $no_rm = "RM-" . $randomNumber;

    // Pastikan tidak ada duplikasi di database
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM pasien WHERE no_rm = :no_rm");
    $stmt->execute([':no_rm' => $no_rm]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        return generateNoRM($pdo); // Rekursi jika duplikasi
    }
    return $no_rm;
}

// Notifikasi status
$alert = "";

// Handle form submission untuk login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];

    // Validasi login menggunakan nama dan alamat
    $stmt = $pdo->prepare("SELECT * FROM pasien WHERE nama = :nama AND alamat = :alamat");
    $stmt->execute([':nama' => $nama, ':alamat' => $alamat]);
    $pasien = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pasien) {
        // Login berhasil
        session_start();
        $_SESSION['id'] = $pasien['id'];
        $_SESSION['nama'] = $pasien['nama'];
        $_SESSION['alamat'] = $pasien['alamat'];
        $_SESSION['id_pasien'] = $pasien['id'];
        header("Location: index.php");
        exit; // Menghentikan eksekusi skrip untuk memastikan pengalihan
    } else {
        $alert = "error|Nama atau alamat salah!";
    }
}

// Handle form submission untuk register
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];

    // Generate No RM
    $no_rm = generateNoRM($pdo);

    // Insert data pasien baru ke database
    $stmt = $pdo->prepare("INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES (:nama, :alamat, :no_ktp, :no_hp, :no_rm)");
    $stmt->execute([
        ':nama' => $nama,
        ':alamat' => $alamat,
        ':no_ktp' => $no_ktp,
        ':no_hp' => $no_hp,
        ':no_rm' => $no_rm
    ]);

    $alert = "success|Pendaftaran berhasil. Nomor Rekam Medis Anda: $no_rm";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login & Register | Klinik Mitra Waras</title>
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
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../index.php" class="h1"><b>Klinik</b> Mitra Waras</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Masuk Pasien atau Daftar Pasien Baru</p>

      <!-- Form Login -->
      <form action="" method="POST">
        <div class="input-group mb-3">
          <input type="text" name="nama" class="form-control" placeholder="Nama" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
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
          <div class="col-8">
            <div class="icheck-primary">
                <p class="mb-0">
                    <a href="#" class="text-center" data-toggle="modal" data-target="#registerModal">Daftar Pasien Baru</a>
                </p>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
          </div>
          <!-- /.col -->
        </div>
        <div class="row">
        </div>
      </form>      
    </div>
  </div>
</div>

<!-- Modal Register -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Daftar Pasien Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="" method="POST">
          <div class="input-group mb-3">
            <input type="text" name="nama" class="form-control" placeholder="Nama" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
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
          <div class="input-group mb-3">
            <input type="text" name="no_ktp" class="form-control" placeholder="Nomor KTP" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-id-card"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="no_hp" class="form-control" placeholder="Nomor HP" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-phone"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
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

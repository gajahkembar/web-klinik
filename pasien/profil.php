<?php
session_start();

require_once '../includes/config.php';

// Pastikan session tersedia
if (!isset($_SESSION['id'])) {
    die("Session ID pasien tidak ditemukan.");
}

$id_pasien = $_SESSION['id'];

// Ambil data pasien berdasarkan id_pasien
$sql_pasien = "SELECT id, nama, alamat, no_ktp, no_hp, no_rm FROM pasien WHERE id = :id_pasien";
$stmt_pasien = $pdo->prepare($sql_pasien);
$stmt_pasien->execute(['id_pasien' => $id_pasien]);
$pasien_data = $stmt_pasien->fetch(PDO::FETCH_ASSOC);

// Proses jika form di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    // Validasi input (opsional)
    if (empty($nama) || empty($alamat) || empty($no_hp)) {
        $error = "Semua kolom harus diisi.";
    } else {
        // Update data pasien
        $sql_update = "UPDATE pasien SET nama = :nama, alamat = :alamat, no_hp = :no_hp WHERE id = :id_pasien";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            'nama' => $nama,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'id_pasien' => $id_pasien
        ]);

        // Kirim respons sukses
        $response = ['status' => 'success', 'message' => 'Data diri pasien berhasil diperbarui.'];
        echo json_encode($response);
        exit; // Menghentikan eksekusi lebih lanjut
    }
}
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Profil Pasien</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- Form untuk update profil pasien -->
                    <form id="update-profile-form">
                        <div class="form-group">
                            <label for="nama">Nama:</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($pasien_data['nama']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat:</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo htmlspecialchars($pasien_data['alamat']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_ktp">No KTP:</label>
                            <input type="text" class="form-control" id="no_ktp" name="no_ktp" value="<?php echo htmlspecialchars($pasien_data['no_ktp']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No HP:</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($pasien_data['no_hp']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="no_rm">No RM:</label>
                            <input type="text" class="form-control" id="no_rm" name="no_rm" value="<?php echo htmlspecialchars($pasien_data['no_rm']); ?>" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Footer -->
<footer class="main-footer">
    <strong>&copy; 2024 Klinik Mitra Waras</strong>
</footer>

<!-- SweetAlert and jQuery -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#update-profile-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah form melakukan submit biasa

        // Ambil data dari form
        var formData = $(this).serialize();

        $.ajax({
            url: '', // URL tempat form diproses (halaman yang sama)
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Update form dengan data baru
                    $('#nama').val($('input[name="nama"]').val());
                    $('#alamat').val($('input[name="alamat"]').val());
                    $('#no_hp').val($('input[name="no_hp"]').val());

                    // Tampilkan SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1000
                    }).then(function() {
                        // Animasi logo dengan latar belakang abu-abu
                        animateLogoWithBackground();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Tidak dapat memperbarui data.',
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal menghubungi server.',
                });
            }
        });
    });

    function animateLogoWithBackground() {
        // Membuat elemen latar belakang abu-abu
        var background = $('<div id="background" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(128, 128, 128, 0.7); z-index: 9998;"></div>');

        // Menambahkan latar belakang dan logo ke dalam body
        var logo = $('<img src="../assets/logo.png" id="logo" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0.5); z-index: 9999; width: 800px; height: 800px;">');
        $('body').append(background);
        $('body').append(logo);

        // Animasi logo bergerak ke bawah dan hilang ke atas
        $('#logo').animate({ 
            top: '100%', 
            opacity: 0 
        }, 3000, function() {
            // Setelah animasi selesai, reload halaman tanpa submit ulang formulir
            location.reload();
        });

        // Animasi latar belakang hilang setelah logo selesai
        $('#background').fadeOut(3000);
    }
});
</script>

</body>
</html>

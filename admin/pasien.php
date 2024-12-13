<?php
session_start();
// Memasukkan file config.php untuk koneksi database
require_once '../includes/config.php';

// Generate no_rm otomatis
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
$no_rm = generateNoRM($pdo);

// Proses tambah data pasien
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_pasien'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];
    $no_rm = $_POST['no_rm'];

    $sql = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES (:nama, :alamat, :no_ktp, :no_hp, :no_rm)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nama' => $nama, ':alamat' => $alamat, ':no_ktp' => $no_ktp, ':no_hp' => $no_hp, ':no_rm' => $no_rm]);

    $_SESSION['success'] = 'Data pasien berhasil ditambahkan!';
    header("Location: pasien.php");
    exit();
}

// Proses edit data pasien
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_pasien'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];
    $no_rm = $_POST['no_rm'];

    $sql = "UPDATE pasien SET nama = :nama, alamat = :alamat, no_ktp = :no_ktp, no_hp = :no_hp, no_rm = :no_rm WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':nama' => $nama, ':alamat' => $alamat, ':no_ktp' => $no_ktp, ':no_hp' => $no_hp, ':no_rm' => $no_rm]);

    $_SESSION['success'] = 'Data pasien berhasil diperbarui!';
    header("Location: pasien.php");
    exit();
}

// Proses hapus data pasien
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM pasien WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $_SESSION['success'] = 'Data pasien berhasil dihapus!';
    header("Location: pasien.php");
    exit();
}

// Mengambil data pasien untuk ditampilkan di tabel
$sql = "SELECT * FROM pasien";
$stmt = $pdo->query($sql);
$pasiens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Pasien</h1>
                </div>
            </div>
        </div>
    </div>

<!-- Main content -->
<section class="content ml-2">
    <div class="container-fluid">
        <div class="row">
            <!-- Button Tambah Pasien -->
            <div class="ml-auto mb-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus"></i> Tambah Pasien
                </button>
            </div>

            <table id="pasienTable" class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 10%;">Nama</th>
                        <th style="width: 20%;">Alamat</th>
                        <th style="width: 10%;">No. KTP</th>
                        <th style="width: 10%;">No. HP</th>
                        <th style="width: 10%;">No. RM</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pasiens as $index => $pasien): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($pasien['nama']) ?></td>
                            <td><?= htmlspecialchars($pasien['alamat']) ?></td>
                            <td><?= htmlspecialchars($pasien['no_ktp']) ?></td>
                            <td><?= htmlspecialchars($pasien['no_hp']) ?></td>
                            <td><?= htmlspecialchars($pasien['no_rm']) ?></td>
                            <td>
                            <!-- Edit Button -->
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal"
                                    data-id="<?= $pasien['id'] ?>"
                                    data-nama="<?= htmlspecialchars($pasien['nama']) ?>"
                                    data-alamat="<?= htmlspecialchars($pasien['alamat']) ?>"
                                    data-no_ktp="<?= htmlspecialchars($pasien['no_ktp']) ?>"
                                    data-no_hp="<?= htmlspecialchars($pasien['no_hp']) ?>"
                                    data-no_rm="<?= htmlspecialchars($pasien['no_rm']) ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <!-- Delete Button -->
                            <button class="btn btn-danger btn-sm" onclick="deletePasien(<?= $pasien['id'] ?>)">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Tambah Pasien -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="pasien.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="no_ktp">No. KTP</label>
                        <input type="text" class="form-control" id="no_ktp" name="no_ktp" required>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    <div class="form-group">
                        <label for="no_rm">No. RM</label>
                        <input type="text" class="form-control" id="no_rm" name="no_rm" value="<?= $no_rm ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="tambah_pasien">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pasien -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="pasien.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pasien</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_nama">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_alamat">Alamat</label>
                        <textarea class="form-control" id="edit_alamat" name="alamat" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_no_ktp">No. KTP</label>
                        <input type="text" class="form-control" id="edit_no_ktp" name="no_ktp" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_no_hp">No. HP</label>
                        <input type="text" class="form-control" id="edit_no_hp" name="no_hp" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_no_rm">No. RM</label>
                        <input type="text" class="form-control" id="edit_no_rm" name="no_rm" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="edit_pasien">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS and DataTables Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Scripts -->
<script>
    $('#pasienTable').DataTable({
        "paging": true,
        "info": true,
        "searching": true,
        "language": {
            "paginate": {
                "previous": "<i class='fas fa-chevron-left'></i>",
                "next": "<i class='fas fa-chevron-right'></i>"
            }
        }
    });

    // Script untuk mengisi data modal edit
    $('#editModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nama = button.data('nama');
        var alamat = button.data('alamat');
        var no_ktp = button.data('no_ktp');
        var no_hp = button.data('no_hp');
        var no_rm = button.data('no_rm');

        $('#edit_id').val(id);
        $('#edit_nama').val(nama);
        $('#edit_alamat').val(alamat);
        $('#edit_no_ktp').val(no_ktp);
        $('#edit_no_hp').val(no_hp);
        $('#edit_no_rm').val(no_rm);
    });

    $(document).ready(function() {
        // Check if there is a success message set
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: '<?= $_SESSION['success']; ?>',
                showConfirmButton: false,
                timer: 2000
            });
            <?php unset($_SESSION['success']); // Clear the success message ?>
        <?php endif; ?>
    });

    function deletePasien(id) {
        // Menggunakan SweetAlert untuk konfirmasi penghapusan
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pasien ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika konfirmasi di tekan, arahkan ke URL untuk menghapus data
                window.location.href = "?delete=" + id;
            }
        });
    }
</script>

</body>
</html>
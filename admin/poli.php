<?php
session_start();
// Memasukkan file config.php untuk koneksi database
require_once '../includes/config.php';

// Proses tambah data poli
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_poli'])) {
    $nama_poli = $_POST['nama_poli'];
    $keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO poli (nama_poli, keterangan) VALUES (:nama_poli, :keterangan)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nama_poli' => $nama_poli, ':keterangan' => $keterangan]);

    $_SESSION['success'] = 'Data poli berhasil ditambahkan!';
    header("Location: poli.php");
    exit();
}

// Proses edit data poli
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_poli'])) {
    $id = $_POST['id'];
    $nama_poli = $_POST['nama_poli'];
    $keterangan = $_POST['keterangan'];

    $sql = "UPDATE poli SET nama_poli = :nama_poli, keterangan = :keterangan WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':nama_poli' => $nama_poli, ':keterangan' => $keterangan]);

    $_SESSION['success'] = 'Data poli berhasil diperbarui!';
    header("Location: poli.php");
    exit();
}

// Proses hapus data poli
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM poli WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $_SESSION['success'] = 'Data pasien berhasil dihapus!';
    header("Location: poli.php");
    exit();
}

// Mengambil data poli untuk ditampilkan di tabel
$sql = "SELECT * FROM poli";
$stmt = $pdo->query($sql);
$poli_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Poli</h1>
                </div>
            </div>
        </div>
    </div>

<!-- Main content -->
<section class="content ml-2">
    <div class="container-fluid">
        <div class="row">
            <!-- Button Tambah Poli ke kanan -->
            <div class="ml-auto mb-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus"></i> Tambah Poli
                </button>
            </div>

            <table id="poliTable" class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                    <th style="width: 5%;">No</th> 
                    <th style="width: 15%;">Nama Poli</th>
                    <th style="width: 70%;">Keterangan</th>
                    <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($poli_data as $index => $poli): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($poli['nama_poli']) ?></td>
                            <td><?= htmlspecialchars($poli['keterangan']) ?></td>
                            <td>
                                <!-- Edit Button with Icon -->
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal"
                                        data-id="<?= $poli['id'] ?>"
                                        data-nama_poli="<?= htmlspecialchars($poli['nama_poli']) ?>"
                                        data-keterangan="<?= htmlspecialchars($poli['keterangan']) ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <!-- Delete Button with Icon -->
                                <button class="btn btn-danger btn-sm" onclick="deletePoli(<?= $poli['id'] ?>)">
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

<!-- Modal Tambah Poli -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="poli.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Poli</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_poli">Nama Poli</label>
                        <input type="text" class="form-control" id="nama_poli" name="nama_poli" required>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="tambah_poli">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Poli -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="poli.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Poli</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label for="edit_nama_poli">Nama Poli</label>
                        <input type="text" class="form-control" id="edit_nama_poli" name="nama_poli" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_keterangan">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="edit_poli">Update</button>
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

<script>
    $(document).ready(function() {
        // Initialize DataTables with pagination
        $('#poliTable').DataTable({
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

        // Auto fill edit modal with existing data
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama_poli = button.data('nama_poli');
            var keterangan = button.data('keterangan');
            
            var modal = $(this);
            modal.find('#edit_id').val(id);
            modal.find('#edit_nama_poli').val(nama_poli);
            modal.find('#edit_keterangan').val(keterangan);
        });
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

    function deletePoli(id) {
        // Menggunakan SweetAlert untuk konfirmasi penghapusan
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data poli ini akan dihapus secara permanen!",
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
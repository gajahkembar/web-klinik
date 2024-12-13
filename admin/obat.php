<?php
session_start();
// Memasukkan file config.php untuk koneksi database
require_once '../includes/config.php';

// Proses tambah data obat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_obat'])) {
    $nama_obat = $_POST['nama_obat'];
    $kemasan = $_POST['kemasan'];
    $harga = $_POST['harga'];

    $sql = "INSERT INTO obat (nama_obat, kemasan, harga) VALUES (:nama_obat, :kemasan, :harga)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nama_obat' => $nama_obat, ':kemasan' => $kemasan, ':harga' => $harga]);
    $_SESSION['success'] = 'Data obat berhasil ditambahkan!';
    header("Location: obat.php");
    exit();
}

// Proses edit data obat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_obat'])) {
    $id = $_POST['id'];
    $nama_obat = $_POST['nama_obat'];
    $kemasan = $_POST['kemasan'];
    $harga = $_POST['harga'];

    $sql = "UPDATE obat SET nama_obat = :nama_obat, kemasan = :kemasan, harga = :harga WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':nama_obat' => $nama_obat, ':kemasan' => $kemasan, ':harga' => $harga]);

    $_SESSION['success'] = 'Data obat berhasil diperbarui!';
    header("Location: obat.php");
    exit();
}

// Proses hapus data obat
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM obat WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);

    $_SESSION['success'] = 'Data obat berhasil dihapus!';
    header("Location: obat.php");
    exit();
}

// Mengambil data obat untuk ditampilkan di tabel
$sql = "SELECT * FROM obat";
$stmt = $pdo->query($sql);
$obat_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Obat</h1>
                </div>
            </div>
        </div>
    </div>

<!-- Main content -->
<section class="content ml-2">
    <div class="container-fluid">
        <div class="row">
            <div class="ml-auto mb-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus"></i> Tambah Obat
                </button>
            </div>

            <table id="obatTable" class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 30%;">Nama Obat</th>
                        <th style="width: 30%;">Kemasan</th>
                        <th style="width: 15%;">Harga</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($obat_data as $index => $obat): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($obat['nama_obat']) ?></td>
                            <td><?= htmlspecialchars($obat['kemasan']) ?></td>
                            <td><?= "Rp " . number_format($obat['harga'], 0, ',', '.') . ",-" ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal"
                                        data-id="<?= $obat['id'] ?>"
                                        data-nama_obat="<?= htmlspecialchars($obat['nama_obat']) ?>"
                                        data-kemasan="<?= htmlspecialchars($obat['kemasan']) ?>"
                                        data-harga="<?= htmlspecialchars($obat['harga']) ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteObat(<?= $obat['id'] ?>)">
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

<!-- Modal Tambah Obat -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Obat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="obat.php">
                    <div class="form-group">
                        <label for="nama_obat">Nama Obat</label>
                        <input type="text" class="form-control" id="nama_obat" name="nama_obat" required>
                    </div>
                    <div class="form-group">
                        <label for="kemasan">Kemasan</label>
                        <input type="text" class="form-control" id="kemasan" name="kemasan" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="tambah_obat" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Obat -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Obat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="obat.php">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_nama_obat">Nama Obat</label>
                        <input type="text" class="form-control" id="edit_nama_obat" name="nama_obat" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_kemasan">Kemasan</label>
                        <input type="text" class="form-control" id="edit_kemasan" name="kemasan" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_harga">Harga</label>
                        <input type="number" class="form-control" id="edit_harga" name="harga" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="edit_obat" class="btn btn-primary">Update Obat</button>
                    </div>
                </form>
            </div>
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
    $('#obatTable').DataTable({
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

    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nama_obat = button.data('nama_obat');
        var kemasan = button.data('kemasan');
        var harga = button.data('harga');

        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#edit_nama_obat').val(nama_obat);
        modal.find('#edit_kemasan').val(kemasan);
        modal.find('#edit_harga').val(harga);
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

    function deleteObat(id) {
        // Menggunakan SweetAlert untuk konfirmasi penghapusan
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data obat ini akan dihapus secara permanen!",
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

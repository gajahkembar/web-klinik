<?php
session_start();
// Memasukkan file config.php untuk koneksi database
require_once '../includes/config.php';

// Proses tambah data dokter
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_dokter'])) {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_poli = $_POST['id_poli'];

    $sql = "INSERT INTO dokter (nama, alamat, no_hp, id_poli) VALUES (:nama, :alamat, :no_hp, :id_poli)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nama' => $nama, ':alamat' => $alamat, ':no_hp' => $no_hp, ':id_poli' => $id_poli]);

    $_SESSION['success'] = 'Data dokter berhasil ditambahkan!';
    header("Location: dokter.php");
    exit();
}

// Proses edit data dokter
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_dokter'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_poli = $_POST['id_poli'];

    $sql = "UPDATE dokter SET nama = :nama, alamat = :alamat, no_hp = :no_hp, id_poli = :id_poli WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id, ':nama' => $nama, ':alamat' => $alamat, ':no_hp' => $no_hp, ':id_poli' => $id_poli]);

    $_SESSION['success'] = 'Data dokter berhasil diperbarui!';
    header("Location: dokter.php");
    exit();
}

// Proses hapus data dokter
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM dokter WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $_SESSION['success'] = 'Data dokter berhasil dihapus!';
    header("Location: dokter.php");
    exit();
}

// Mengambil data dokter untuk ditampilkan di tabel
$sql = "SELECT * FROM dokter";
$stmt = $pdo->query($sql);
$dokter_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Dokter</h1>
                </div>
            </div>
        </div>
    </div>

<!-- Main content -->
<section class="content ml-2">
    <div class="container-fluid">
        <div class="row">
            <!-- Button Tambah Dokter ke kanan -->
            <div class="ml-auto mb-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus"></i> Tambah Dokter
                </button>
            </div>

            <table id="dokterTable" class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 10%;">Nama</th>
                        <th style="width: 40%;">Alamat</th>
                        <th style="width: 10%;">No. HP</th>
                        <th style="width: 10%;">Poli</th>
                        <th style="width: 10%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dokter_data as $index => $dokter): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($dokter['nama']) ?></td>
                            <td><?= htmlspecialchars($dokter['alamat']) ?></td>
                            <td><?= htmlspecialchars($dokter['no_hp']) ?></td>
                            <td><?php 
                                // Menampilkan nama poli berdasarkan id_poli
                                $poli_id = $dokter['id_poli'];
                                $poli_sql = "SELECT nama_poli FROM poli WHERE id = :id_poli";
                                $poli_stmt = $pdo->prepare($poli_sql);
                                $poli_stmt->execute([':id_poli' => $poli_id]);
                                $poli = $poli_stmt->fetch(PDO::FETCH_ASSOC);
                                echo $poli['nama_poli'];
                            ?></td>
                            <td>
                                <!-- Edit Button with Icon -->
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal"
                                        data-id="<?= $dokter['id'] ?>"
                                        data-nama="<?= htmlspecialchars($dokter['nama']) ?>"
                                        data-alamat="<?= htmlspecialchars($dokter['alamat']) ?>"
                                        data-no_hp="<?= htmlspecialchars($dokter['no_hp']) ?>"
                                        data-id_poli="<?= $dokter['id_poli'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <!-- Delete Button with Icon -->
                                <button class="btn btn-danger btn-sm" onclick="deleteDokter(<?= $dokter['id'] ?>)">
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

<!-- Modal Tambah Dokter -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Dokter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="dokter.php">
                    <div class="form-group">
                        <label for="nama">Nama Dokter</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    <div class="form-group">
                        <label for="id_poli">Poli</label>
                        <select class="form-control" id="id_poli" name="id_poli" required>
                            <?php
                            // Menampilkan daftar poli untuk dropdown
                            $poli_sql = "SELECT id, nama_poli FROM poli";
                            $poli_stmt = $pdo->query($poli_sql);
                            while ($poli = $poli_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?= $poli['id'] ?>"><?= $poli['nama_poli'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="tambah_dokter" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Dokter -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Dokter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="dokter.php">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_nama">Nama Dokter</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_alamat">Alamat</label>
                        <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_no_hp">No. HP</label>
                        <input type="text" class="form-control" id="edit_no_hp" name="no_hp" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_id_poli">Poli</label>
                        <select class="form-control" id="edit_id_poli" name="id_poli" required>
                            <?php
                            // Menampilkan daftar poli untuk dropdown
                            $poli_sql = "SELECT id, nama_poli FROM poli";
                            $poli_stmt = $pdo->query($poli_sql);
                            while ($poli = $poli_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?= $poli['id'] ?>"><?= $poli['nama_poli'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="edit_dokter" class="btn btn-primary">Update Dokter</button>
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

<!-- JavaScript untuk mengisi form edit -->
<script>
    $('#dokterTable').DataTable({
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
        var button = $(event.relatedTarget); // Tombol Edit
        var id = button.data('id');
        var nama = button.data('nama');
        var alamat = button.data('alamat');
        var no_hp = button.data('no_hp');
        var id_poli = button.data('id_poli');

        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#edit_nama').val(nama);
        modal.find('#edit_alamat').val(alamat);
        modal.find('#edit_no_hp').val(no_hp);
        modal.find('#edit_id_poli').val(id_poli);
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

    function deleteDokter(id) {
        // Menggunakan SweetAlert untuk konfirmasi penghapusan
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data dokter ini akan dihapus secara permanen!",
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
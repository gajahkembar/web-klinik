<?php
session_start();
// Memasukkan file config.php untuk koneksi database
require_once '../includes/config.php';

// Proses tambah jadwal periksa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_jadwal'])) {
    $id_dokter = $_POST['id_dokter'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    $sql = "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai) VALUES (:id_dokter, :hari, :jam_mulai, :jam_selesai)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_dokter' => $id_dokter,
        ':hari' => $hari,
        ':jam_mulai' => $jam_mulai,
        ':jam_selesai' => $jam_selesai,
    ]);

    $_SESSION['success'] = 'Jadwal periksa berhasil ditambahkan!';
    header("Location: jadwal_periksa.php");
    exit();
}

// Proses edit jadwal periksa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_jadwal'])) {
    $id = $_POST['id'];
    $id_dokter = $_POST['id_dokter'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    $sql = "UPDATE jadwal_periksa SET hari = :hari, jam_mulai = :jam_mulai, jam_selesai = :jam_selesai WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':hari' => $hari,
        ':jam_mulai' => $jam_mulai,
        ':jam_selesai' => $jam_selesai,
    ]);

    $_SESSION['success'] = 'Jadwal periksa berhasil diperbarui!';
    header("Location: jadwal_periksa.php");
    exit();
}

// Proses hapus jadwal periksa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM jadwal_periksa WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $_SESSION['success'] = 'Jadwal periksa berhasil dihapus!';
    header("Location: jadwal_periksa.php");
    exit();
}

// Mengambil data jadwal periksa untuk ditampilkan di tabel
$sql = "SELECT jp.*, d.nama AS nama_dokter, p.nama_poli AS nama_poli 
        FROM jadwal_periksa jp 
        JOIN dokter d ON jp.id_dokter = d.id
        JOIN poli p ON d.id_poli = p.id";
$stmt = $pdo->query($sql);
$jadwal_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mengambil data poli untuk dropdown
$sql_poli = "SELECT id, nama_poli FROM poli";
$stmt_poli = $pdo->query($sql_poli);
$poli_data = $stmt_poli->fetchAll(PDO::FETCH_ASSOC);

// Memeriksa jika ada permintaan untuk mendapatkan dokter
if (isset($_GET['id_poli'])) {
    $id_poli = $_GET['id_poli'];
    $sql_dokter = "SELECT id, nama FROM dokter WHERE id_poli = :id_poli";
    $stmt_dokter = $pdo->prepare($sql_dokter);
    $stmt_dokter->execute([':id_poli' => $id_poli]);
    $dokter_data = $stmt_dokter->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($dokter_data); // Mengirimkan data dokter dalam format JSON
    exit();
}
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manajemen Jadwal Periksa</h1>
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
                    <i class="fas fa-plus"></i> Tambah Jadwal
                </button>
            </div>

            <table id="jadwalTable" class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Dokter</th>
                    <th style="width: 20%;">Poli</th>
                    <th style="width: 10%;">Hari</th>
                    <th style="width: 10%;">Jam Mulai</th>
                    <th style="width: 10%;">Jam Selesai</th>
                    <th style="width: 10%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jadwal_data as $index => $jadwal): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($jadwal['nama_dokter']) ?></td>
                        <td><?= htmlspecialchars($jadwal['nama_poli']) ?></td>
                        <td><?= htmlspecialchars($jadwal['hari']) ?></td>
                        <td><?= htmlspecialchars($jadwal['jam_mulai']) ?></td>
                        <td><?= htmlspecialchars($jadwal['jam_selesai']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal"
                                data-id="<?= $jadwal['id'] ?>"
                                data-id_dokter="<?= $jadwal['id_dokter'] ?>"
                                data-nama_dokter="<?= htmlspecialchars($jadwal['nama_dokter']) ?>"
                                data-hari="<?= htmlspecialchars($jadwal['hari']) ?>"
                                data-jam_mulai="<?= htmlspecialchars($jadwal['jam_mulai']) ?>"
                                data-jam_selesai="<?= htmlspecialchars($jadwal['jam_selesai']) ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="deleteJadwal(<?= $jadwal['id'] ?>)">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="jadwal_periksa.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_poli">Nama Poli</label>
                        <select class="form-control" id="id_poli" name="id_poli" required>
                            <option value="">Pilih Poli</option>
                            <?php foreach ($poli_data as $poli): ?>
                                <option value="<?= $poli['id'] ?>"><?= htmlspecialchars($poli['nama_poli']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="id_dokter">Nama Dokter</label>
                        <select class="form-control" id="id_dokter" name="id_dokter" required>
                            <option value="">Pilih Dokter</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select class="form-control" id="hari" name="hari" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jam_mulai">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="jam_selesai">Jam Selesai</label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="tambah_jadwal">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="jadwal_periksa.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <!-- Menampilkan nama dokter sebagai teks biasa, bukan dropdown -->
                    <div class="form-group">
                        <label for="edit_nama_dokter">Nama Dokter</label>
                        <input type="text" class="form-control" id="edit_nama_dokter" name="nama_dokter" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_hari">Hari</label>
                        <select class="form-control" id="edit_hari" name="hari" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_jam_mulai">Jam Mulai</label>
                        <input type="time" class="form-control" id="edit_jam_mulai" name="jam_mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_jam_selesai">Jam Selesai</label>
                        <input type="time" class="form-control" id="edit_jam_selesai" name="jam_selesai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="edit_jadwal">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: '<?= $_SESSION['success'] ?>',
            showConfirmButton: false,
            timer: 1500
        });
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    // Fungsi untuk konfirmasi hapus jadwal
    function deleteJadwal(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "jadwal_periksa.php?delete=" + id;
            }
        });
    }

    // Fungsi untuk memuat dokter berdasarkan poli yang dipilih
    document.getElementById('id_poli').addEventListener('change', function() {
        const poliId = this.value;

        if (poliId) {
            // Menggunakan fetch untuk AJAX
            fetch('jadwal_periksa.php?id_poli=' + poliId)
                .then(response => response.json())
                .then(data => {
                    const dokterSelect = document.getElementById('id_dokter');
                    dokterSelect.innerHTML = '<option value="">Pilih Dokter</option>'; // Clear existing options
                    data.forEach(dokter => {
                        dokterSelect.innerHTML += `<option value="${dokter.id}">${dokter.nama}</option>`;
                    });
                });
        } else {
            // Clear dokter dropdown if no poli is selected
            document.getElementById('id_dokter').innerHTML = '<option value="">Pilih Dokter</option>';
        }
    });

    // Menyisipkan data ke modal edit
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var id_dokter = button.data('id_dokter');
        var nama_dokter = button.data('nama_dokter');
        var hari = button.data('hari');
        var jam_mulai = button.data('jam_mulai');
        var jam_selesai = button.data('jam_selesai');

        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#edit_nama_dokter').val(nama_dokter);  // Menampilkan nama dokter yang sedang diedit
        modal.find('#edit_hari').val(hari);
        modal.find('#edit_jam_mulai').val(jam_mulai);
        modal.find('#edit_jam_selesai').val(jam_selesai);
    });

    $('#jadwalTable').DataTable({
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
</script>

</body>
</html>
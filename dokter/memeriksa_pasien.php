<?php
session_start();
include('../includes/config.php');

// Pastikan session tersedia
if (!isset($_SESSION['id'])) {
    die("Session ID dokter tidak ditemukan.");
}

$id_dokter = $_SESSION['id'];

// Query untuk mengambil data dokter
$stmt = $pdo->prepare("SELECT d.id, d.nama, d.alamat, d.no_hp, d.id_poli, p.nama_poli 
                       FROM dokter d 
                       JOIN poli p ON d.id_poli = p.id
                       WHERE d.id = :id");
$stmt->execute([':id' => $id_dokter]);
$dokter = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dokter) {
    die("Data dokter tidak ditemukan.");
}

// Query untuk mengambil data dari tabel daftar_poli, pasien, jadwal_periksa
$query = "SELECT dp.id, dp.no_antrian, p.nama, p.no_rm, dp.keluhan, 
                 (SELECT COUNT(*) FROM periksa WHERE id_daftar_poli = dp.id) AS sudah_diperiksa
          FROM daftar_poli dp
          JOIN pasien p ON dp.id_pasien = p.id
          JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
          WHERE jp.id_dokter = :id_dokter
          ORDER BY dp.no_antrian ASC";
$stmt = $pdo->prepare($query);
$stmt->execute([':id_dokter' => $id_dokter]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Reset nomor antrean mulai dari 1
$data = array_values($data); // Mengatur ulang indeks array untuk memudahkan iterasi

// Query untuk mengambil data obat
$query_obat = "SELECT id, nama_obat, harga FROM obat ORDER BY nama_obat ASC";
$stmt_obat = $pdo->prepare($query_obat);
$stmt_obat->execute();
$obat_data = $stmt_obat->fetchAll(PDO::FETCH_ASSOC);

// Menangani form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_daftar_poli = $_POST['id_daftar_poli'];
    $tgl_periksa_date = $_POST['tgl_periksa_date'];
    $tgl_periksa_time = $_POST['tgl_periksa_time'];
    $tgl_periksa = $tgl_periksa_date . ' ' . $tgl_periksa_time; // Combine date and time
    $catatan = $_POST['catatan'];
    $biaya_periksa = $_POST['biaya_periksa'];  // Get the calculated cost for examination
    $obat_selected = $_POST['obat'];  // Get selected medicines from the form

    // Start transaction to ensure both inserts are successful
    try {
        $pdo->beginTransaction();

        // Insert data into periksa table
        $stmt = $pdo->prepare("INSERT INTO periksa (id_daftar_poli, tgl_periksa, catatan, biaya_periksa) 
                               VALUES (:id_daftar_poli, :tgl_periksa, :catatan, :biaya_periksa)");
        $stmt->execute([
            ':id_daftar_poli' => $id_daftar_poli,
            ':tgl_periksa' => $tgl_periksa,
            ':catatan' => $catatan,
            ':biaya_periksa' => str_replace(['Rp', '.'], '', $biaya_periksa)  // Clean the Rp and dots before saving
        ]);

        // Get the last inserted id from periksa table
        $periksa_id = $pdo->lastInsertId();

        // Insert selected medicines into detail_periksa table
        if (!empty($obat_selected)) {
            $stmt_obat = $pdo->prepare("INSERT INTO detail_periksa (id_periksa, id_obat) VALUES (:id_periksa, :id_obat)");
            foreach ($obat_selected as $obat_id) {
                $stmt_obat->execute([
                    ':id_periksa' => $periksa_id,
                    ':id_obat' => $obat_id
                ]);
            }
        }

        // Commit the transaction
        $pdo->commit();

        // Display success message using SweetAlert
        echo '<script>
                Swal.fire({
                    title: "Berhasil!",
                    text: "Data pemeriksaan dan obat berhasil disimpan.",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(function() {
                    window.location.reload();
                });
              </script>';
    } catch (Exception $e) {
        // Rollback transaction if something goes wrong
        $pdo->rollBack();
        echo '<script>
                Swal.fire({
                    title: "Error!",
                    text: "Terjadi kesalahan saat menyimpan data.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
              </script>';
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
                    <h1 class="m-0">Manajemen Data Periksa</h1>
                </div>
            </div>
        </div>
    </div>

<!-- Main content -->
<section class="content ml-2">
    <div class="container-fluid">
        <div class="row">
            <div class="ml-auto mr-3"></div>

            <table id="periksaTable" class="table table-bordered table-striped mt-3">
                <thead>
                    <tr>
                        <th style="width: 5%">No Urut</th>
                        <th style="width: 15%">Nama Pasien</th>
                        <th style="width: 15%">No Rekam Medis</th>
                        <th style="width: 51%">Keluhan</th>
                        <th style="width: 6%">Status</th>
                        <th style="width: 8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $index => $row): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($row['nama']) ?></td>
                                <td><?= htmlspecialchars($row['no_rm']) ?></td>
                                <td><?= htmlspecialchars($row['keluhan']) ?></td>
                                <td>
                                    <?php if ($row['sudah_diperiksa'] > 0): ?>
                                        <span class="badge badge-success">Sudah Diperiksa</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Belum Diperiksa</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                <?php if ($row['sudah_diperiksa'] == 0): ?>
                                    <button class="btn btn-sm btn-primary" 
                                            data-toggle="modal" 
                                            data-target="#periksaModal"
                                            data-id="<?= htmlspecialchars($row['id']) ?>"
                                            data-nama="<?= htmlspecialchars($row['nama']) ?>"
                                            data-tanggal="<?= date('Y-m-d') ?>"
                                            data-keluhan="<?= htmlspecialchars($row['keluhan']) ?>">
                                        Periksa
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        Sudah Diperiksa
                                    </button>
                                <?php endif; ?>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6">Tidak ada data.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="periksaModal" tabindex="-1" role="dialog" aria-labelledby="periksaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="periksaForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="periksaModalLabel">Form Periksa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modalId" name="id_daftar_poli" class="form-control" readonly>
                    <div class="form-group">
                        <label>Nama Pasien</label>
                        <input type="text" id="modalNama" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Periksa</label>
                        <input type="text" id="modalTanggal" name="tgl_periksa_date" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jam Periksa</label>
                        <input type="time" id="modalJam" name="tgl_periksa_time" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Keluhan</label>
                        <textarea id="modalKeluhan" class="form-control" readonly></textarea>
                    </div>
                    <div class="form-group">
                        <label>Catatan</label>
                        <textarea id="modalCatatan" name="catatan" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Obat</label><br>
                        <select id="modalObat" name="obat[]" class="form-control select2" multiple="multiple" data-obat='<?= json_encode($obat_data) ?>'>
                            <option value="">Pilih Obat</option>
                            <?php foreach ($obat_data as $obat): ?>
                                <option value="<?= $obat['id'] ?>" data-harga="<?= $obat['harga'] ?>">
                                    <?= htmlspecialchars($obat['nama_obat']) ?> (Rp<?= number_format($obat['harga'], 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Biaya</label>
                        <input type="text" id="modalBiaya" name="biaya_periksa" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="main-footer">
    <strong>&copy; 2024 Klinik Mitra Waras.</strong> All rights reserved.
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $('#periksaTable').DataTable({
        paging: true,
        info: false,
        searching: false,
        language: {
            paginate: {
                previous: "<i class='fas fa-chevron-left'></i>",
                next: "<i class='fas fa-chevron-right'></i>"
            }
        }
    });

    $(document).ready(function() {
        $('#modalObat').select2({
            placeholder: "Pilih Obat",
            allowClear: true,
            theme: "classic",  // Anda bisa pilih tema lain jika perlu
            width: '100%',
            closeOnSelect: false // Agar memilih beberapa item tidak menutup dropdown
        });
    });

    $('#periksaModal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget);
        var id = button.data('id');
        var nama = button.data('nama');
        var keluhan = button.data('keluhan');
        var tanggal = new Date(); // Ambil waktu saat ini
        var tanggalStr = tanggal.toISOString().split('T')[0]; // Format tanggal YYYY-MM-DD
        var waktuStr = tanggal.toTimeString().split(' ')[0].substr(0, 5); // Format waktu HH:MM

        $('#modalId').val(id);
        $('#modalNama').val(nama);
        $('#modalKeluhan').val(keluhan);
        $('#modalTanggal').val(tanggalStr);
        $('#modalJam').val(waktuStr); // Set waktu default
    });

    $('#periksaForm').on('submit', function (e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serialize();
        
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: formData,
            success: function (response) {
                Swal.fire({
                    title: "Sukses!",
                    text: "Pemeriksaan berhasil disimpan.",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(function() {
                    location.reload();
                });
            }
        });
    });

    $(document).ready(function() {
        // Set default biaya periksa
        var biayaPeriksa = 50000;
        $('#modalBiaya').val(formatRupiah(biayaPeriksa)); // Tampilkan biaya default dengan format rupiah

        // Initialize select2 untuk pilihan obat
        $('#modalObat').select2({
            placeholder: "Pilih Obat",
            allowClear: true,
            theme: "classic",
            width: '100%',
            closeOnSelect: false
        });

        // Hitung biaya saat obat dipilih atau dihapus
        $('#modalObat').on('change', function() {
            var totalBiaya = biayaPeriksa; // Mulai dengan biaya periksa default
            $('#modalObat option:selected').each(function() {
                var hargaObat = parseInt($(this).data('harga')); // Ambil harga dari data-harga
                totalBiaya += hargaObat; // Tambahkan harga obat ke total biaya
            });
            $('#modalBiaya').val(formatRupiah(totalBiaya)); // Tampilkan total biaya dengan format rupiah
        });
        
        // Format angka menjadi format Rupiah
        function formatRupiah(angka) {
            var number_string = angka.toString(),
                sisa = number_string.length % 3,
                rupiah = number_string.substr(0, sisa),
                ribuan = number_string.substr(sisa).match(/\d{3}/g);
            
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            
            return 'Rp ' + rupiah;
        }
    });
</script>
</body>
</html>
<?php
session_start();

require_once '../includes/config.php';

// Pastikan session tersedia
if (!isset($_SESSION['id'])) {
    die("Session ID dokter tidak ditemukan.");
}

$id_dokter = $_SESSION['id'];

// Ambil data pasien yang pernah diperiksa oleh dokter yang sedang login
$sql_pasien = "SELECT DISTINCT 
                    p.id AS id_pasien, 
                    p.nama AS nama_pasien, 
                    p.alamat, 
                    p.no_ktp, 
                    p.no_hp, 
                    p.no_rm
                FROM pasien p
                JOIN daftar_poli dp ON p.id = dp.id_pasien
                JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                JOIN dokter d ON jp.id_dokter = d.id
                WHERE jp.id_dokter = :id_dokter
                ORDER BY p.nama ASC";

$stmt_pasien = $pdo->prepare($sql_pasien);
$stmt_pasien->execute(['id_dokter' => $id_dokter]);
$result_pasien = $stmt_pasien;

// Logic to fetch patient history when clicked on the button
if (isset($_GET['id_pasien'])) {
    $id_pasien = $_GET['id_pasien'];
    // Fetch medical history of the selected patient
    $sql_riwayat = "SELECT dp.id AS id_daftar_poli, 
                            dp.keluhan, 
                            p.nama AS nama_pasien, 
                            pr.tgl_periksa, 
                            pr.catatan, 
                            pr.biaya_periksa,
                            GROUP_CONCAT(CAST(o.nama_obat AS CHAR) SEPARATOR ', ') AS daftar_obat
                    FROM daftar_poli dp
                    JOIN pasien p ON dp.id_pasien = p.id
                    JOIN jadwal_periksa jp ON dp.id_jadwal = jp.id
                    JOIN dokter d ON jp.id_dokter = d.id
                    LEFT JOIN periksa pr ON dp.id = pr.id_daftar_poli
                    LEFT JOIN detail_periksa dpd ON pr.id = dpd.id_periksa
                    LEFT JOIN obat o ON dpd.id_obat = o.id
                    WHERE jp.id_dokter = :id_dokter AND p.id = :id_pasien
                    GROUP BY dp.id
                    ORDER BY pr.tgl_periksa DESC";

    $stmt_riwayat = $pdo->prepare($sql_riwayat);
    $stmt_riwayat->execute(['id_dokter' => $id_dokter, 'id_pasien' => $id_pasien]);
    $riwayat_data = $stmt_riwayat->fetchAll(PDO::FETCH_ASSOC);
}

// Bagian untuk format tanggal dalam PHP
function formatTanggal($tgl) {
    $date = new DateTime($tgl);
    return $date->format('d/m/Y H:i'); // Format dd/mm/yyyy HH:mm
}
?>

<?php include('header.php'); ?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Data Pasien</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <table id="pasienTable" class="table table-bordered table-striped mt-3">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Alamat</th>
                            <th>No. KTP</th>
                            <th>No. Telepon</th>
                            <th>No. RM</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = $result_pasien->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_pasien']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['alamat']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['no_ktp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['no_hp']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['no_rm']) . "</td>";
                            echo "<td>
                                <a href='?id_pasien=" . htmlspecialchars($row['id_pasien']) . "' class='btn btn-sm btn-info'>Lihat Riwayat</a>
                              </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal untuk melihat riwayat -->
<?php if (isset($riwayat_data)): ?>
<div class="modal fade" id="riwayatModal" tabindex="-1" role="dialog" aria-labelledby="riwayatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="riwayatModalLabel">Detail Riwayat Pasien</h5>
                <a href="javascript:history.back()" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <?php 
                $dataLengkap = array_filter($riwayat_data, function($riwayat) {
                    return !empty($riwayat['tgl_periksa']); // Filter hanya data dengan tanggal periksa
                });
                ?>
                
                <?php if (empty($dataLengkap)): ?>
                    <!-- Jika semua data tidak memiliki tanggal periksa -->
                    <p>Belum Pernah Periksa</p>
                <?php else: ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Periksa</th>
                                <th>Nama Pasien</th>
                                <th>Keluhan</th>
                                <th>Catatan</th>
                                <th>Obat</th>
                                <th>Biaya Periksa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($dataLengkap as $riwayat) {
                                // Memformat tanggal menggunakan fungsi formatTanggal
                                $formattedDate = formatTanggal($riwayat['tgl_periksa']);
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
                                echo "<td>" . htmlspecialchars($riwayat['nama_pasien']) . "</td>";
                                echo "<td>" . htmlspecialchars($riwayat['keluhan']) . "</td>";
                                echo "<td>" . htmlspecialchars($riwayat['catatan']) . "</td>";
                                echo "<td>" . htmlspecialchars($riwayat['daftar_obat']) . "</td>";
                                echo "<td>Rp " . number_format($riwayat['biaya_periksa'], 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Footer -->
<footer class="main-footer">
    <strong>&copy; 2024 Klinik Mitra Waras</strong>
</footer>

<!-- JS dan DataTables Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#pasienTable').DataTable();
    });

    $(document).ready(function() {
      <?php if (isset($riwayat_data)): ?>
          $('#riwayatModal').modal('show');
      <?php endif; ?>
  });
</script>

</body>
</html>